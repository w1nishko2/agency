<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use Illuminate\Http\Request;

class CastingAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Список заявок на кастинг
     */
    public function index(Request $request)
    {
        $query = CastingApplication::query();

        // Фильтр по статусу
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.castings.index', compact('applications'));
    }

    /**
     * Просмотр заявки
     */
    public function show($id)
    {
        $application = CastingApplication::findOrFail($id);
        return view('admin.castings.show', compact('application'));
    }

    /**
     * Одобрить заявку
     */
    public function approve($id)
    {
        $application = CastingApplication::findOrFail($id);
        $application->approve();

        return back()->with('success', 'Заявка одобрена!');
    }

    /**
     * Отклонить заявку
     */
    public function reject(Request $request, $id)
    {
        $application = CastingApplication::findOrFail($id);
        $application->reject($request->input('reason'));

        return back()->with('success', 'Заявка отклонена.');
    }

    /**
     * Удалить заявку
     */
    public function destroy($id)
    {
        $application = CastingApplication::findOrFail($id);
        
        // Удаляем фото
        $photoFields = ['photo_portrait', 'photo_full_body', 'photo_profile', 'photo_additional_1', 'photo_additional_2'];
        foreach ($photoFields as $field) {
            if ($application->$field) {
                \Storage::disk('public')->delete($application->$field);
            }
        }

        $application->delete();

        return redirect()->route('admin.castings.index')->with('success', 'Заявка удалена.');
    }
}
