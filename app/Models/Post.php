<?php

namespace App\Models;

use App\Enums\PostVisibility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'body',
        'visibility',
        'is_premium_paid',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'visibility' => PostVisibility::class,
            'is_premium_paid' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(PostMedia::class)->orderBy('sort_order');
    }

    public function isExclusive(): bool
    {
        return $this->visibility === PostVisibility::SubscribersOnly;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', PostVisibility::Public->value);
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }
}