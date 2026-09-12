<?php

namespace App\Services\Payment;

use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Models\Subscription;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Asaas payment gateway integration.
 *
 * Uses Guzzle to communicate with Asaas API v3.
 *   - Sandbox:    https://sandbox.asaas.com/api/v3
 *   - Production: https://www.asaas.com/api/v3
 *
 * Credentials are never hardcoded - resolved from config/payment.php
 * which in turn reads env(PAYMENT_SECRET_KEY).
 *
 * Docs mapping:
 *   POST   /customers           -> createCustomer
 *   POST   /subscriptions       -> createSubscription (recurring)
 *   POST   /payments            -> createPayment (single charge)
 *   GET    /payments/{id}       -> getPaymentStatus
 *   GET    /subscriptions/{id}  -> getSubscription
 *   DELETE /subscriptions/{id}  -> cancelSubscription
 */
class AsaasGateway implements PaymentGatewayInterface
{
    protected Client $client;

    protected string $baseUrl;

    protected string $apiKey;

    protected ?string $webhookToken;

    public function __construct(?Client $client = null)
    {
        $this->baseUrl = $this->resolveBaseUrl();
        $this->apiKey = (string) config('payment.secret_key', config('payment.asaas.api_key', ''));
        // Fallback to dedicated env var, otherwise generic webhook secret.
        $this->webhookToken = config('payment.asaas.webhook_token')
            ?? config('payment.webhook_secret');

        $this->client = $client ?? new Client([
            'base_uri' => rtrim($this->baseUrl, '/') . '/',
            'timeout' => (int) config('payment.asaas.timeout', 15),
            'connect_timeout' => (int) config('payment.asaas.connect_timeout', 5),
            'http_errors' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function name(): string
    {
        return 'asaas';
    }

    public function isSandbox(): bool
    {
        return config('payment.env', 'sandbox') !== 'production';
    }

    // -----------------------------------------------------------------
    // Public API required by task spec (outside the interface contract)
    // -----------------------------------------------------------------

    /**
     * Creates a customer at Asaas.
     *
     * @param  array<string, mixed>  $data  Must contain at least name + email or cpfCnpj.
     * @return array<string, mixed> Asaas customer payload.
     *
     * @throws \RuntimeException on failure.
     */
    public function createCustomer(array $data): array
    {
        $this->assertConfigured();

        $payload = $this->normalizeCustomerPayload($data);

        Log::info('AsaasGateway.createCustomer.request', [
            'payload' => $this->redact($payload),
        ]);

        try {
            $response = $this->request('POST', 'customers', ['json' => $payload]);
        } catch (GuzzleException $e) {
            $this->logException('createCustomer', $e, $payload);
            throw new \RuntimeException('Falha ao criar cliente no Asaas: ' . $e->getMessage(), 0, $e);
        }

        Log::info('AsaasGateway.createCustomer.success', ['id' => $response['id'] ?? null]);

        return $response;
    }

    /**
     * Creates a single payment (avulsa) at Asaas.
     *
     * @param  array<string, mixed>  $data  Requires: customer, billingType, value, dueDate
     * @return array<string, mixed>
     */
    public function createPayment(array $data): array
    {
        $this->assertConfigured();

        Log::info('AsaasGateway.createPayment.request', ['payload' => $this->redact($data)]);

        try {
            $response = $this->request('POST', 'payments', ['json' => $data]);
        } catch (GuzzleException $e) {
            $this->logException('createPayment', $e, $data);
            throw new \RuntimeException('Falha ao criar cobrança no Asaas: ' . $e->getMessage(), 0, $e);
        }

        Log::info('AsaasGateway.createPayment.success', ['id' => $response['id'] ?? null]);

        return $response;
    }

    /**
     * Retrieves payment status.
     * GET /payments/{id}
     *
     * @return array<string, mixed>
     */
    public function getPaymentStatus(string $paymentId): array
    {
        $this->assertConfigured();

        try {
            $response = $this->request('GET', "payments/{$paymentId}");
        } catch (GuzzleException $e) {
            $this->logException('getPaymentStatus', $e, ['paymentId' => $paymentId]);
            throw new \RuntimeException('Falha ao consultar pagamento no Asaas: ' . $e->getMessage(), 0, $e);
        }

        return $response;
    }

    // -----------------------------------------------------------------
    // PaymentGatewayInterface implementation
    // -----------------------------------------------------------------

    /**
     * Creates a subscription intent at Asaas.
     *
     * Flow:
     *  1. Ensure gateway customer exists (create or reuse via externalReference)
     *  2. POST /subscriptions
     *  3. Persist traceable SubscriptionTransaction as pending
     *  4. Return PaymentGatewayResponse with gateway id + checkout url when available
     */
    public function createSubscription(Subscription $subscription): PaymentGatewayResponse
    {
        if (blank($this->apiKey)) {
            Log::warning('AsaasGateway.createSubscription.missing_api_key', [
                'subscription_id' => $subscription->id,
                'env' => config('payment.env'),
            ]);

            // In sandbox without keys we gracefully degrade to a local stub so
            // local dev is not blocked when PAYMENT_SECRET_KEY is empty.
            if ($this->isSandbox()) {
                return $this->sandboxFallback($subscription, 'API key não configurada - fallback sandbox.');
            }

            return PaymentGatewayResponse::failure('Gateway Asaas não configurado (PAYMENT_SECRET_KEY ausente).');
        }

        try {
            $customerId = $this->ensureCustomerForSubscription($subscription);

            $payload = $this->buildSubscriptionPayload($subscription, $customerId);

            Log::info('AsaasGateway.createSubscription.request', [
                'subscription_id' => $subscription->id,
                'payload' => $this->redact($payload),
            ]);

            $response = $this->request('POST', 'subscriptions', ['json' => $payload]);

            $gatewayId = $response['id'] ?? null;
            $checkoutUrl = $response['invoiceUrl'] ?? $response['bankSlipUrl'] ?? $response['invoice_url'] ?? null;

            // Fallback: some billing types return payment link inside 'payments' or use direct URL
            if (! $checkoutUrl && isset($response['id'])) {
                $checkoutUrl = $this->extractCheckoutUrl($response);
            }

            Log::info('AsaasGateway.createSubscription.success', [
                'subscription_id' => $subscription->id,
                'gateway_id' => $gatewayId,
            ]);

            // Traceability - same pattern as SandboxGateway
            $subscription->transactions()->create([
                'subscription_id' => $subscription->id,
                'amount_cents' => $subscription->value_cents,
                'status' => TransactionStatus::Pending->value,
                'gateway_transaction_id' => $gatewayId,
                'payload' => ['gateway' => $this->name(), 'asaas' => $response],
            ]);

            return PaymentGatewayResponse::success(
                gatewayTransactionId: $gatewayId,
                checkoutUrl: $checkoutUrl,
                raw: $response,
            );
        } catch (GuzzleException $e) {
            $this->logException('createSubscription', $e, ['subscription_id' => $subscription->id]);

            $message = $this->extractErrorMessage($e);
            $raw = $this->extractErrorRaw($e);

            return PaymentGatewayResponse::failure(
                message: $message ?: 'Falha ao criar assinatura no Asaas.',
                raw: $raw,
            );
        } catch (\Throwable $e) {
            Log::error('AsaasGateway.createSubscription.unexpected', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return PaymentGatewayResponse::failure('Erro inesperado ao criar assinatura no Asaas.');
        }
    }

    public function cancelSubscription(Subscription $subscription): void
    {
        $gatewayId = $subscription->gateway_transaction_id;

        if (blank($gatewayId)) {
            Log::warning('AsaasGateway.cancelSubscription.missing_gateway_id', [
                'subscription_id' => $subscription->id,
            ]);

            // Still mark locally as cancelled for consistency
            $subscription->update([
                'status' => SubscriptionStatus::Cancelled->value,
                'cancelled_at' => now(),
            ]);

            return;
        }

        // If sandbox without key, just log and mark cancelled (test-friendly)
        if (blank($this->apiKey) && $this->isSandbox() && str_starts_with((string) $gatewayId, 'sandbox_')) {
            Log::info('AsaasGateway.cancelSubscription.sandbox_skipped', [
                'subscription_id' => $subscription->id,
                'gateway_id' => $gatewayId,
            ]);

            $subscription->update([
                'status' => SubscriptionStatus::Cancelled->value,
                'cancelled_at' => now(),
            ]);

            return;
        }

        $this->assertConfigured();

        Log::info('AsaasGateway.cancelSubscription.request', [
            'subscription_id' => $subscription->id,
            'gateway_id' => $gatewayId,
        ]);

        try {
            // DELETE /subscriptions/{id}
            $this->request('DELETE', "subscriptions/{$gatewayId}");

            Log::info('AsaasGateway.cancelSubscription.success', [
                'subscription_id' => $subscription->id,
                'gateway_id' => $gatewayId,
            ]);
        } catch (ClientException $e) {
            // 404 means already deleted / not found - treat as success locally
            $status = $e->getResponse()?->getStatusCode();
            if ($status === 404) {
                Log::warning('AsaasGateway.cancelSubscription.not_found', [
                    'subscription_id' => $subscription->id,
                    'gateway_id' => $gatewayId,
                ]);
            } else {
                $this->logException('cancelSubscription', $e, ['gateway_id' => $gatewayId]);
                throw new \RuntimeException('Falha ao cancelar assinatura no Asaas: ' . $this->extractErrorMessage($e), 0, $e);
            }
        } catch (GuzzleException $e) {
            $this->logException('cancelSubscription', $e, ['gateway_id' => $gatewayId]);
            throw new \RuntimeException('Falha ao cancelar assinatura no Asaas: ' . $e->getMessage(), 0, $e);
        }

        $subscription->update([
            'status' => SubscriptionStatus::Cancelled->value,
            'cancelled_at' => now(),
        ]);

        $subscription->transactions()->create([
            'amount_cents' => 0,
            'status' => TransactionStatus::Pending->value,
            'gateway_transaction_id' => $gatewayId,
            'payload' => ['gateway' => $this->name(), 'event' => 'subscription.cancelled', 'gateway_id' => $gatewayId],
        ]);
    }

    /**
     * Retrieves subscription from Asaas.
     * GET /subscriptions/{id}
     *
     * @return array<string, mixed>
     */
    public function getSubscription(string $gatewayId): array
    {
        $this->assertConfigured();

        // Sandbox fallback - gatewayId generated locally
        if (str_starts_with($gatewayId, 'sandbox_')) {
            return [
                'id' => $gatewayId,
                'status' => 'pending',
                'gateway' => $this->name(),
                'sandbox' => true,
            ];
        }

        try {
            $response = $this->request('GET', "subscriptions/{$gatewayId}");
        } catch (GuzzleException $e) {
            $this->logException('getSubscription', $e, ['gatewayId' => $gatewayId]);
            throw new \RuntimeException('Falha ao consultar assinatura no Asaas: ' . $e->getMessage(), 0, $e);
        }

        return $response;
    }

    /**
     * Handles incoming Asaas webhook.
     *
     * Security: validates token when PAYMENT_WEBHOOK_SECRET / ASAAS_WEBHOOK_TOKEN is set.
     * Asaas may send token via header `asaas-access-token` or query param `token`.
     *
     * Normalizes Asaas events to internal domain events:
     *   PAYMENT_CONFIRMED / PAYMENT_RECEIVED -> subscription.paid
     *   PAYMENT_REFUNDED / PAYMENT_CHARGEBACK... -> subscription.cancelled (or refunded)
     *   SUBSCRIPTION_DELETED / SUBSCRIPTION_CANCELLED -> subscription.cancelled
     *
     * @return array<string, mixed> ['event' => string, 'data' => array]
     */
    public function handleWebhook(Request $request): array
    {
        $providedToken = $request->header('asaas-access-token')
            ?? $request->header('Asaas-Access-Token')
            ?? $request->header('X-AASAAS-WEBHOOK-TOKEN')
            ?? $request->header('X-FANORA-WEBHOOK-SECRET')
            ?? $request->query('token')
            ?? $request->input('token');

        $expectedToken = $this->webhookToken;

        if (! blank($expectedToken)) {
            if (blank($providedToken) || ! hash_equals((string) $expectedToken, (string) $providedToken)) {
                Log::warning('AsaasGateway.webhook.invalid_token', [
                    'headers' => $request->headers->all(),
                    'ip' => $request->ip(),
                ]);
                abort(403, 'Assinatura de webhook inválida.');
            }
        } else {
            Log::warning('AsaasGateway.webhook.no_token_configured', [
                'hint' => 'Configure PAYMENT_WEBHOOK_SECRET / ASAAS_WEBHOOK_TOKEN em produção.',
                'ip' => $request->ip(),
            ]);
            // In production we still allow but log warning; strict enforcement
            // can be enabled by setting a webhook token.
            if (! $this->isSandbox()) {
                Log::warning('AsaasGateway.webhook.production_without_token', ['payload' => $request->all()]);
            }
        }

        $payload = $request->all();

        Log::info('AsaasGateway.webhook.received', [
            'event' => $payload['event'] ?? $request->input('event'),
            'payload' => $payload,
        ]);

        $asaasEvent = $payload['event'] ?? $request->input('event', 'unknown');
        $normalized = $this->normalizeEvent($asaasEvent);

        // Extract gateway ids
        $payment = $payload['payment'] ?? $payload['data'] ?? $payload;
        $subscriptionGatewayId = $payment['subscription'] ?? $payload['subscription']['id'] ?? $payload['subscription'] ?? null;
        $paymentId = $payment['id'] ?? $payload['payment_id'] ?? $payload['id'] ?? null;

        // Try to resolve local subscription_id from gateway id
        $subscriptionId = $this->resolveLocalSubscriptionId($request, $subscriptionGatewayId, $paymentId, $payment);

        $data = [
            'subscription_id' => $subscriptionId,
            'transaction_id' => $paymentId,
            'gateway_subscription_id' => $subscriptionGatewayId,
            'gateway_payment_id' => $paymentId,
            'raw' => $payload,
            'payment' => $payment,
        ];

        // Also expose top-level for backward compatibility with SubscriptionService::confirmPaid
        // which expects $providerData['transaction_id']
        if ($paymentId) {
            $data['transaction_id'] = $paymentId;
        }

        Log::info('AsaasGateway.webhook.normalized', [
            'asaas_event' => $asaasEvent,
            'normalized' => $normalized,
            'subscription_id' => $subscriptionId,
            'payment_id' => $paymentId,
        ]);

        return [
            'event' => $normalized,
            'data' => $data,
        ];
    }

    // -----------------------------------------------------------------
    // Internal helpers
    // -----------------------------------------------------------------

    protected function resolveBaseUrl(): string
    {
        $configured = config('payment.asaas.base_url');

        if (! blank($configured)) {
            return (string) $configured;
        }

        $sandboxUrl = config('payment.asaas.sandbox_url', 'https://sandbox.asaas.com/api/v3');
        $productionUrl = config('payment.asaas.production_url', 'https://www.asaas.com/api/v3');

        // Also support explicit ASAAS_BASE_URL env override
        $envOverride = env('ASAAS_BASE_URL');
        if (! blank($envOverride)) {
            return (string) $envOverride;
        }

        return $this->isSandbox() ? (string) $sandboxUrl : (string) $productionUrl;
    }

    protected function assertConfigured(): void
    {
        if (blank($this->apiKey)) {
            throw new \RuntimeException('PAYMENT_SECRET_KEY (Asaas API key) não configurado.');
        }
        if (blank($this->baseUrl)) {
            throw new \RuntimeException('Asaas base URL não configurado.');
        }
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     *
     * @throws GuzzleException
     */
    protected function request(string $method, string $uri, array $options = []): array
    {
        $options['headers'] = array_merge([
            'access_token' => $this->apiKey,
            // Asaas also accepts Bearer style; send both for compatibility
            'Authorization' => 'Bearer ' . $this->apiKey,
        ], $options['headers'] ?? []);

        $response = $this->client->request($method, ltrim($uri, '/'), $options);

        $status = $response->getStatusCode();
        $body = (string) $response->getBody();

        if ($body === '' || $status === 204) {
            return ['status' => $status, 'deleted' => true];
        }

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('AsaasGateway.request.non_json_response', [
                'method' => $method,
                'uri' => $uri,
                'status' => $status,
                'body' => Str::limit($body, 1000),
            ]);

            return ['raw' => $body, 'status' => $status];
        }

        return is_array($decoded) ? $decoded : ['data' => $decoded, 'status' => $status];
    }

    protected function ensureCustomerForSubscription(Subscription $subscription): string
    {
        $subscriber = $subscription->user;
        if (! $subscriber) {
            $subscription->loadMissing('user');
            $subscriber = $subscription->user;
        }

        // If we already store asaas customer id somewhere, reuse. For MVP we
        // store externalReference as fanora user id.
        // Try to find existing customer by externalReference to avoid duplicates.
        // We do a lightweight search; if fails we create.
        $externalReference = 'fanora_user_' . $subscriber->id;

        // Attempt to search existing customer via GET /customers?externalReference=
        try {
            $search = $this->request('GET', 'customers', [
                'query' => ['externalReference' => $externalReference],
            ]);

            $existing = $search['data'][0] ?? null;
            if ($existing && isset($existing['id'])) {
                Log::info('AsaasGateway.ensureCustomer.found_existing', [
                    'externalReference' => $externalReference,
                    'customer_id' => $existing['id'],
                ]);

                return (string) $existing['id'];
            }
        } catch (GuzzleException $e) {
            // Search is best-effort; log and proceed to creation
            Log::warning('AsaasGateway.ensureCustomer.search_failed', [
                'error' => $e->getMessage(),
            ]);
        }

        $customerData = [
            'name' => $subscriber->name ?? 'Assinante FANORA ' . $subscriber->id,
            'email' => $subscriber->email,
            'externalReference' => $externalReference,
            // Optional fields if available on model
            'cpfCnpj' => $subscriber->cpf_cnpj ?? $subscriber->cpf ?? null,
            'phone' => $subscriber->phone ?? null,
            'mobilePhone' => $subscriber->mobile_phone ?? $subscriber->phone ?? null,
        ];

        // Remove nulls
        $customerData = array_filter($customerData, fn ($v) => ! is_null($v) && $v !== '');

        $customer = $this->createCustomer($customerData);

        if (! isset($customer['id'])) {
            throw new \RuntimeException('Resposta do Asaas sem id de cliente.');
        }

        return (string) $customer['id'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildSubscriptionPayload(Subscription $subscription, string $customerId): array
    {
        $value = number_format($subscription->value_cents / 100, 2, '.', '');
        $nextDueDate = now()->addDay()->format('Y-m-d');

        // Allow override via config if needed
        $billingType = config('payment.asaas.default_billing_type', 'UNDEFINED');
        $cycle = config('payment.asaas.default_cycle', 'MONTHLY');

        return [
            'customer' => $customerId,
            'billingType' => $billingType,
            'value' => (float) $value,
            'nextDueDate' => $nextDueDate,
            'cycle' => $cycle,
            'description' => "Assinatura FANORA #{$subscription->id} - Creator {$subscription->creator_id}",
            'externalReference' => 'fanora_subscription_' . $subscription->id,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizeCustomerPayload(array $data): array
    {
        // Asaas requires name; ensure it exists
        if (blank($data['name'] ?? null)) {
            $data['name'] = $data['email'] ?? 'Cliente FANORA';
        }

        // Keep only known Asaas fields to avoid 400
        $allowed = [
            'name', 'email', 'cpfCnpj', 'phone', 'mobilePhone',
            'address', 'addressNumber', 'complement', 'province', 'postalCode',
            'externalReference', 'notificationDisabled', 'additionalEmails',
            'municipalInscription', 'stateInscription', 'observations',
        ];

        $filtered = array_intersect_key($data, array_flip($allowed));

        // Ensure at least externalReference is present for traceability
        if (! isset($filtered['externalReference']) && isset($data['externalReference'])) {
            $filtered['externalReference'] = $data['externalReference'];
        }

        return $filtered;
    }

    protected function extractCheckoutUrl(array $response): ?string
    {
        $candidates = [
            $response['invoiceUrl'] ?? null,
            $response['bankSlipUrl'] ?? null,
            $response['transactionUrl'] ?? null,
            $response['checkoutUrl'] ?? null,
            $response['invoice_url'] ?? null,
        ];

        foreach ($candidates as $url) {
            if (! blank($url)) {
                return (string) $url;
            }
        }

        return null;
    }

    protected function normalizeEvent(string $asaasEvent): string
    {
        return match (strtoupper(trim($asaasEvent))) {
            'PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED', 'PAYMENT_RECEIVED_IN_CASH',
            'PAYMENT_CONFIRMED_CASH', 'PAYMENT_CREDIT_CARD_CAPTURED' => 'subscription.paid',

            'PAYMENT_CREATED', 'PAYMENT_AWAITING_PAYMENT', 'PAYMENT_UPDATED' => 'subscription.created',

            'PAYMENT_DELETED', 'PAYMENT_REFUNDED', 'PAYMENT_REFUND_REQUESTED',
            'PAYMENT_CHARGEBACK_REQUESTED', 'PAYMENT_CHARGEBACK_DISPUTE',
            'PAYMENT_OVERDUE', 'PAYMENT_DUNNING_REQUESTED', 'PAYMENT_DUNNING_RECEIVED',
            'SUBSCRIPTION_DELETED', 'SUBSCRIPTION_CANCELLED', 'SUBSCRIPTION_EXPIRED',
            'SUBSCRIPTION_INACTIVATED', 'SUBSCRIPTION_UPDATED' => 'subscription.cancelled',

            // Generic subscription events that imply active payment
            'SUBSCRIPTION_CREATED' => 'subscription.created',
            default => strtolower($asaasEvent),
        };
    }

    protected function resolveLocalSubscriptionId(Request $request, ?string $subscriptionGatewayId, ?string $paymentId, mixed $payment): ?int
    {
        // Direct fanora id passed by our own subscription creation (externalReference)
        $fromRequest = $request->input('data.subscription_id') ?? $request->input('subscription_id');
        if (! blank($fromRequest) && is_numeric($fromRequest)) {
            return (int) $fromRequest;
        }

        // Try externalReference lookup
        $externalRef = $payment['externalReference'] ?? $request->input('externalReference');
        if (! blank($externalRef) && str_starts_with((string) $externalRef, 'fanora_subscription_')) {
            $id = (int) Str::after((string) $externalRef, 'fanora_subscription_');
            if ($id > 0) {
                return $id;
            }
        }

        // Lookup by gateway_transaction_id in subscriptions table
        if (! blank($subscriptionGatewayId)) {
            $sub = Subscription::where('gateway_transaction_id', $subscriptionGatewayId)->first();
            if ($sub) {
                return $sub->id;
            }
        }

        if (! blank($paymentId)) {
            $sub = Subscription::where('gateway_transaction_id', $paymentId)->first();
            if ($sub) {
                return $sub->id;
            }
            // Also check transaction table
            $txn = \App\Models\SubscriptionTransaction::where('gateway_transaction_id', $paymentId)->first();
            if ($txn) {
                return (int) $txn->subscription_id;
            }
        }

        return null;
    }

    protected function logException(string $context, \Throwable $e, array $extra = []): void
    {
        $payload = [
            'context' => $context,
            'error' => $e->getMessage(),
            'extra' => $this->redact($extra),
        ];

        if ($e instanceof ClientException && $e->hasResponse()) {
            $body = (string) $e->getResponse()->getBody();
            $decoded = json_decode($body, true);
            $payload['response_status'] = $e->getResponse()->getStatusCode();
            $payload['response_body'] = $decoded ?? Str::limit($body, 2000);
        }

        Log::error("AsaasGateway.{$context}.exception", $payload);
    }

    protected function extractErrorMessage(GuzzleException $e): string
    {
        if ($e instanceof ClientException && $e->hasResponse()) {
            $body = (string) $e->getResponse()->getBody();
            $decoded = json_decode($body, true);
            if (is_array($decoded)) {
                return $decoded['errors'][0]['description']
                    ?? $decoded['errors'][0]['code']
                    ?? $decoded['message']
                    ?? $decoded['error']
                    ?? $e->getMessage();
            }
        }

        return $e->getMessage();
    }

    /**
     * @return array<string, mixed>
     */
    protected function extractErrorRaw(GuzzleException $e): array
    {
        if ($e instanceof ClientException && $e->hasResponse()) {
            $body = (string) $e->getResponse()->getBody();
            $decoded = json_decode($body, true);
            if (is_array($decoded)) {
                return $decoded;
            }

            return ['raw' => Str::limit($body, 2000), 'status' => $e->getResponse()->getStatusCode()];
        }

        return ['message' => $e->getMessage()];
    }

    /**
     * Redact sensitive fields before logging.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function redact(array $data): array
    {
        $sensitive = ['cpfCnpj', 'cpf', 'phone', 'mobilePhone', 'access_token', 'apiKey'];
        foreach ($sensitive as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***REDACTED***';
            }
        }

        return $data;
    }

    protected function sandboxFallback(Subscription $subscription, string $reason): PaymentGatewayResponse
    {
        $gatewayId = 'sandbox_' . Str::uuid()->toString();

        $subscription->transactions()->create([
            'subscription_id' => $subscription->id,
            'amount_cents' => $subscription->value_cents,
            'status' => TransactionStatus::Pending->value,
            'gateway_transaction_id' => $gatewayId,
            'payload' => ['gateway' => $this->name(), 'sandbox_fallback' => true, 'reason' => $reason],
        ]);

        Log::info('AsaasGateway.sandboxFallback', [
            'subscription_id' => $subscription->id,
            'gateway_id' => $gatewayId,
            'reason' => $reason,
        ]);

        return PaymentGatewayResponse::success(
            gatewayTransactionId: $gatewayId,
            raw: ['gateway' => $this->name(), 'sandbox_fallback' => true, 'message' => $reason],
        );
    }
}
