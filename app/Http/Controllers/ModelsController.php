<?php

namespace App\Http\Controllers;

use App\Models\ModelProfile;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Hash;

class ModelsController extends Controller
{
    public function index(Request $request)
    {
        $query = ModelProfile::query()->active();

        // Фильтры
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('gender')) {
            $query->byGender($request->gender);
        }

        if ($request->filled('city')) {
            $query->byCity($request->city);
        }

        // Возраст (от/до)
        if ($request->filled('age_from')) {
            $query->where('age', '>=', $request->age_from);
        }

        if ($request->filled('age_to')) {
            $query->where('age', '<=', $request->age_to);
        }

        // Рост (от/до)
        if ($request->filled('height_from')) {
            $query->where('height', '>=', $request->height_from);
        }

        if ($request->filled('height_to')) {
            $query->where('height', '<=', $request->height_to);
        }

        // Размер одежды (от/до)
        if ($request->filled('clothing_size_from')) {
            $query->where('clothing_size_numeric', '>=', $request->clothing_size_from);
        }

        if ($request->filled('clothing_size_to')) {
            $query->where('clothing_size_numeric', '<=', $request->clothing_size_to);
        }

        // Размер обуви (от/до)
        if ($request->filled('shoe_size_from')) {
            $query->where('shoe_size', '>=', $request->shoe_size_from);
        }

        if ($request->filled('shoe_size_to')) {
            $query->where('shoe_size', '<=', $request->shoe_size_to);
        }

        // Типаж внешности
        if ($request->filled('appearance_type')) {
            $query->where('appearance_type', $request->appearance_type);
        }

        // Цвет кожи
        if ($request->filled('skin_color')) {
            $query->where('skin_color', $request->skin_color);
        }

        if ($request->filled('eye_color')) {
            $query->where('eye_color', $request->eye_color);
        }

        if ($request->filled('hair_color')) {
            $query->where('hair_color', $request->hair_color);
        }

        // Знание языков
        if ($request->filled('languages')) {
            $query->whereJsonContains('languages', $request->languages);
        }

        // Дополнительные критерии (чекбоксы)
        if ($request->has('has_snaps')) {
            $query->where('has_snaps', true);
        }

        if ($request->has('has_video_presentation')) {
            $query->where('has_video_presentation', true);
        }

        if ($request->has('has_video_walk')) {
            $query->where('has_video_walk', true);
        }

        if ($request->has('has_passport')) {
            $query->where('has_passport', true);
        }

        if ($request->has('has_professional_experience')) {
            $query->where('has_professional_experience', true);
        }

        if ($request->has('has_tattoos')) {
            $query->where('has_tattoos', true);
        }

        if ($request->has('has_piercings')) {
            $query->where('has_piercings', true);
        }

        // Сортировка
        $sort = $request->get('sort', 'new');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'name':
                $query->orderBy('first_name', 'asc');
                break;
            default: // 'new'
                $query->orderBy('created_at', 'desc');
                break;
        }

        $models = $query->paginate(12);

        // Для AJAX запросов возвращаем JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'models' => $models->items(),
                'hasMorePages' => $models->hasMorePages(),
                'currentPage' => $models->currentPage(),
                'total' => $models->total(),
            ]);
        }

        return view('models.index', compact('models'));
    }

    public function show($id)
    {
        $model = ModelProfile::active()->findOrFail($id);
        $model->incrementViews();

        $relatedModels = ModelProfile::active()
            ->where('id', '!=', $id)
            ->where('gender', $model->gender)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('models.show', compact('model', 'relatedModels'));
    }

    public function registerForm()
    {
        return view('models.register');
    }

    public function registerSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:models,email',
            'phone' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:female,male',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'eye_color' => 'nullable|string',
            'hair_color' => 'nullable|string',
            'bust' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'hips' => 'nullable|numeric',
            'shoe_size' => 'nullable|numeric',
            'instagram' => 'nullable|string',
            'vk' => 'nullable|string',
            'telegram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'experience' => 'nullable|string',
            'catwalk_skills' => 'nullable|string',
            'posing_skills' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|file',
        ]);

        // Загрузка и обработка фотографий
        $portfolioPhotos = [];
        $mainPhoto = null;

        if ($request->hasFile('photos')) {
            $manager = new ImageManager(new Driver());
            
            foreach ($request->file('photos') as $index => $photo) {
                $filename = time() . '_' . uniqid() . '.webp';
                $path = storage_path('app/public/models/photos/' . $filename);
                
                // Создаем директорию если не существует
                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0755, true);
                }

                // Обработка и сжатие изображения
                $image = $manager->read($photo);
                
                // Определяем ориентацию и изменяем размер
                if ($image->width() > $image->height()) {
                    // Горизонтальное фото
                    $image->scale(width: 1920);
                } else {
                    // Вертикальное фото
                    $image->scale(height: 1920);
                }
                
                // Сохраняем в WebP с качеством 85%
                $image->toWebp(quality: 85)->save($path);

                $relativePath = 'models/photos/' . $filename;
                
                // Первое фото - главное
                if ($index === 0) {
                    $mainPhoto = $relativePath;
                } else {
                    $portfolioPhotos[] = $relativePath;
                }
            }
        }

        $validated['main_photo'] = $mainPhoto;
        $validated['portfolio_photos'] = $portfolioPhotos;

        // Вычисление возраста
        if (isset($validated['birth_date']) && $validated['birth_date']) {
            $validated['age'] = \Carbon\Carbon::parse($validated['birth_date'])->age;
        } else {
            $validated['age'] = 18; // Возраст по умолчанию
        }

        // Мапинг полей name -> first_name, surname -> last_name
        if (isset($validated['name']) && $validated['name']) {
            $validated['first_name'] = $validated['name'];
            unset($validated['name']);
        } else {
            $validated['first_name'] = 'Модель';
        }
        
        if (isset($validated['surname']) && $validated['surname']) {
            $validated['last_name'] = $validated['surname'];
            unset($validated['surname']);
        } else {
            $validated['last_name'] = time(); // Уникальная фамилия
        }

        // Значения по умолчанию для обязательных полей
        $validated['gender'] = $validated['gender'] ?? 'female';
        $validated['city'] = $validated['city'] ?? 'Не указан';
        $validated['eye_color'] = $validated['eye_color'] ?? 'Не указан';
        $validated['hair_color'] = $validated['hair_color'] ?? 'Не указан';
        $validated['height'] = $validated['height'] ?? 170;

        // Удаляем поля, которых нет в таблице
        unset($validated['country']);
        unset($validated['catwalk_skills']);
        unset($validated['posing_skills']);

        // Статус модели - на модерации
        $validated['status'] = 'pending';
        
        // Добавляем experience в experience_description
        if (isset($validated['experience'])) {
            $validated['experience_description'] = $validated['experience'];
            unset($validated['experience']);
        }
        
        // Проверяем, авторизован ли пользователь
        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
            $model = ModelProfile::create($validated);
            
            return redirect()->route('profile')->with('success', 'Ваша анкета модели создана и отправлена на модерацию!');
        } else {
            // Если не авторизован - создаем нового пользователя
            $user = \App\Models\User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'] ?? 'model_' . time() . '@temp.agency',
                'password' => Hash::make(time() . rand(1000, 9999)), // Случайный пароль
            ]);
            
            $validated['user_id'] = $user->id;
            $model = ModelProfile::create($validated);
            
            // Авторизуем пользователя
            auth()->login($user);
            
            return redirect()->route('profile')->with('success', 'Спасибо за регистрацию! Ваша анкета отправлена на модерацию. Проверьте почту для активации аккаунта.');
        }
    }
}
