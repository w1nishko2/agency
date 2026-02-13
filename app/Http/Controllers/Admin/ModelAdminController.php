<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

        // Для AJAX-запросов (автосохранение) делаем email и phone опциональными
        $isAjax = $request->ajax() || $request->wantsJson();
        
        $validated = $request->validate([
            'model_number' => 'nullable|string|max:20|unique:models,model_number,' . $model->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ($isAjax ? 'nullable' : 'required') . '|email|max:255|unique:models,email,' . $validated_id['id'],
            'phone' => ($isAjax ? 'nullable' : 'required') . '|string|max:20|unique:models,phone,' . $validated_id['id'],
            'gender' => 'required|in:male,female',
            'age' => $isAjax ? 'nullable|integer' : 'required|integer',
            'city' => 'required|string|max:255',
            'height' => 'required|integer|min:150|max:220',
            'weight' => 'required|integer|min:40|max:150',
            'bust' => 'nullable|integer|min:50|max:150',
            'waist' => 'nullable|integer|min:40|max:120',
            'hips' => 'nullable|integer|min:50|max:150',
            'eye_color' => 'required|string|max:50',
            'hair_color' => 'required|string|max:50',
            'shoe_size' => 'nullable|numeric|min:30|max:50',
            'clothing_size' => 'nullable|string|in:XS,S,M,L,XL,XXL,XXXL',
            'appearance_type' => 'nullable|string|in:Славянский,Европейский,Азиатский,Афро,Мулат',
            'bio' => 'nullable|string|max:2000',
            'telegram' => 'nullable|string|max:100',
            'vk' => 'nullable|string|max:100',
            'experience_description' => 'nullable|string|max:2000',
            'languages' => 'nullable|string|max:500',
            'status' => 'required|in:pending,active,inactive,rejected',
        ]);
        
        // Обработка is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        
        // Вычисление clothing_size_numeric для фильтрации
        if (isset($validated['clothing_size'])) {
            $sizeMap = [
                'XS' => 40,
                'S' => 42,
                'M' => 44,
                'L' => 46,
                'XL' => 48,
                'XXL' => 50,
                'XXXL' => 52
            ];
            $validated['clothing_size_numeric'] = $sizeMap[$validated['clothing_size']] ?? null;
        }
        
        // Санитизация текстовых полей
        if (!empty($validated['bio'])) {
            $validated['bio'] = strip_tags($validated['bio']);
        }
        if (!empty($validated['experience_description'])) {
            $validated['experience_description'] = strip_tags($validated['experience_description']);
        }
        
        // Обработка поля languages - преобразование строки в массив объектов
        if (isset($validated['languages'])) {
            if (!empty($validated['languages'])) {
                // Разделяем по запятой и создаем массив объектов
                $languagesArray = array_map('trim', explode(',', $validated['languages']));
                $languagesArray = array_filter($languagesArray); // Убираем пустые значения
                
                $validated['languages'] = array_map(function($lang) {
                    return ['language' => $lang];
                }, $languagesArray);
            } else {
                $validated['languages'] = [];
            }
        }
        
        // Обработка изменения порядка фотографий (оптимизировано)
        if ($request->has('photos_order') && !empty($request->photos_order)) {
            $newOrder = json_decode($request->photos_order, true);
            if (is_array($newOrder) && $model->photos) {
                $currentPhotos = $model->photos;
                $reorderedPhotos = [];
                
                foreach ($newOrder as $oldIndex) {
                    if (isset($currentPhotos[$oldIndex])) {
                        $reorderedPhotos[] = $currentPhotos[$oldIndex];
                    }
                }
                
                // Обновляем main_photo и portfolio_photos на основе нового порядка
                if (count($reorderedPhotos) > 0) {
                    $validated['main_photo'] = $reorderedPhotos[0];
                    $validated['portfolio_photos'] = array_slice($reorderedPhotos, 1);
                }
            }
        }

        // Оптимизация: используем update напрямую вместо транзакции для простых обновлений
        if ($isAjax) {
            // Для автосохранения используем простое обновление без транзакции
            $model->update($validated);
        } else {
            // Для обычных сохранений используем транзакцию
            DB::transaction(function() use ($model, $validated) {
                $model->update($validated);
            });
        }

        Log::info('Model updated', [
            'model_id' => $model->id,
            'model_name' => $model->full_name,
            'admin_id' => auth()->id(),
            'admin_name' => auth()->user()->name,
            'is_ajax' => $isAjax
        ]);

        // Для AJAX запросов (автосохранение) возвращаем JSON
        if ($isAjax) {
            return response()->json([
                'success' => true,
                'message' => 'Профиль модели обновлен!'
            ]);
        }

        return back()->with('success', 'Профиль модели обновлен!');
    }

    /**
     * Массовая загрузка фотографий с конвертацией в WebP
     */
    public function uploadPhotos(Request $request, $id)
    {
        $validated_id = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::findOrFail($validated_id['id']);

        // Валидация файлов
        $request->validate([
            'photos' => 'required|array|min:1|max:50',
            'photos.*' => 'required|image|mimes:jpeg,jpg,png,gif,bmp,tiff,webp|max:20480' // до 20MB
        ]);

        $uploadedPhotos = [];
        $errors = [];

        try {
            // Создаем ImageManager с драйвером GD
            $manager = new ImageManager(new Driver());

            foreach ($request->file('photos') as $index => $photo) {
                try {
                    // Генерируем уникальное имя файла
                    $filename = 'model_' . $model->id . '_' . time() . '_' . $index . '.webp';
                    $path = 'models/photos/' . $filename;

                    // Загружаем изображение с помощью Intervention Image v3
                    $image = $manager->read($photo->getPathname());

                    // Оптимизация: уменьшаем максимальный размер до 1600px для быстрой обработки
                    $maxWidth = 1600;
                    $maxHeight = 1600;

                    // Пропорциональное уменьшение, если изображение больше максимальных размеров
                    if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                        $image->scale(width: $maxWidth, height: $maxHeight);
                    }

                    // Оптимизация: конвертируем в WebP с качеством 80 для баланса качества и скорости
                    $encoded = $image->toWebp(quality: 80);

                    // Сохраняем в storage/app/public
                    Storage::disk('public')->put($path, (string) $encoded);

                    $uploadedPhotos[] = $path;

                    Log::info('Photo uploaded and converted', [
                        'model_id' => $model->id,
                        'filename' => $filename,
                        'original_name' => $photo->getClientOriginalName(),
                        'size' => strlen((string) $encoded),
                        'admin_id' => auth()->id()
                    ]);

                } catch (\Exception $e) {
                    Log::error('Failed to process photo', [
                        'model_id' => $model->id,
                        'photo_index' => $index,
                        'original_name' => $photo->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                    $errors[] = "Ошибка обработки фото {$photo->getClientOriginalName()}: " . $e->getMessage();
                }
            }

            // Обновляем портфолио модели
            if (!empty($uploadedPhotos)) {
                // Оптимизация: простое обновление без транзакции для быстроты
                $currentPhotos = $model->portfolio_photos ?? [];
                
                // Если главного фото нет, делаем первое загруженное фото главным
                if (empty($model->main_photo)) {
                    $model->main_photo = array_shift($uploadedPhotos);
                }
                
                // Добавляем остальные фото в портфолио
                $model->portfolio_photos = array_merge($currentPhotos, $uploadedPhotos);
                $model->save();

                Log::info('Model photos updated', [
                    'model_id' => $model->id,
                    'uploaded_count' => count($uploadedPhotos) + (empty($model->main_photo) ? 1 : 0),
                    'admin_id' => auth()->id()
                ]);
            }

            $message = count($uploadedPhotos) > 0 
                ? 'Загружено фотографий: ' . count($uploadedPhotos)
                : 'Фотографии не были загружены';

            if (!empty($errors)) {
                $message .= '. Ошибки: ' . implode('; ', $errors);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'uploaded' => count($uploadedPhotos),
                'errors' => $errors,
                'photos' => $model->fresh()->photos // Обновленный список всех фото
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to upload photos', [
                'model_id' => $model->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при загрузке фотографий: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Обрезка фотографии модели
     */
    public function cropPhoto(Request $request, $id)
    {
        $validated_id = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::findOrFail($validated_id['id']);

        // Валидация данных обрезки
        $validated = $request->validate([
            'photo_path' => 'required|string',
            'crop_data' => 'required|string',
        ]);

        try {
            $photoPath = $validated['photo_path'];
            $cropData = json_decode($validated['crop_data'], true);

            // Проверяем корректность данных обрезки
            if (!$cropData || !isset($cropData['width'], $cropData['height'], $cropData['x'], $cropData['y'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Некорректные данные обрезки'
                ], 400);
            }

            // Проверяем, что фото принадлежит этой модели
            $modelPhotos = $model->photos;
            if (!in_array($photoPath, $modelPhotos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Фотография не найдена в профиле модели'
                ], 404);
            }

            // Проверяем существование файла
            if (!Storage::disk('public')->exists($photoPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Файл не найден в хранилище'
                ], 404);
            }

            // Создаем ImageManager
            $manager = new ImageManager(new Driver());

            // Загружаем оригинальное изображение
            $fullPath = storage_path('app/public/' . $photoPath);
            
            // Проверяем физическое существование файла
            if (!file_exists($fullPath)) {
                Log::error('Crop: File not found', ['path' => $fullPath]);
                return response()->json([
                    'success' => false,
                    'message' => 'Физический файл не найден: ' . basename($fullPath)
                ], 404);
            }
            
            Log::info('Crop: Loading image', [
                'path' => $fullPath,
                'size' => filesize($fullPath)
            ]);
            
            $image = $manager->read($fullPath);
            
            Log::info('Crop: Image loaded', [
                'width' => $image->width(),
                'height' => $image->height()
            ]);

            // Валидация размеров обрезки
            $cropWidth = (int) $cropData['width'];
            $cropHeight = (int) $cropData['height'];
            $cropX = (int) $cropData['x'];
            $cropY = (int) $cropData['y'];
            
            // Проверяем, что обрезка не выходит за границы изображения
            if ($cropX < 0 || $cropY < 0) {
                throw new \Exception('Некорректные координаты обрезки');
            }
            
            if ($cropX + $cropWidth > $image->width() || $cropY + $cropHeight > $image->height()) {
                Log::warning('Crop: Adjusting crop dimensions', [
                    'original' => ['x' => $cropX, 'y' => $cropY, 'w' => $cropWidth, 'h' => $cropHeight],
                    'image' => ['w' => $image->width(), 'h' => $image->height()]
                ]);
                
                // Корректируем размеры, если они выходят за границы
                $cropWidth = min($cropWidth, $image->width() - $cropX);
                $cropHeight = min($cropHeight, $image->height() - $cropY);
            }

            // Применяем обрезку с помощью данных от Cropper.js
            $image->crop(
                width: $cropWidth,
                height: $cropHeight,
                offset_x: $cropX,
                offset_y: $cropY
            );
            
            Log::info('Crop: Applied crop', [
                'new_width' => $image->width(),
                'new_height' => $image->height()
            ]);

            // Если нужно масштабировать до определенного размера после обрезки
            if (isset($cropData['targetWidth']) && isset($cropData['targetHeight'])) {
                $image->scale(
                    width: (int) $cropData['targetWidth'],
                    height: (int) $cropData['targetHeight']
                );
            }

            // Оптимизация: конвертируем в WebP с качеством 80
            $encoded = $image->toWebp(quality: 80);
            
            $encodedSize = strlen((string) $encoded);
            Log::info('Crop: Image encoded', ['size' => $encodedSize]);
            
            if ($encodedSize === 0) {
                throw new \Exception('Обрезанное изображение имеет нулевой размер');
            }

            // Создаем резервную копию оригинала (если еще не создана)
            $pathInfo = pathinfo($photoPath);
            $backupPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_original.webp';
            
            if (!Storage::disk('public')->exists($backupPath)) {
                Storage::disk('public')->copy($photoPath, $backupPath);
                Log::info('Crop: Backup created', ['backup_path' => $backupPath]);
            }

            // Сохраняем обрезанное изображение, заменяя оригинал
            $saveResult = Storage::disk('public')->put($photoPath, (string) $encoded);
            
            if (!$saveResult) {
                throw new \Exception('Не удалось сохранить обрезанное изображение');
            }
            
            Log::info('Crop: File saved', ['saved_size' => $encodedSize]);

            Log::info('Photo cropped successfully', [
                'model_id' => $model->id,
                'photo_path' => $photoPath,
                'crop_data' => $cropData,
                'backup_path' => $backupPath,
                'file_size' => strlen((string) $encoded),
                'admin_id' => auth()->id()
            ]);

            // Генерируем URL с timestamp для обхода кеша браузера
            $timestamp = time();
            $photoUrl = asset('storage/' . $photoPath) . '?v=' . $timestamp;

            return response()->json([
                'success' => true,
                'message' => 'Фотография успешно обрезана и сохранена',
                'photo_url' => $photoUrl,
                'photo_path' => $photoPath,
                'timestamp' => $timestamp
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to crop photo', [
                'model_id' => $model->id,
                'photo_path' => $validated['photo_path'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обрезке фотографии: ' . $e->getMessage()
            ], 500);
        }
    }
}
