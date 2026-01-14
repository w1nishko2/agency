<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use App\Models\CastingApplication;
use App\Models\Booking;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Получаем статистику с кешированием (5 минут)
        $stats = [
            'pending_models' => Cache::remember('dashboard.pending_models', 300, function () {
                return ModelProfile::where('status', 'pending')->count();
            }),
            'active_models' => Cache::remember('dashboard.active_models', 300, function () {
                return ModelProfile::where('status', 'active')->count();
            }),
            'total_models' => Cache::remember('dashboard.total_models', 300, function () {
                return ModelProfile::count();
            }),
            'bookings_count' => Cache::remember('dashboard.bookings_count', 300, function () {
                return Booking::count();
            }),
            'recent_models' => ModelProfile::latest()->limit(5)->get(),
            'recent_castings' => CastingApplication::latest()->limit(10)->get(),
            'recent_bookings' => Booking::with('model')->latest()->limit(10)->get(),
            'new_castings_count' => Cache::remember('dashboard.new_castings', 300, function () {
                return CastingApplication::where('status', 'new')->count();
            }),
            'pending_bookings_count' => Cache::remember('dashboard.pending_bookings', 300, function () {
                return Booking::where('status', 'pending')->count();
            }),
        ];

        return view('admin.dashboard', $stats);
    }
}
