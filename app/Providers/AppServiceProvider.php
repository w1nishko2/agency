<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\Paginator;
use Laravel\Socialite\Facades\Socialite;
use App\Models\ModelProfile;
use App\Models\CastingApplication;
use App\Models\Booking;
use App\Models\SiteSetting;
use App\Services\VkIdProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::useBootstrapFive();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Использовать Bootstrap 5 для пагинации
        Paginator::useBootstrapFive();
        
        // Регистрация кастомного провайдера для VK ID
        Socialite::extend('vkid', function ($app) {
            $config = $app['config']['services.vkid'];
            return Socialite::buildProvider(VkIdProvider::class, $config);
        });
        
        // View Composer для админ-сайдбара (кеширование счетчиков на 5 минут)
        View::composer('layouts.admin', function ($view) {
            $view->with('sidebar_stats', [
                'pending_models_count' => Cache::remember('sidebar.pending_models', 300, fn() => 
                    ModelProfile::where('status', 'pending')->count()
                ),
                'new_castings_count' => Cache::remember('sidebar.new_castings', 300, fn() =>
                    CastingApplication::where('status', 'new')->count()
                ),
                'pending_bookings_count' => Cache::remember('sidebar.pending_bookings', 300, fn() =>
                    Booking::where('status', 'pending')->count()
                ),
            ]);
        });
        
        // View Composer для настроек сайта (доступны во всех представлениях)
        View::composer('*', function ($view) {
            $view->with('site_settings', [
                'contact' => SiteSetting::getGroup('contact'),
                'social' => SiteSetting::getGroup('social'),
                'general' => SiteSetting::getGroup('general'),
            ]);
        });
    }
}
