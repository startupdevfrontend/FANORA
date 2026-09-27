<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptions)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return SubscriptionResource::collection(
            $request->user()->subscriptions()->with('creator')->latest()->paginate(15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $creator = \App\Models\User::where('username', $request->string('username'))->firstOrFail();

        abort_unless($creator->isVerifiedCreator(), 422, 'Creator não verificado.');

        abort_if($creator->id === $request->user()->id, 422, 'Você não pode assinar a si mesmo.');

        $subscription = $this->subscriptions->subscribe($request->user(), $creator);

        return response()->json([
            'message' => 'Assinatura iniciada. Aguardando confirmação do gateway.',
            'subscription' => new SubscriptionResource($subscription),
        ], 201);
    }

    public function destroy(Request $request, Subscription $subscription): JsonResponse
    {
        abort_unless($subscription->user_id === $request->user()->id, 403);

        $this->subscriptions->cancel($subscription, $request->user());

        return response()->json(['message' => 'Assinatura cancelada.']);
    }
}