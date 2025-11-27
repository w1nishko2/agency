<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use Illuminate\Http\Request;

class ModelAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Список всех моделей для модерации
     */
    public function index(Request $request)
    {
        $query = ModelProfile::with('user');

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

        $models = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.models.index', compact('models'));
    }

    /**
     * Просмотр конкретной модели
     */
    public function show($id)
    {
        $model = ModelProfile::with('user')->findOrFail($id);
        return view('admin.models.show', compact('model'));
    }

    /**
     * Одобрить модель
     */
    public function approve($id)
    {
        $model = ModelProfile::findOrFail($id);
        $model->update(['status' => 'active']);

        return back()->with('success', 'Модель одобрена и опубликована!');
    }

    /**
     * Отклонить модель
     */
    public function reject(Request $request, $id)
    {
        $model = ModelProfile::findOrFail($id);
        $model->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('reason', 'Не указана')
        ]);

        return back()->with('success', 'Модель отклонена.');
    }

    /**
     * Деактивировать модель
     */
    public function deactivate($id)
    {
        $model = ModelProfile::findOrFail($id);
        $model->update(['status' => 'inactive']);

        return back()->with('success', 'Модель деактивирована.');
    }

    /**
     * Удалить модель
     */
    public function destroy($id)
    {
        $model = ModelProfile::findOrFail($id);
        
        // Удаляем фотографии
        if ($model->main_photo) {
            \Storage::disk('public')->delete($model->main_photo);
        }
        if ($model->portfolio_photos) {
            foreach ($model->portfolio_photos as $photo) {
                \Storage::disk('public')->delete($photo);
            }
        }

        $model->delete();

        return redirect()->route('admin.models.index')->with('success', 'Модель удалена.');
    }

    /**
     * Редактирование модели
     */
    public function edit($id)
    {
        $model = ModelProfile::with('user')->findOrFail($id);
        return view('admin.models.edit', compact('model'));
    }

    /**
     * Обновление модели
     */
    public function update(Request $request, $id)
    {
        $model = ModelProfile::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:16|max:100',
            'city' => 'required|string',
            'height' => 'required|integer',
            'weight' => 'nullable|integer',
            'bust' => 'nullable|integer',
            'waist' => 'nullable|integer',
            'hips' => 'nullable|integer',
            'eye_color' => 'required|string',
            'hair_color' => 'required|string',
            'shoe_size' => 'nullable|numeric',
            'bio' => 'nullable|string',
            'experience_description' => 'nullable|string',
            'status' => 'required|in:pending,active,inactive,rejected',
            'is_featured' => 'boolean',
        ]);

        $model->update($validated);

        return back()->with('success', 'Профиль модели обновлен!');
    }
}
