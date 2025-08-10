<?php

namespace App\Providers;

use App\Models\PointsTransaction;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
    public function boot(): void
    {
        RouteFacade::bind('transaction', function (string $value): PointsTransaction {
            return PointsTransaction::where('uuid', $value)->firstOrFail();
        });
    }
}
