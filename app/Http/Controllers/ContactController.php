<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Пожалуйста, укажите ваше имя',
            'phone.required' => 'Пожалуйста, укажите ваш телефон',
            'message.required' => 'Пожалуйста, введите сообщение',
        ]);

        try {
            // Логирование обращения
            Log::info('New contact form submission', $validated);

            // Здесь можно добавить отправку email или уведомления в Telegram
            // Mail::to('info@golden-models.ru')->send(new ContactFormMail($validated));
            
            // Или сохранение в базу данных
            // ContactMessage::create($validated);

            return back()->with('success', 'Спасибо за обращение! Мы свяжемся с вами в ближайшее время.');
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return back()->with('error', 'Произошла ошибка. Пожалуйста, попробуйте позже или свяжитесь с нами по телефону.');
        }
    }
}
