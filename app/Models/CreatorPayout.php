<?php

namespace App\Models;

use App\Enums\PayoutStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreatorPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'period_started_on',
        'period_ended_on',
        'gross_amount_cents',
        'fees_cents',
        'net_amount_cents',
        'status',
        'requested_at',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'period_started_on' => 'date',
            'period_ended_on' => 'date',
            'gross_amount_cents' => 'integer',
            'fees_cents' => 'integer',
            'net_amount_cents' => 'integer',
            'status' => PayoutStatus::class,
            'requested_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}