<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\ProductPrice;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;

#[UseResource(ProductResource::class)]
class Product extends Model
{
    protected $fillable = ['name','stock'];
    protected $appends = ['in_stock'];

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function scopeBySite($query, $site)
    {
        return $query
            ->whereHas('prices', function ($q) use ($site) {
                $q->where('site_id', $site);
            })
            ->with([
                'prices' => fn ($q) => $q->where('site_id', $site)
            ]);
    }

    public function scopeSearch($query, $keyword)
    {
        if (!$keyword) {
            return $query;
        }

        return $query->where('name', 'like', "%{$keyword}%");
    }

    public function scopeAvailable($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $site = request()->route('site');
        
        if(!$site)
            return parent::resolveRouteBinding($value, $field);

        return static::bySite($site->id)
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->firstOrFail();
    }

    protected function inStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stock > 0,
        );
    }
}
