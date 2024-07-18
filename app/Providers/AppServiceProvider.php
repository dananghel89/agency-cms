<?php

namespace App\Providers;

use App\Models\Options;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Project') . '/helpers.php';
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Paginator::useBootstrapFour();

        $GLOBALS['options'] = parse_args(
            Options::all()->pluck('value', 'name')->toArray(),
            config('sb.options')
        );
    }
}
