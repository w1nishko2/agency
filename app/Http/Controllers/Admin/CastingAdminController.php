<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

        $applications = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.castings.index', compact('applications'));
    }

    /**
     * Просмотр заявки
     */
    public function show($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $application = CastingApplication::findOrFail($validated['id']);
        return view('admin.castings.show', compact('application'));
    }

    /**
     * Одобрить заявку
     */
    public function approve($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $application = CastingApplication::findOrFail($validated['id']);
        $application->approve();

        Log::info('Casting application approved', [
            'application_id' => $application->id,
            'applicant_name' => $application->full_name,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Заявка одобрена!');
    }

    /**
     * Отклонить заявку
     */
    public function reject(Request $request, $id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $application = CastingApplication::findOrFail($validated['id']);
        
        $reason = $request->input('reason');
        $application->reject($reason);

        Log::info('Casting application rejected', [
            'application_id' => $application->id,
            'applicant_name' => $application->full_name,
            'reason' => $reason,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);

        return back()->with('success', 'Заявка отклонена.');
    }

    /**
     * Удалить заявку
     */
    public function destroy($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $application = CastingApplication::findOrFail($validated['id']);
        
        $applicationData = [
            'id' => $application->id,
            'name' => $application->full_name,
            'email' => $application->email
        ];

        // Используем транзакцию для безопасного удаления
        DB::transaction(function() use ($application) {
            // Удаляем фото с проверкой существования
            $photoFields = ['photo_portrait', 'photo_full_body', 'photo_profile', 'photo_additional_1', 'photo_additional_2'];
            foreach ($photoFields as $field) {
                if ($application->$field && Storage::disk('public')->exists($application->$field)) {
                    Storage::disk('public')->delete($application->$field);
                }
            }

            $application->delete();
        });

        Log::warning('Casting application deleted', array_merge($applicationData, [
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]));

        return redirect()->route('admin.castings.index')->with('success', 'Заявка удалена.');
    }

    /**
     * Подбор моделей по критериям из заявки
     */
    public function findModels($id)
    {
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $application = CastingApplication::findOrFail($validated['id']);
        
        // ОПТИМИЗАЦИЯ: Применяем фильтры прямо в SQL запросе
        $query = \App\Models\ModelProfile::where('status', 'active');
        
        // Обязательные критерии применяем в SQL
        if ($application->gender && $application->gender !== 'any') {
            $query->where('gender', $application->gender);
        }
        
        // Возраст ±10 лет (более широкий диапазон для первичной фильтрации)
        if ($application->age && $application->age > 0) {
            $query->whereBetween('age', [
                max(16, $application->age - 10), 
                $application->age + 10
            ]);
        }
        
        // Рост ±15 см
        if ($application->height && $application->height > 0) {
            $query->whereBetween('height', [
                $application->height - 15, 
                $application->height + 15
            ]);
        }
        
        // Вес ±15 кг
        if ($application->weight && $application->weight > 0) {
            $query->whereBetween('weight', [
                max(40, $application->weight - 15), 
                $application->weight + 15
            ]);
        }
        
        // Точные совпадения
        if ($application->eye_color && $application->eye_color !== '-') {
            $query->where('eye_color', $application->eye_color);
        }
        
        if ($application->hair_color && $application->hair_color !== '-') {
            $query->where('hair_color', $application->hair_color);
        }
        
        // Получаем отфильтрованные модели с пагинацией
        $models = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Рассчитываем процент совпадения только для загруженных моделей
        $models->getCollection()->transform(function($model) use ($application) {
            $totalCriteria = 0;
            $matchedCriteria = 0;
            
            // Пол
            if ($application->gender && $application->gender !== 'any') {
                $totalCriteria++;
                if ($model->gender === $application->gender) {
                    $matchedCriteria++;
                }
            }
            
            // Возраст (±5 лет = 100%, ±10 лет = 50%)
            if ($application->age && $application->age > 0) {
                $totalCriteria++;
                $ageDiff = abs($model->age - $application->age);
                if ($ageDiff <= 5) {
                    $matchedCriteria += 1;
                } elseif ($ageDiff <= 10) {
                    $matchedCriteria += 0.5;
                }
            }
            
            // Рост (±7 см = 100%, ±15 см = 50%)
            if ($application->height && $application->height > 0) {
                $totalCriteria++;
                $heightDiff = abs($model->height - $application->height);
                if ($heightDiff <= 7) {
                    $matchedCriteria += 1;
                } elseif ($heightDiff <= 15) {
                    $matchedCriteria += 0.5;
                }
            }
            
            // Вес (±7 кг = 100%, ±15 кг = 50%)
            if ($application->weight && $application->weight > 0 && $model->weight) {
                $totalCriteria++;
                $weightDiff = abs($model->weight - $application->weight);
                if ($weightDiff <= 7) {
                    $matchedCriteria += 1;
                } elseif ($weightDiff <= 15) {
                    $matchedCriteria += 0.5;
                }
            }
            
            // Размер одежды
            if ($application->clothing_size && $application->clothing_size !== '-') {
                $totalCriteria++;
                if ($model->clothing_size === $application->clothing_size) {
                    $matchedCriteria++;
                }
            }
            
            // Цвет глаз
            if ($application->eye_color && $application->eye_color !== '-') {
                $totalCriteria++;
                if ($model->eye_color === $application->eye_color) {
                    $matchedCriteria++;
                }
            }
            
            // Цвет волос
            if ($application->hair_color && $application->hair_color !== '-') {
                $totalCriteria++;
                if ($model->hair_color === $application->hair_color) {
                    $matchedCriteria++;
                }
            }
            
            // Опыт работы
            if ($application->has_experience) {
                $totalCriteria++;
                if ($model->experience_years > 0) {
                    $matchedCriteria++;
                }
            }
            
            // Рассчитываем процент
            $model->match_percent = $totalCriteria > 0 ? round(($matchedCriteria / $totalCriteria) * 100) : 0;
            return $model;
        });
        
        return view('admin.castings.find-models', compact('application', 'models'));
    }

    /**
     * Записать выбранных моделей на кастинг
     */
    public function assignModels(Request $request, $id)
    {
        $validated_id = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $application = CastingApplication::findOrFail($validated_id['id']);
        
        $request->validate([
            'model_ids' => 'required|array|min:1|max:50',
            'model_ids.*' => 'integer|exists:models,id'
        ]);
        
        $modelIds = $request->model_ids;
        $models = \App\Models\ModelProfile::whereIn('id', $modelIds)
            ->where('status', 'active')
            ->get();
        
        if ($models->count() !== count($modelIds)) {
            return back()->withErrors(['model_ids' => 'Некоторые модели не найдены или неактивны']);
        }
        
        // Сохраняем информацию о выбранных моделях в заявке
        $selectedModels = $models->map(function($model) {
            return [
                'id' => $model->id,
                'name' => e($model->full_name), // XSS защита
                'age' => $model->age,
                'height' => $model->height,
                'selected_at' => now()->toDateTimeString()
            ];
        })->toArray();
        
        $application->selected_models = json_encode($selectedModels);
        $application->save();
        
        Log::info('Models assigned to casting', [
            'application_id' => $application->id,
            'models_count' => count($modelIds),
            'model_ids' => $modelIds,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);
        
        return redirect()
            ->route('admin.castings.show', $id)
            ->with('success', 'Выбрано ' . count($modelIds) . ' ' . 
                   (count($modelIds) === 1 ? 'модель' : (count($modelIds) < 5 ? 'модели' : 'моделей')) . 
                   ' для кастинга');
    }

    /**
     * Удалить модель из выбранных для кастинга
     */
    public function removeModel($castingId, $modelId)
    {
        $validated = validator([
            'casting_id' => $castingId,
            'model_id' => $modelId
        ], [
            'casting_id' => 'required|integer|min:1',
            'model_id' => 'required|integer|min:1'
        ])->validate();
        
        $application = CastingApplication::findOrFail($validated['casting_id']);
        
        if (!$application->selected_models) {
            return back()->with('error', 'Нет выбранных моделей');
        }
        
        $selectedModels = json_decode($application->selected_models, true);
        
        // Проверка валидности JSON
        if (!is_array($selectedModels)) {
            return back()->with('error', 'Ошибка данных выбранных моделей');
        }
        
        // Фильтруем массив, удаляя модель с нужным ID
        $filteredModels = array_filter($selectedModels, function($model) use ($validated) {
            return $model['id'] != $validated['model_id'];
        });
        
        // Переиндексируем массив
        $filteredModels = array_values($filteredModels);
        
        // Если моделей не осталось, обнуляем поле
        $application->selected_models = count($filteredModels) > 0 ? json_encode($filteredModels) : null;
        $application->save();
        
        Log::info('Model removed from casting', [
            'application_id' => $application->id,
            'model_id' => $validated['model_id'],
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);
        
        return back()->with('success', 'Модель удалена из кастинга');
    }
}
