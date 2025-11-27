<?php

namespace App\Http\Controllers;

use App\Models\CastingApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CastingController extends Controller
{
    public function index()
    {
        return view('casting.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|in:male,female',
            'age' => 'required|string',
            'eye_color' => 'required|string',
            'hair_color' => 'required|string',
            'height' => 'required|integer',
            'weight' => 'required|integer',
            'model_school' => 'nullable|string',
            'catwalk_skills' => 'nullable|string',
            'posing_skills' => 'nullable|string',
            'experience' => 'nullable|string',
            'photos' => 'required|array',
            'photos.*' => 'file',
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
        ]);

        // Обработка фотографий
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $this->compressAndStoreImage($photo);
            }
        }

        // Сохраняем заявку
        CastingApplication::create([
            'gender' => $validated['gender'],
            'first_name' => $validated['name'], // Используем name как first_name
            'last_name' => '-', // Заглушка
            'age' => 0, // Заглушка, тк age хранится как строка
            'birth_date' => now(), // Заглушка
            'city' => '-', // Заглушка
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'height' => $validated['height'],
            'weight' => $validated['weight'],
            'eye_color' => $validated['eye_color'],
            'hair_color' => $validated['hair_color'],
            'shoe_size' => 0, // Заглушка
            'clothing_size' => '-', // Заглушка для размера одежды
            'skin_tone' => '-', // Заглушка для тона кожи
            'has_modeling_school' => $validated['model_school'] === 'yes',
            'photo_portrait' => $photos[0] ?? null,
            'photo_full_body' => $photos[1] ?? null,
            'photo_profile' => $photos[2] ?? null,
            'photo_additional_1' => $photos[3] ?? null,
            'photo_additional_2' => $photos[4] ?? null,
            'experience_description' => implode(', ', [
                'Возрастная категория: ' . ($validated['age'] ?? '-'),
                'Дефиле: ' . ($validated['catwalk_skills'] ?? '-'),
                'Позирование: ' . ($validated['posing_skills'] ?? '-'),
                'Опыт: ' . ($validated['experience'] ?? '-'),
            ]),
        ]);

        return redirect()->route('casting.thanks')->with('success', 'Ваша заявка успешно отправлена!');
    }

    /**
     * Сжатие и сохранение изображения
     */
    private function compressAndStoreImage($file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.webp';
        $path = 'casting_photos/' . $filename;
        
        // Создаём изображение из загруженного файла
        $image = null;
        if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
            $image = imagecreatefromjpeg($file->getRealPath());
        } elseif ($extension === 'png') {
            $image = imagecreatefrompng($file->getRealPath());
        } elseif ($extension === 'gif') {
            $image = imagecreatefromgif($file->getRealPath());
        } elseif ($extension === 'webp') {
            $image = imagecreatefromwebp($file->getRealPath());
        } else {
            // Если формат не поддерживается, сохраняем как есть
            return $file->store('casting_photos', 'public');
        }

        if (!$image) {
            return $file->store('casting_photos', 'public');
        }

        // Получаем размеры
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Масштабируем если больше 1920px
        $maxWidth = 1920;
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int)($height * ($maxWidth / $width));
            
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Сохраняем в WebP с качеством 85
        $fullPath = storage_path('app/public/' . $path);
        
        // Создаём директорию если не существует
        $directory = dirname($fullPath);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        imagewebp($image, $fullPath, 85);
        imagedestroy($image);

        return $path;
    }

    public function thanks()
    {
        return view('casting.thanks');
    }
}
