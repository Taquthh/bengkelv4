<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Jika dijalankan di lokal, gunakan http agar tidak error SSL
        if (config('app.env') === 'local') {
            URL::forceScheme('http');
        }

        // 🔹 Set bahasa Carbon ke Indonesia secara global
        Carbon::setLocale(config('app.locale', 'id'));

        // 🔹 Set locale sistem untuk tanggal
        setlocale(LC_TIME, 'id_ID.UTF-8');
    }
}
