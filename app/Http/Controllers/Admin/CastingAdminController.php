<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use App\Models\TelegramBotSettings;
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
        
        // Логируем критерии поиска для отладки
        \Log::info('Finding models for casting', [
            'casting_id' => $application->id,
            'gender' => $application->gender,
            'age' => $application->age,
            'height' => $application->height,
            'weight' => $application->weight,
            'eye_color' => $application->eye_color,
            'hair_color' => $application->hair_color,
        ]);
        
        // Сначала пробуем точный поиск
        $query = \App\Models\ModelProfile::where('status', 'active');
        $exactQuery = clone $query;
        $hasStrictCriteria = false;
        
        // Обязательный критерий - пол
        if ($application->gender && $application->gender !== 'any') {
            $exactQuery->where('gender', $application->gender);
            $query->where('gender', $application->gender);
        }
        
        // Возраст ±10 лет (строгий поиск)
        if ($application->age && $application->age > 0) {
            $hasStrictCriteria = true;
            $exactQuery->whereBetween('age', [
                max(16, $application->age - 10), 
                $application->age + 10
            ]);
        }
        
        // Рост ±15 см (строгий поиск)
        if ($application->height && $application->height > 0) {
            $hasStrictCriteria = true;
            $exactQuery->whereBetween('height', [
                $application->height - 15, 
                $application->height + 15
            ]);
        }
        
        // Вес ±15 кг (строгий поиск)
        if ($application->weight && $application->weight > 0) {
            $hasStrictCriteria = true;
            $exactQuery->where(function($q) use ($application) {
                $q->whereNull('weight')
                  ->orWhereBetween('weight', [
                      max(40, $application->weight - 15), 
                      $application->weight + 15
                  ]);
            });
        }
        
        // Точные совпадения цвета (строгий поиск)
        if ($application->eye_color && $application->eye_color !== '-' && $application->eye_color !== 'Не важно') {
            $hasStrictCriteria = true;
            $exactQuery->where('eye_color', $application->eye_color);
        }
        
        if ($application->hair_color && $application->hair_color !== '-' && $application->hair_color !== 'Не важно') {
            $hasStrictCriteria = true;
            $exactQuery->where('hair_color', $application->hair_color);
        }
        
        // Пытаемся получить точные совпадения
        $exactCount = $exactQuery->count();
        $isFallback = false;
        
        \Log::info('Exact matches found', ['count' => $exactCount]);
        
        // Если точных совпадений нет и были строгие критерии - используем расширенный поиск
        if ($exactCount === 0 && $hasStrictCriteria) {
            \Log::info('No exact matches, using fallback search');
            $isFallback = true;
            // Расширенный поиск - только по полу, сортируем по близости к критериям
            $models = $query->get();
        } else {
            // Используем точный поиск с пагинацией
            $models = $exactQuery->orderBy('created_at', 'desc')->paginate(12);
        }
        
        // Если получили коллекцию, преобразуем в пагинатор
        if (!$isFallback) {
            // Рассчитываем процент совпадения для точных результатов
            $models->getCollection()->transform(function($model) use ($application) {
                return $this->calculateMatchPercent($model, $application);
            });
        } else {
            // Рассчитываем процент для всех моделей и сортируем по совпадению
            $models = $models->map(function($model) use ($application) {
                return $this->calculateMatchPercent($model, $application);
            })->sortByDesc('match_percent')->values();
            
            // Создаем пагинатор вручную
            $perPage = 12;
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $currentPageItems = $models->slice(($currentPage - 1) * $perPage, $perPage);
            
            $models = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $models->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        }
        
        return view('admin.castings.find-models', compact('application', 'models', 'isFallback'));
    }
    
    /**
     * Рассчитать процент совпадения модели с критериями кастинга
     */
    private function calculateMatchPercent($model, $application)
    {
        $totalCriteria = 0;
        $matchedCriteria = 0;
        
        // Пол (обязательный критерий)
        if ($application->gender && $application->gender !== 'any') {
            $totalCriteria++;
            if ($model->gender === $application->gender) {
                $matchedCriteria++;
            }
        }
        
        // Возраст (±5 лет = 100%, ±10 лет = 50%, ±20 лет = 25%)
        if ($application->age && $application->age > 0 && $model->age > 0) {
            $totalCriteria++;
            $ageDiff = abs($model->age - $application->age);
            if ($ageDiff <= 5) {
                $matchedCriteria += 1;
            } elseif ($ageDiff <= 10) {
                $matchedCriteria += 0.5;
            } elseif ($ageDiff <= 20) {
                $matchedCriteria += 0.25;
            }
        }
        
        // Рост (±7 см = 100%, ±15 см = 50%, ±30 см = 25%)
        if ($application->height && $application->height > 0 && $model->height > 0) {
            $totalCriteria++;
            $heightDiff = abs($model->height - $application->height);
            if ($heightDiff <= 7) {
                $matchedCriteria += 1;
            } elseif ($heightDiff <= 15) {
                $matchedCriteria += 0.5;
            } elseif ($heightDiff <= 30) {
                $matchedCriteria += 0.25;
            }
        }
        
        // Вес (±7 кг = 100%, ±15 кг = 50%, ±25 кг = 25%)
        if ($application->weight && $application->weight > 0 && $model->weight) {
            $totalCriteria++;
            $weightDiff = abs($model->weight - $application->weight);
            if ($weightDiff <= 7) {
                $matchedCriteria += 1;
            } elseif ($weightDiff <= 15) {
                $matchedCriteria += 0.5;
            } elseif ($weightDiff <= 25) {
                $matchedCriteria += 0.25;
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
        if ($application->eye_color && $application->eye_color !== '-' && $application->eye_color !== 'Не важно') {
            $totalCriteria++;
            if ($model->eye_color === $application->eye_color) {
                $matchedCriteria++;
            }
        }
        
        // Цвет волос
        if ($application->hair_color && $application->hair_color !== '-' && $application->hair_color !== 'Не важно') {
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
        
        // Добавляем детальную информацию о совпадениях
        $model->match_details = [
            'height_diff' => $application->height && $model->height ? abs($model->height - $application->height) : null,
            'age_diff' => $application->age && $model->age ? abs($model->age - $application->age) : null,
            'weight_diff' => $application->weight && $model->weight ? abs($model->weight - $application->weight) : null,
        ];
        
        return $model;
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
        
        // Получаем настройки бота
        $botSettings = TelegramBotSettings::current();
        $telegramSentCount = 0;
        $emailSentCount = 0;
        
        // Отправляем уведомления моделям
        foreach ($models as $model) {
            Log::info('Processing model for casting invitation', [
                'model_id' => $model->id,
                'model_name' => $model->full_name,
                'model_email' => $model->email,
                'has_user' => !is_null($model->user),
                'user_id' => $model->user_id,
                'telegram_id' => $model->user ? $model->user->telegram_id : null
            ]);
            
            // Отправляем email через очередь
            if ($model->email) {
                \Illuminate\Support\Facades\Mail::to($model->email)
                    ->queue(new \App\Mail\CastingInvitationMail($model, $application));
                
                $emailSentCount++;
                
                Log::info('Casting invitation email queued', [
                    'model_id' => $model->id,
                    'model_name' => $model->full_name,
                    'model_email' => $model->email,
                    'casting_id' => $application->id
                ]);
            } else {
                Log::warning('Model has no email', [
                    'model_id' => $model->id,
                    'model_name' => $model->full_name
                ]);
            }
            
            // Отправляем Telegram сообщение, если у модели привязан аккаунт и бот настроен
            if ($model->user && $model->user->telegram_id && $botSettings->isConfigured() && $botSettings->is_active) {
                $message = "🎬 <b>Новое приглашение на кастинг!</b>\n\n";
                $message .= "Вас выбрали для участия в кастинге!\n\n";
                
                $message .= "👤 <b>Заявка:</b> " . htmlspecialchars($application->full_name) . "\n";
                $message .= "📍 <b>Город:</b> " . htmlspecialchars($application->city) . "\n";
                
                if ($application->gender) {
                    $gender = $application->gender === 'male' ? 'Мужчина' : 'Женщина';
                    $message .= "⚧ <b>Пол:</b> " . $gender . "\n";
                }
                
                if ($application->age) {
                    $message .= "🎂 <b>Возраст:</b> " . $application->age . " лет\n";
                }
                
                if ($application->height) {
                    $message .= "📏 <b>Рост:</b> " . $application->height . " см\n";
                }
                
                if ($application->categories_interest && is_array($application->categories_interest) && count($application->categories_interest) > 0) {
                    $message .= "🎯 <b>Интересы:</b> " . implode(', ', array_map('htmlspecialchars', $application->categories_interest)) . "\n";
                }
                
                $message .= "\n✅ Пожалуйста, свяжитесь с агентством для уточнения деталей.";
                
                try {
                    $response = \Illuminate\Support\Facades\Http::post(
                        "https://api.telegram.org/bot{$botSettings->bot_token}/sendMessage",
                        [
                            'chat_id' => $model->user->telegram_id,
                            'text' => $message,
                            'parse_mode' => 'HTML'
                        ]
                    );
                    
                    if ($response->successful() && $response->json('ok')) {
                        $telegramSentCount++;
                        
                        Log::info('Casting invitation sent via Telegram', [
                            'model_id' => $model->id,
                            'model_name' => $model->full_name,
                            'telegram_id' => $model->user->telegram_id,
                            'telegram_username' => $model->user->telegram_username,
                            'casting_id' => $application->id
                        ]);
                    } else {
                        Log::warning('Failed to send Telegram message', [
                            'model_id' => $model->id,
                            'model_name' => $model->full_name,
                            'telegram_id' => $model->user->telegram_id,
                            'error' => $response->json('description')
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Exception sending Telegram message', [
                        'model_id' => $model->id,
                        'model_name' => $model->full_name,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                // Логируем почему не отправили Telegram
                if (!$model->user) {
                    Log::info('Model has no linked user account', [
                        'model_id' => $model->id,
                        'model_name' => $model->full_name
                    ]);
                } elseif (!$model->user->telegram_id) {
                    Log::info('User has no linked Telegram account', [
                        'model_id' => $model->id,
                        'model_name' => $model->full_name,
                        'user_id' => $model->user_id
                    ]);
                } elseif (!$botSettings->isConfigured()) {
                    Log::warning('Bot not configured');
                } elseif (!$botSettings->is_active) {
                    Log::warning('Bot not active');
                }
            }
        }
        
        Log::info('Models assigned to casting', [
            'casting_id' => $application->id,
            'total_models' => count($modelIds),
            'emails_sent' => $emailSentCount,
            'telegrams_sent' => $telegramSentCount,
            'selected_model_ids' => $modelIds
        ]);
            'application_id' => $application->id,
            'models_count' => count($modelIds),
            'model_ids' => $modelIds,
            'emails_queued' => $emailSentCount,
            'telegram_sent' => $telegramSentCount,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name
        ]);
        
        $successMessage = 'Выбрано ' . count($modelIds) . ' ' . 
                   (count($modelIds) === 1 ? 'модель' : (count($modelIds) < 5 ? 'модели' : 'моделей')) . 
                   ' для кастинга. ';
        
        if ($emailSentCount > 0) {
            $successMessage .= "Email отправлен: {$emailSentCount}. ";
        }
        
        if ($telegramSentCount > 0) {
            $successMessage .= "Telegram уведомлений: {$telegramSentCount}.";
        }
        
        return redirect()
            ->route('admin.castings.show', $id)
            ->with('success', $successMessage);
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
