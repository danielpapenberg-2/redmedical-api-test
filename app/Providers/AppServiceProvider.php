<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProviderPortalClient;
use App\Services\RedProviderPortalMockClient;
use App\Services\RedProviderPortalHttpClient;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProviderPortalClient::class, function () {
            if (config('services.red_portal.enabled')) {
                return app(RedProviderPortalHttpClient::class);
            }

            return app(RedProviderPortalMockClient::class);
        });
    }

    public function boot(): void
    {
        //
    }
}
