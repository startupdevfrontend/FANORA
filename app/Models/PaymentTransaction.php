<?php

namespace App\Models;

use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'creator_id',
        'subscription_id',
        'gross_amount_cents',
        'commission_rate',
        'commission_cents',
        'gateway_fee_cents',
        'creator_amount_cents',
        'status',
        'provider',
        'provider_transaction_id',
        'metadata',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount_cents' => 'integer',
            'commission_rate' => 'integer',
            'commission_cents' => 'integer',
            'gateway_fee_cents' => 'integer',
            'creator_amount_cents' => 'integer',
            'status' => TransactionStatus::class,
            'metadata' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}