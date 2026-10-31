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
        $this->app->singleton('sb.options', fn () => parse_args(
            Options::query()->pluck('value', 'name')->all(),
            config('sb.options')
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Paginator::useBootstrapFour();
    }
}
