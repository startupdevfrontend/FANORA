<?php

namespace App\Services;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportStatusChangedNotification;

class ReportService
{
    public function __construct(protected AuditService $audit)
    {
    }

    /**
     * Creates a new moderation report for any reportable model
     * (post, user / creator profile).
     */
    public function create(User $reporter, object $reportable, string $reason, ?string $description = null): Report
    {
        abort_if($reporter->id === $reportable->getKey(), 422, 'Você não pode denunciar o seu próprio conteúdo.');

        $report = Report::create([
            'reporter_id' => $reporter->id,
            'reportable_type' => get_class($reportable),
            'reportable_id' => $reportable->getKey(),
            'reason' => $reason,
            'description' => $description,
            'status' => ReportStatus::Pending->value,
        ]);

        $this->audit->log($reporter, 'report.created', $report, null, [
            'reason' => $reason,
            'reportable' => [$report->reportable_type, $report->reportable_id],
        ]);

        return $report;
    }

    public function review(Report $report, User $admin, string $status, ?string $note = null): Report
    {
        $old = $report->only(['status']);

        $report->update([
            'status' => $status,
            'moderator_note' => $note,
            'resolved_by' => $admin->id,
            'resolved_at' => in_array($status, [ReportStatus::Resolved->value, ReportStatus::Rejected->value]) ? now() : $report->resolved_at,
        ]);

        if (in_array($status, [ReportStatus::Resolved->value, ReportStatus::Rejected->value])) {
            $report->reporter?->notify(new ReportStatusChangedNotification($report));
        }

        $this->audit->log($admin, 'report.updated', $report, $old, $report->only(['status', 'moderator_note']));

        return $report->fresh();
    }
}