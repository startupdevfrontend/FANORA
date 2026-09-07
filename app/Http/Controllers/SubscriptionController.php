<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptions,
    ) {
    }

    public function index(): View
    {
        $subscriptions = auth()->user()
            ->subscriptions()
            ->with(['creator.profile', 'creator.creatorProfile'])
            ->latest()
            ->paginate(12);

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function store(Request $request, string $username): RedirectResponse
    {
        $creator = User::where('username', $username)->firstOrFail();

        abort_unless($creator->isVerifiedCreator(), 422, 'Este creator ainda não está habilitado a receber assinaturas.');

        $this->authorize('create', [Subscription::class, $creator]);

        $this->subscriptions->subscribe(auth()->user(), $creator);

        session()->flash('status', 'Solicitação de assinatura registrada. Aguardando confirmação do pagamento.');

        return redirect()->route('subscriptions.index');
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $this->authorize('cancel', $subscription);

        $this->subscriptions->cancel($subscription, auth()->user());

        return back()->with('status', 'Assinatura cancelada com sucesso.');
    }
}