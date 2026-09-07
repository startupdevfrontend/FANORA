<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ReportStatus;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Services\ReportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reports)
    {
    }

    public function index(Request $request): View
    {
        $reports = Report::query()
            ->with(['reporter', 'reportable'])
            ->when($request->query('status') && in_array($request->query('status'), ['pending', 'reviewing', 'resolved', 'rejected'], true), fn ($q) => $q->where('status', $request->query('status')))
            ->latest()
            ->paginate(20);

        $counts = [
            'pending' => Report::where('status', ReportStatus::Pending->value)->count(),
            'reviewing' => Report::where('status', ReportStatus::Reviewing->value)->count(),
            'resolved' => Report::where('status', ReportStatus::Resolved->value)->count(),
            'rejected' => Report::where('status', ReportStatus::Rejected->value)->count(),
        ];

        return view('admin.reports.index', compact('reports', 'counts'));
    }

    public function show(Report $report): View
    {
        $report->load(['reporter', 'reportable']);

        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('review', $report);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,reviewing,resolved,rejected'],
            'moderator_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->reports->review(
            $report,
            auth()->user(),
            $validated['status'],
            $validated['moderator_note'] ?? null,
        );

        return redirect()->route('admin.reports.index')->with('status', 'Denúncia atualizada.');
    }
}