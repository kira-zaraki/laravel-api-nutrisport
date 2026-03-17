<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Factories\NutritionToolsFactory;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('nutrition.tools', function ($app, $params) {
            return $app->make(NutritionToolsFactory::class)
                ->make(
                    $params['site_id'],
                    $params['cart_id']
                );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
