<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ProductPrice;

class Product extends Model
{
    protected $fillable = ['name','stock'];

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

    public function resolveRouteBinding($value, $field = null)
    {
        $site = request()->route('site');
        
        if(!$site)
            return parent::resolveRouteBinding($value, $field);

        return static::bySite($site->id)
            ->where($field ?? $this->getRouteKeyName(), $value)
            ->firstOrFail();
    }
}
