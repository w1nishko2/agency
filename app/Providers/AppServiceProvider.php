<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\ModelProfile;
use App\Models\CastingApplication;
use App\Models\Booking;

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
    }
}
