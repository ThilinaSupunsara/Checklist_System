<?php

namespace App\Providers;

use App\Models\Property;
use App\Policies\PropertyPolicy;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
{
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }
}
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

    protected $policies = [
    // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    Property::class => PropertyPolicy::class, // Add this line
];
}
