<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator; // Impor Paginator
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
        // Menyesuaikan pagination untuk menggunakan Tailwind CSS
        Paginator::useTailwind();
    }
}
