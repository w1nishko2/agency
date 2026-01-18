<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\ModelsController;
use App\Http\Controllers\CastingController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\YandexAuthController;
use App\Http\Controllers\Auth\VkAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Главная страница
Route::get('/', function () {
    $latestPosts = \App\Models\BlogPost::published()
        ->orderBy('published_at', 'desc')
        ->limit(3)
        ->get();
    
    return view('index', compact('latestPosts'));
})->name('home');

// Модели
Route::get('/models', [ModelsController::class, 'index'])->name('models.index');
Route::get('/model/{id}', [ModelsController::class, 'show'])->name('models.show');
Route::post('/models/{id}/book', [BookingController::class, 'store'])->name('models.book')->middleware('throttle:5,1');

// Регистрация моделью
Route::get('/register-model', [ModelsController::class, 'registerForm'])->name('models.register');
Route::post('/register-model', [ModelsController::class, 'registerSubmit'])->name('models.register.submit');

// Кастинг
Route::get('/casting', [CastingController::class, 'index'])->name('casting.index');
Route::post('/casting', [CastingController::class, 'store'])->name('casting.submit');
Route::get('/casting/thanks', [CastingController::class, 'thanks'])->name('casting.thanks');

// О нас
Route::get('/about', function () {
    return view('about');
})->name('about');

// Блог
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Контакты
Route::get('/contacts', function () {
    return view('contacts');
})->name('contacts');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Авторизация
Auth::routes();

// Yandex OAuth
Route::get('/auth/yandex', [YandexAuthController::class, 'redirectToYandex'])->name('auth.yandex');
Route::get('/auth/yandex/callback', [YandexAuthController::class, 'handleYandexCallback'])->name('auth.yandex.callback');

// VK OAuth
Route::get('/auth/vk', [VkAuthController::class, 'redirectToVk'])->name('auth.vk');
Route::get('/auth/vk/callback', [VkAuthController::class, 'handleVkCallback']);
Route::post('/auth/vk/callback', [VkAuthController::class, 'handleVkCallback'])->name('auth.vk.callback');

// Профиль модели (защищенные маршруты)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photos', [ProfileController::class, 'uploadPhotos'])->name('profile.upload-photos');
    Route::delete('/profile/photos/{index}', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    Route::post('/profile/telegram/generate-key', [ProfileController::class, 'generateTelegramKey'])->name('profile.telegram.generate-key');
    Route::post('/profile/telegram/unlink', [ProfileController::class, 'unlinkTelegram'])->name('profile.telegram.unlink');
});

// Админка
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Модерация моделей
    Route::get('/models', [\App\Http\Controllers\Admin\ModelAdminController::class, 'index'])->name('models.index');
    Route::get('/models/{id}', [\App\Http\Controllers\Admin\ModelAdminController::class, 'show'])->name('models.detail');
    Route::patch('/models/{id}/approve', [\App\Http\Controllers\Admin\ModelAdminController::class, 'approve'])->name('models.approve');
    Route::patch('/models/{id}/reject', [\App\Http\Controllers\Admin\ModelAdminController::class, 'reject'])->name('models.reject');
    Route::patch('/models/{id}/deactivate', [\App\Http\Controllers\Admin\ModelAdminController::class, 'deactivate'])->name('models.deactivate');
    Route::get('/models/{id}/edit', [\App\Http\Controllers\Admin\ModelAdminController::class, 'edit'])->name('models.edit');
    Route::put('/models/{id}', [\App\Http\Controllers\Admin\ModelAdminController::class, 'update'])->name('models.update');
    Route::delete('/models/{id}', [\App\Http\Controllers\Admin\ModelAdminController::class, 'destroy'])->name('models.destroy');
    
    // Кастинги
    Route::get('/castings', [\App\Http\Controllers\Admin\CastingAdminController::class, 'index'])->name('castings.index');
    Route::get('/castings/{id}', [\App\Http\Controllers\Admin\CastingAdminController::class, 'show'])->name('castings.show');
    Route::get('/castings/{id}/find-models', [\App\Http\Controllers\Admin\CastingAdminController::class, 'findModels'])->name('castings.find-models');
    Route::post('/castings/{id}/assign-models', [\App\Http\Controllers\Admin\CastingAdminController::class, 'assignModels'])->name('castings.assign-models');
    Route::delete('/castings/{castingId}/models/{modelId}', [\App\Http\Controllers\Admin\CastingAdminController::class, 'removeModel'])->name('castings.remove-model');
    Route::patch('/castings/{id}/approve', [\App\Http\Controllers\Admin\CastingAdminController::class, 'approve'])->name('castings.approve');
    Route::patch('/castings/{id}/reject', [\App\Http\Controllers\Admin\CastingAdminController::class, 'reject'])->name('castings.reject');
    Route::delete('/castings/{id}', [\App\Http\Controllers\Admin\CastingAdminController::class, 'destroy'])->name('castings.destroy');
    
    // Бронирования
    Route::get('/bookings', [\App\Http\Controllers\Admin\BookingAdminController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [\App\Http\Controllers\Admin\BookingAdminController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{id}/approve', [\App\Http\Controllers\Admin\BookingAdminController::class, 'approve'])->name('bookings.approve');
    Route::patch('/bookings/{id}/reject', [\App\Http\Controllers\Admin\BookingAdminController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{id}/complete', [\App\Http\Controllers\Admin\BookingAdminController::class, 'complete'])->name('bookings.complete');
    Route::delete('/bookings/{id}', [\App\Http\Controllers\Admin\BookingAdminController::class, 'destroy'])->name('bookings.destroy');
    
    // Блог
    Route::get('/blog', [\App\Http\Controllers\Admin\BlogAdminController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [\App\Http\Controllers\Admin\BlogAdminController::class, 'create'])->name('blog.create');
    Route::post('/blog', [\App\Http\Controllers\Admin\BlogAdminController::class, 'store'])->name('blog.store');
    Route::get('/blog/{id}/edit', [\App\Http\Controllers\Admin\BlogAdminController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{id}', [\App\Http\Controllers\Admin\BlogAdminController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{id}', [\App\Http\Controllers\Admin\BlogAdminController::class, 'destroy'])->name('blog.destroy');

    // Telegram Bot
    Route::get('/telegram-bot', [\App\Http\Controllers\Admin\TelegramBotController::class, 'index'])->name('telegram-bot.index');
    Route::put('/telegram-bot', [\App\Http\Controllers\Admin\TelegramBotController::class, 'update'])->name('telegram-bot.update');
    Route::post('/telegram-bot/test', [\App\Http\Controllers\Admin\TelegramBotController::class, 'testConnection'])->name('telegram-bot.test');
    Route::post('/telegram-bot/webhook', [\App\Http\Controllers\Admin\TelegramBotController::class, 'setWebhook'])->name('telegram-bot.webhook.set');
    Route::delete('/telegram-bot/webhook', [\App\Http\Controllers\Admin\TelegramBotController::class, 'deleteWebhook'])->name('telegram-bot.webhook.delete');
    Route::get('/telegram-bot/webhook/info', [\App\Http\Controllers\Admin\TelegramBotController::class, 'getWebhookInfo'])->name('telegram-bot.webhook.info');
});

if (app()->environment('production')) {
    URL::forceScheme('https');
}