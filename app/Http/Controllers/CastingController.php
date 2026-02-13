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
            'project_type' => 'required|string',
            'gender' => 'required|in:male,female,any',
            'age' => 'required|string',
            'height_from' => 'nullable|integer',
            'height_to' => 'nullable|integer',
            'clothing_size' => 'required|string',
            'hair_color' => 'required|string',
            'eye_color' => 'required|string',
            'appearance_type' => 'required|string',
            'languages' => 'nullable|string',
            'bust' => 'nullable|integer',
            'waist' => 'nullable|integer',
            'hips' => 'nullable|integer',
            'shoe_size' => 'nullable|numeric',
            'city' => 'required|string',
            'project_description' => 'required|string',
            'client_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'budget' => 'nullable|string',
        ]);

        // Формируем описание заявки
        $description = "ТИП ПРОЕКТА: {$validated['project_type']}\n\n";
        $description .= "КРИТЕРИИ ПОИСКА МОДЕЛИ:\n";
        $description .= "Пол: {$validated['gender']}\n";
        $description .= "Возраст: {$validated['age']}\n";
        
        if ($validated['height_from'] || $validated['height_to']) {
            $height = '';
            if ($validated['height_from']) $height .= "от {$validated['height_from']} см ";
            if ($validated['height_to']) $height .= "до {$validated['height_to']} см";
            $description .= "Рост: {$height}\n";
        }
        
        $description .= "Размер одежды: {$validated['clothing_size']}\n";
        $description .= "Цвет волос: {$validated['hair_color']}\n";
        $description .= "Цвет глаз: {$validated['eye_color']}\n";
        $description .= "Тип внешности: {$validated['appearance_type']}\n";
        
        if (!empty($validated['languages'])) {
            $description .= "Требуется знание языка: {$validated['languages']}\n";
        }
        
        // Параметры фигуры
        if ($validated['bust'] || $validated['waist'] || $validated['hips']) {
            $params = [];
            if ($validated['bust']) $params[] = "Грудь: {$validated['bust']} см";
            if ($validated['waist']) $params[] = "Талия: {$validated['waist']} см";
            if ($validated['hips']) $params[] = "Бедра: {$validated['hips']} см";
            $description .= "Параметры: " . implode(', ', $params) . "\n";
        }
        
        if ($validated['shoe_size']) {
            $description .= "Размер обуви: {$validated['shoe_size']}\n";
        }
        
        $description .= "Город съемки: {$validated['city']}\n\n";
        
        if ($validated['budget']) {
            $description .= "Бюджет: {$validated['budget']}\n\n";
        }
        
        $description .= "ОПИСАНИЕ ПРОЕКТА:\n{$validated['project_description']}";

        // Сохраняем заявку
        CastingApplication::create([
            'first_name' => $validated['client_name'],
            'last_name' => 'Заявка на подбор',
            'gender' => $validated['gender'],
            'age' => 0,
            'birth_date' => now(),
            'city' => $validated['city'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'height' => $validated['height_from'] ?? 0,
            'weight' => 0,
            'bust' => $validated['bust'] ?? 0,
            'waist' => $validated['waist'] ?? 0,
            'hips' => $validated['hips'] ?? 0,
            'shoe_size' => $validated['shoe_size'] ?? 0,
            'eye_color' => $validated['eye_color'],
            'hair_color' => $validated['hair_color'],
            'clothing_size' => $validated['clothing_size'],
            'skin_tone' => '-',
            'has_modeling_school' => false,
            'experience_description' => $description,
            'status' => 'new',
            'terms_accepted' => true,
            'personal_data_accepted' => true,
            'photo_usage_accepted' => false,
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
