<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsAdminController extends Controller
{
    /**
     * Отображение страницы настроек
     */
    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        // Убеждаемся что все группы существуют
        if (!$settings->has('contact')) {
            $settings->put('contact', collect());
        }
        if (!$settings->has('social')) {
            $settings->put('social', collect());
        }
        if (!$settings->has('general')) {
            $settings->put('general', collect());
        }
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Обновление настроек
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string',
        ]);

        foreach ($request->input('settings', []) as $key => $value) {
            $setting = SiteSetting::where('key', $key)->first();

            if ($setting) {
                // Обработка загрузки изображения для настроек типа 'image'
                if ($setting->type === 'image' && $request->hasFile("settings.{$key}")) {
                    // Удаляем старое изображение
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    $imagePath = $request->file("settings.{$key}")->store('settings', 'public');
                    $value = $imagePath;
                }

                $setting->value = $value;
                $setting->save();
            }
        }

        // Очищаем кеш настроек
        SiteSetting::clearCache();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Настройки успешно обновлены!');
    }

    /**
     * Удаление изображения настройки
     */
    public function deleteImage($key)
    {
        $setting = SiteSetting::where('key', $key)->first();

        if ($setting && $setting->type === 'image' && $setting->value) {
            if (Storage::disk('public')->exists($setting->value)) {
                Storage::disk('public')->delete($setting->value);
            }
            $setting->value = null;
            $setting->save();

            SiteSetting::clearCache();
        }

        return response()->json(['success' => true]);
    }
}
