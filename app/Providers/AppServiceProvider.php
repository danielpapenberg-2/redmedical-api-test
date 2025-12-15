<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProviderPortalClient;
use App\Services\RedProviderPortalMockClient;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProviderPortalClient::class, function () {
            return app(RedProviderPortalMockClient::class);
        });
    }

    public function boot(): void
    {
        //
    }
}
