<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportStoreRequest;
use App\Models\CreatorProfile;
use App\Models\Post;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reports)
    {
    }

    public function store(ReportStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', \App\Models\Report::class);

        $type = $request->input('reportable_type');
        $id = (int) $request->input('reportable_id');

        $reportable = match ($type) {
            'post' => Post::findOrFail($id),
            'user' => User::findOrFail($id),
            'creator' => CreatorProfile::findOrFail($id),
            default => abort(404),
        };

        $this->reports->create(
            $request->user(),
            $reportable,
            $request->string('reason'),
            $request->input('description'),
        );

        return back()->with('status', 'Denúncia enviada com sucesso. Obrigado por ajudar a manter a plataforma segura.');
    }
}