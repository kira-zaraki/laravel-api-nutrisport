<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $appends = ['rest_to_pay'];

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

    protected function restToPay(): Attribute
    {
        return Attribute::make(
            get: fn () => max(0, $this->total - ($this->paid ?? 0)),
        );
    }
}
