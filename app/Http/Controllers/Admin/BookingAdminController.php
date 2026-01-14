<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Список всех бронирований
     */
    public function index(Request $request)
    {
        $query = Booking::with(['model.user']);

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Поиск с экранированием спецсимволов LIKE
        if ($request->filled('search')) {
            $request->validate([
                'search' => 'string|max:100'
            ]);
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where(function($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Просмотр конкретного бронирования
     */
    public function show($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $booking = Booking::with(['model.user'])->findOrFail($validated['id']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Одобрить бронирование
     */
    public function approve($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $booking = Booking::findOrFail($validated['id']);
        
        DB::transaction(function() use ($booking) {
            $booking->update(['status' => 'confirmed']);
        });

        Log::info('Booking approved', [
            'booking_id' => $booking->id,
            'client_name' => $booking->client_name,
            'model_id' => $booking->model_id,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Бронирование одобрено!');
    }

    /**
     * Отклонить бронирование
     */
    public function reject(Request $request, $id)
    {
        $validated_id = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $booking = Booking::findOrFail($validated_id['id']);
        
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $reason = strip_tags($request->input('reason'));
        
        DB::transaction(function() use ($booking, $reason) {
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason
            ]);
        });

        Log::info('Booking rejected', [
            'booking_id' => $booking->id,
            'client_name' => $booking->client_name,
            'reason' => $reason,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Бронирование отклонено.');
    }

    /**
     * Завершить бронирование
     */
    public function complete($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $booking = Booking::findOrFail($validated['id']);
        
        DB::transaction(function() use ($booking) {
            $booking->update(['status' => 'completed']);
            // Увеличиваем счетчик успешных бронирований модели
            if ($booking->model) {
                $booking->model->increment('bookings_count');
            }
        });

        Log::info('Booking completed', [
            'booking_id' => $booking->id,
            'client_name' => $booking->client_name,
            'model_id' => $booking->model_id,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Бронирование отмечено как выполненное.');
    }

    /**
     * Удалить бронирование
     */
    public function destroy($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $booking = Booking::findOrFail($validated['id']);
        
        $bookingData = [
            'id' => $booking->id,
            'client_name' => $booking->client_name,
            'model_id' => $booking->model_id,
            'status' => $booking->status
        ];
        
        DB::transaction(function() use ($booking) {
            $booking->delete();
        });

        Log::warning('Booking deleted', array_merge($bookingData, [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]));

        return redirect()->route('admin.bookings.index')->with('success', 'Бронирование удалено.');
    }
}
