<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        // Поиск с экранированием спецсимволов LIKE
        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
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
        // Валидация ID
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::with('user')->findOrFail($validated['id']);
        return view('admin.models.show', compact('model'));
    }

    /**
     * Одобрить модель
     */
    public function approve($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::findOrFail($validated['id']);
        $model->update(['status' => 'active']);

        Log::info('Model approved', [
            'model_id' => $model->id,
            'model_name' => $model->full_name,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Модель одобрена и опубликована!');
    }

    /**
     * Отклонить модель
     */
    public function reject(Request $request, $id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::findOrFail($validated['id']);
        
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        
        $reason = strip_tags($request->input('reason'));
        $model->update([
            'status' => 'rejected',
            'rejection_reason' => $reason
        ]);

        Log::info('Model rejected', [
            'model_id' => $model->id,
            'model_name' => $model->full_name,
            'reason' => $reason,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Модель отклонена.');
    }

    /**
     * Деактивировать модель
     */
    public function deactivate($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::findOrFail($validated['id']);
        $model->update(['status' => 'inactive']);

        Log::info('Model deactivated', [
            'model_id' => $model->id,
            'model_name' => $model->full_name,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Модель деактивирована.');
    }

    /**
     * Удалить модель
     */
    public function destroy($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::findOrFail($validated['id']);
        
        $modelData = [
            'id' => $model->id,
            'name' => $model->full_name,
            'email' => $model->email
        ];

        // Используем транзакцию для безопасного удаления
        DB::transaction(function() use ($model) {
            // Удаляем фотографии с проверкой существования
            if ($model->main_photo && Storage::disk('public')->exists($model->main_photo)) {
                Storage::disk('public')->delete($model->main_photo);
            }
            
            if ($model->portfolio_photos && is_array($model->portfolio_photos)) {
                foreach ($model->portfolio_photos as $photo) {
                    if (Storage::disk('public')->exists($photo)) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }

            $model->delete();
        });

        Log::warning('Model deleted', array_merge($modelData, [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]));

        return redirect()->route('admin.models.index')->with('success', 'Модель удалена.');
    }

    /**
     * Редактирование модели
     */
    public function edit($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::with('user')->findOrFail($validated['id']);
        return view('admin.models.edit', compact('model'));
    }

    /**
     * Обновление модели
     */
    public function update(Request $request, $id)
    {
        $validated_id = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::findOrFail($validated_id['id']);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:models,email,' . $validated_id['id'],
            'phone' => 'required|string|max:20|unique:models,phone,' . $validated_id['id'],
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:16|max:100',
            'city' => 'required|string|max:255',
            'height' => 'required|integer|min:150|max:220',
            'weight' => 'nullable|integer|min:40|max:150',
            'bust' => 'nullable|integer|min:50|max:150',
            'waist' => 'nullable|integer|min:40|max:120',
            'hips' => 'nullable|integer|min:50|max:150',
            'eye_color' => 'required|string|max:50',
            'hair_color' => 'required|string|max:50',
            'shoe_size' => 'nullable|numeric|min:30|max:50',
            'clothing_size' => 'nullable|string|max:10',
            'bio' => 'nullable|string|max:2000',
            'telegram' => 'nullable|string|max:100',
            'vk' => 'nullable|string|max:100',
            'experience_description' => 'nullable|string|max:2000',
            'status' => 'required|in:pending,active,inactive,rejected',
            'is_featured' => 'boolean',
        ]);
        
        // Санитизация текстовых полей
        if (!empty($validated['bio'])) {
            $validated['bio'] = strip_tags($validated['bio']);
        }
        if (!empty($validated['experience_description'])) {
            $validated['experience_description'] = strip_tags($validated['experience_description']);
        }

        DB::transaction(function() use ($model, $validated) {
            $model->update($validated);
        });

        Log::info('Model updated', [
            'model_id' => $model->id,
            'model_name' => $model->full_name,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Профиль модели обновлен!');
    }
}
