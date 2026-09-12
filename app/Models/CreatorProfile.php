<?php

namespace App\Models;

use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CreatorProfile extends Model
{
    /** @use HasFactory<\Database\Factories\CreatorProfileFactory> */
    use HasFactory;

    // SECURITY: verification_status, rejection_reason, is_featured, subscriber_count
    // are privileged fields - never mass-assignable from user input.
    protected $fillable = [
        'user_id',
        'display_name',
        'tagline',
        'subscription_price_cents',
        'instagram',
        'tiktok',
        'twitter',
        'youtube',
    ];

    protected function casts(): array
    {
        return [
            'subscription_price_cents' => 'integer',
            'is_featured' => 'boolean',
            'subscriber_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_creator');
    }

    public function isApproved(): bool
    {
        return $this->verification_status === VerificationStatus::Approved->value;
    }

    public function isPending(): bool
    {
        return $this->verification_status === VerificationStatus::Pending->value;
    }

    public function priceCents(): ?int
    {
        return $this->subscription_price_cents;
    }

    public function priceReais(): ?string
    {
        return $this->subscription_price_cents !== null
            ? number_format($this->subscription_price_cents / 100, 2, ',', '.')
            : null;
    }
}