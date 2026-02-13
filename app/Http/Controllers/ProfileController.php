<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Если админ - перенаправляем на админку
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        $model = $user->modelProfile;
        
        return view('profile.index', compact('user', 'model'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $model = $user->modelProfile;

        // Если это модель - валидируем все поля
        if ($model) {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'age' => 'required|integer',
                'height' => 'required|integer|min:150|max:220',
                'weight' => 'required|integer|min:40|max:150',
                'bust' => 'nullable|integer',
                'waist' => 'nullable|integer',
                'hips' => 'nullable|integer',
                'eye_color' => 'required|string',
                'hair_color' => 'required|string',
                'city' => 'required|string|max:255',
                'bio' => 'nullable|string|max:1000',
                'instagram' => 'nullable|string|max:255',
                'telegram' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'required|string|max:20',
                'current_password' => 'nullable|required_with:new_password',
                'new_password' => 'nullable|min:8|confirmed',
            ]);

            // Обновление модели
            $model->update($validated);
        } else {
            // Для обычных пользователей - только базовые поля
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'current_password' => 'nullable|required_with:new_password',
                'new_password' => 'nullable|min:8|confirmed',
            ]);
        }

        // Обновление email и имени пользователя
        $updateData = ['email' => $validated['email']];
        if (isset($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }
        User::where('id', $user->id)->update($updateData);

        // Смена пароля
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Неверный текущий пароль']);
            }
            User::where('id', $user->id)->update(['password' => Hash::make($request->new_password)]);
        }

        return back()->with('success', 'Профиль успешно обновлён');
    }

    public function uploadPhotos(Request $request)
    {
        $request->validate([
            'photos' => 'required|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $user = Auth::user();
        $model = $user->modelProfile;

        if (!$model) {
            return back()->withErrors(['error' => 'Профиль модели не найден']);
        }

        $photos = $model->portfolio_photos ?? [];

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('model_portfolios', 'public');
            $photos[] = $path;
        }

        $model->update(['portfolio_photos' => $photos]);

        return back()->with('success', 'Фотографии успешно загружены');
    }

    public function deletePhoto(Request $request, $photoIndex)
    {
        $user = Auth::user();
        $model = $user->modelProfile;

        if (!$model) {
            return back()->withErrors(['error' => 'Профиль модели не найден']);
        }

        $photos = $model->portfolio_photos ?? [];
        
        if (isset($photos[$photoIndex])) {
            Storage::disk('public')->delete($photos[$photoIndex]);
            unset($photos[$photoIndex]);
            $model->update(['portfolio_photos' => array_values($photos)]);
        }

        return back()->with('success', 'Фотография удалена');
    }

    /**
     * Генерация ключа для привязки Telegram
     */
    public function generateTelegramKey()
    {
        $user = Auth::user();
        
        // Генерируем уникальный ключ
        $key = strtoupper(Str::random(8));
        
        // Сохраняем ключ и время истечения (15 минут)
        $user->telegram_bind_key = $key;
        $user->telegram_bind_key_expires_at = now()->addMinutes(15);
        $user->save();
        
        return back()->with('success', 'Ключ для привязки сгенерирован. Отправьте его боту в течение 15 минут.');
    }

    /**
     * Отвязка Telegram аккаунта
     */
    public function unlinkTelegram()
    {
        $user = Auth::user();
        
        $user->telegram_id = null;
        $user->telegram_username = null;
        $user->telegram_bind_key = null;
        $user->telegram_bind_key_expires_at = null;
        $user->save();
        
        return back()->with('success', 'Telegram аккаунт отвязан');
    }
}
