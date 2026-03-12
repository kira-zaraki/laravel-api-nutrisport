<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrderItem;
use App\Enums\OrderStatus;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'site_id',
        'total',
        'status',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_country'
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeLastDays($query, int $days = 5)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
