<?php

namespace App\Providers;

use App\Models\Translation;
use App\Services\AuthService;
use App\Contracts\AuthContract;
use App\Services\TranslationService;
use App\Contracts\TranslationContract;
use App\Observers\TranslationObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AuthContract::class,
            function ($app) {
                return $app->make(AuthService::class);
            }
        );

        $this->app->bind(
            TranslationContract::class,
            function ($app) {
                return $app->make(TranslationService::class);
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // if (file_exists(base_path('.env.local')) && !app()->runningInConsole() && !app()->environment('docker')) {
        //     $dotenv = \Dotenv\Dotenv::createImmutable(base_path(), '.env.local');
        //     $dotenv->load();
        // }
        Translation::observe(TranslationObserver::class);
    }
}
