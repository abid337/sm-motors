<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\OpenRouterProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(AIProviderInterface::class, function ($app) {
            return new OpenRouterProvider();
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
