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

        // Размер одежды
        if ($request->filled('clothing_size')) {
            $query->where('clothing_size', $request->clothing_size);
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
            $query->whereRaw('JSON_CONTAINS(languages, ?)', [json_encode($request->languages)]);
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
        $sort = $request->get('sort', 'new');
        switch ($sort) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'name':
                $query->orderBy('first_name', 'asc');
                break;
            default: 
                $query->orderBy('created_at', 'desc');
                break;
        }
        $models = $query->paginate(12);
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
        // Валидация ID
        $validated = validator(['id' => $id], ['id' => 'required|integer|min:1'])->validate();
        $model = ModelProfile::active()->findOrFail($validated['id']);
        
        // Защита от ботов - incrementViews только для браузеров
        $userAgent = request()->userAgent();
        if ($userAgent && !preg_match('/bot|crawl|slurp|spider|mediapartners/i', $userAgent)) {
            $model->incrementViews();
        }

        $relatedModels = ModelProfile::active()
            ->where('id', '!=', $validated['id'])
            ->where('gender', $model->gender)
            ->orderBy('views_count', 'desc')
            ->limit(4)
            ->get();

        return view('models.show', compact('model', 'relatedModels'));
    }

    public function registerForm()
    {
        $user = auth()->user();
        return view('models.register', compact('user'));
    }

    public function registerSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:models,email',
            'phone' => 'required|string',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:female,male',
            'city' => 'required|string',
            'country' => 'nullable|string',
            'height' => 'required|numeric|min:140|max:220',
            'weight' => 'required|numeric|min:40|max:150',
            'eye_color' => 'required|string',
            'hair_color' => 'required|string',
            'bust' => 'required|numeric|min:60|max:150',
            'waist' => 'required|numeric|min:50|max:120',
            'hips' => 'required|numeric|min:60|max:150',
            'shoe_size' => 'required|numeric|min:33|max:46',
            'clothing_size' => 'required|string|in:XS,S,M,L,XL,XXL,XXXL',
            'appearance_type' => 'required|string|in:Славянский,Европейский,Азиатский,Афро,Мулат',
            'vk' => 'nullable|string',
            'telegram' => 'nullable|string',
            'facebook' => 'nullable|string',
            'experience' => 'nullable|string',
            'catwalk_skills' => 'nullable|string',
            'posing_skills' => 'nullable|string',
            'languages' => 'nullable|array',
            'languages.*.enabled' => 'nullable|boolean',
            'languages.*.level' => 'nullable|string|in:Начальный,Разговорный,Переводчик',
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'required|file|image|max:10240',
            'agree' => 'required|accepted',
        ], [
            'name.required' => 'Укажите имя',
            'surname.required' => 'Укажите фамилию',
            'email.required' => 'Укажите email',
            'email.email' => 'Некорректный формат email',
            'email.unique' => 'Модель с таким email уже зарегистрирована',
            'phone.required' => 'Укажите телефон',
            'birth_date.required' => 'Укажите дату рождения',
            'gender.required' => 'Укажите пол',
            'city.required' => 'Укажите город',
            'height.required' => 'Укажите рост',
            'height.min' => 'Рост должен быть не менее 140 см',
            'height.max' => 'Рост должен быть не более 220 см',
            'weight.required' => 'Укажите вес',
            'weight.min' => 'Вес должен быть не менее 40 кг',
            'weight.max' => 'Вес должен быть не более 150 кг',
            'eye_color.required' => 'Укажите цвет глаз',
            'hair_color.required' => 'Укажите цвет волос',
            'bust.required' => 'Укажите объем груди',
            'bust.min' => 'Объем груди должен быть не менее 60 см',
            'bust.max' => 'Объем груди должен быть не более 150 см',
            'waist.required' => 'Укажите объем талии',
            'waist.min' => 'Объем талии должен быть не менее 50 см',
            'waist.max' => 'Объем талии должен быть не более 120 см',
            'hips.required' => 'Укажите объем бедер',
            'hips.min' => 'Объем бедер должен быть не менее 60 см',
            'hips.max' => 'Объем бедер должен быть не более 150 см',
            'shoe_size.required' => 'Укажите размер обуви',
            'shoe_size.min' => 'Размер обуви должен быть не менее 33',
            'shoe_size.max' => 'Размер обуви должен быть не более 46',
            'clothing_size.required' => 'Укажите размер одежды',
            'appearance_type.required' => 'Укажите тип внешности',
            'photos.required' => 'Загрузите хотя бы одну фотографию',
            'photos.min' => 'Загрузите хотя бы одну фотографию',
            'photos.max' => 'Можно загрузить не более 10 фотографий',
            'photos.*.image' => 'Все файлы должны быть изображениями',
            'photos.*.max' => 'Размер каждого файла не должен превышать 10 МБ',
            'agree.required' => 'Необходимо согласие на обработку персональных данных',
            'agree.accepted' => 'Необходимо согласие на обработку персональных данных',
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

        // Обработка языков
        $languagesData = [];
        if (isset($validated['languages']) && is_array($validated['languages'])) {
            foreach ($validated['languages'] as $langKey => $langData) {
                if (isset($langData['enabled']) && $langData['enabled'] && isset($langData['level']) && $langData['level']) {
                    $languagesData[] = [
                        'language' => $langKey,
                        'level' => $langData['level']
                    ];
                }
            }
        }
        $validated['languages'] = $languagesData;

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
            $validated['clothing_size_numeric'] = $sizeMap[$validated['clothing_size']] ?? 44;
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
            
            // Генерируем model_number если его нет
            if (!$model->model_number) {
                $model->model_number = 'GM' . str_pad($model->id, 5, '0', STR_PAD_LEFT);
                $model->save();
            }
            
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
