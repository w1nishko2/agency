<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:16|max:60',
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
        if ($model) {
            $model->update($validated);
        }

        // Обновление email
        User::where('id', $user->id)->update([
            'email' => $validated['email'],
        ]);

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
}
