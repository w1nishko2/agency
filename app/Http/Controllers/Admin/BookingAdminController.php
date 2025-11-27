<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

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

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
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
        $booking = Booking::with(['model.user'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Одобрить бронирование
     */
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Бронирование одобрено!');
    }

    /**
     * Отклонить бронирование
     */
    public function reject(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'status' => 'cancelled',
            'rejection_reason' => $request->input('reason', 'Не указана')
        ]);

        return back()->with('success', 'Бронирование отклонено.');
    }

    /**
     * Завершить бронирование
     */
    public function complete($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'completed']);

        return back()->with('success', 'Бронирование отмечено как выполненное.');
    }

    /**
     * Удалить бронирование
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Бронирование удалено.');
    }
}
