# API Endpoints - Golden Models

## 🔐 Аутентификация

Все маршруты, требующие аутентификации, используют session-based auth через Laravel Auth.

---

## 📍 Публичные маршруты

### Главная страница
```
GET /
Описание: Главная страница сайта
Возвращает: resources/views/index.blade.php
```

### Каталог моделей
```
GET /models
Параметры (query):
  - category: string (fashion, commercial, fit, plus-size)
  - gender: string (male, female)
  - city: string
  - age_from: integer
  - age_to: integer
  - height_from: integer
  - height_to: integer
  - eye_color: string
  - hair_color: string
  
Описание: Список моделей с фильтрацией
Возвращает: Пагинированный список моделей (12 на страницу)
Контроллер: ModelsController@index
```

### Карточка модели
```
GET /model/{id}
Параметры (path):
  - id: integer (ID модели)
  
Описание: Детальная информация о модели с портфолио
Возвращает: Данные модели + 4 похожих модели
Контроллер: ModelsController@show
Побочный эффект: Увеличивает счетчик просмотров
```

### Бронирование модели
```
POST /models/{id}/book
Параметры (path):
  - id: integer (ID модели)
  
Параметры (body):
  - client_name: string (required, max:255)
  - client_phone: string (required, max:20)
  - client_email: string (required, email)
  - company_name: string (optional, max:255)
  - event_type: string (required)
  - event_description: string (required, max:1000)
  - event_date: date (optional, after:today)
  - event_time: time (optional, H:i)
  - event_location: string (optional, max:255)
  - duration_hours: integer (optional, 1-24)
  - budget: decimal (optional, min:0)
  
Описание: Создание заявки на бронирование модели
Возвращает: JSON {success: true, message: '...'} или redirect
Контроллер: BookingController@store
```

### Кастинг - форма
```
GET /casting
Описание: Страница подачи заявки на кастинг
Возвращает: 12-шаговая форма кастинга
Контроллер: CastingController@index
```

### Кастинг - отправка
```
POST /casting
Параметры (body):
  Личная информация:
    - first_name: string (required, max:255)
    - last_name: string (required, max:255)
    - patronymic: string (optional, max:255)
    - gender: enum (required, male|female)
    - birth_date: date (required, before:today)
    - city: string (required, max:255)
    
  Контакты:
    - phone: string (required, max:20)
    - email: string (required, email)
    - telegram: string (optional, max:255)
    - instagram: string (optional, max:255)
    
  Параметры:
    - height: integer (required, 150-220)
    - weight: integer (required, 40-150)
    - bust: integer (optional, 60-150)
    - waist: integer (optional, 50-120)
    - hips: integer (optional, 60-150)
    - shoe_size: integer (required, 34-48)
    - clothing_size: string (required, max:10)
    
  Внешность:
    - eye_color: string (required, max:50)
    - hair_color: string (required, max:50)
    - skin_tone: string (required, max:50)
    
  Опыт:
    - has_experience: boolean
    - experience_description: string (optional)
    - has_modeling_school: boolean
    - school_name: string (optional, max:255)
    - languages: array (optional)
    - special_skills: array (optional)
    
  Фотографии:
    - photo_portrait: file (required, image, max:5MB)
    - photo_full_body: file (required, image, max:5MB)
    - photo_profile: file (required, image, max:5MB)
    - photo_additional_1: file (optional, image, max:5MB)
    - photo_additional_2: file (optional, image, max:5MB)
    
  Дополнительно:
    - about: string (optional, max:1000)
    - motivation: string (optional, max:1000)
    - categories_interest: array (optional)
    
  Согласия:
    - terms_accepted: boolean (required)
    - personal_data_accepted: boolean (required)
    - photo_usage_accepted: boolean (required)

Описание: Отправка заявки на кастинг
Возвращает: Redirect на /casting/thanks
Контроллер: CastingController@store
```

### Кастинг - благодарность
```
GET /casting/thanks
Описание: Страница благодарности после отправки заявки
Возвращает: resources/views/casting/thanks.blade.php
Контроллер: CastingController@thanks
```

### О нас
```
GET /about
Описание: Страница о компании
Возвращает: resources/views/about.blade.php
```

### Блог - список
```
GET /blog
Параметры (query):
  - category: string (slug категории)
  - search: string (поиск по title, content, excerpt)
  - page: integer (номер страницы)
  
Описание: Список статей блога с фильтрацией
Возвращает: Пагинированный список статей (12 на страницу)
Контроллер: BlogController@index
```

### Блог - статья
```
GET /blog/{slug}
Параметры (path):
  - slug: string (slug статьи)
  
Описание: Детальная страница статьи
Возвращает: Статья + 3 похожих статьи из той же категории
Контроллер: BlogController@show
Побочный эффект: Увеличивает счетчик просмотров
```

### Контакты
```
GET /contacts
Описание: Страница контактов
Возвращает: resources/views/contacts.blade.php
```

### Отправка сообщения
```
POST /contact
Параметры (body):
  - name: string (required)
  - phone: string (required)
  - email: string (required, email)
  - subject: string (required)
  - message: string (required)
  
Описание: Отправка сообщения через форму обратной связи
Возвращает: Redirect back с сообщением success
```

---

## 🔒 Защищенные маршруты (требуют авторизации)

### Профиль - просмотр
```
GET /profile
Middleware: auth
Описание: Личный кабинет модели
Возвращает: Профиль текущего пользователя
Контроллер: ProfileController@index
```

### Профиль - обновление
```
POST /profile
Middleware: auth
Параметры (body):
  - first_name: string (required, max:255)
  - last_name: string (required, max:255)
  - age: integer (required, 16-60)
  - height: integer (required, 150-220)
  - weight: integer (required, 40-150)
  - bust: integer (optional)
  - waist: integer (optional)
  - hips: integer (optional)
  - eye_color: string (required)
  - hair_color: string (required)
  - city: string (required, max:255)
  - bio: string (optional, max:1000)
  - instagram: string (optional, max:255)
  - telegram: string (optional, max:255)
  - email: string (required, email, unique)
  - phone: string (required, max:20)
  - current_password: string (required_with:new_password)
  - new_password: string (min:8, confirmed)
  
Описание: Обновление данных профиля модели
Возвращает: Redirect back с сообщением success
Контроллер: ProfileController@update
```

### Загрузка фотографий
```
POST /profile/upload-photos
Middleware: auth
Параметры (body):
  - photos: array (required, max:10 files)
  - photos.*: file (image, mimes:jpeg,png,jpg, max:5MB)
  
Описание: Загрузка фотографий в портфолио
Возвращает: Redirect back с сообщением success
Контроллер: ProfileController@uploadPhotos
```

### Удаление фотографии
```
DELETE /profile/photos/{index}
Middleware: auth
Параметры (path):
  - index: integer (индекс фото в массиве)
  
Описание: Удаление фотографии из портфолио
Возвращает: Redirect back с сообщением success
Контроллер: ProfileController@deletePhoto
```

---

## 👨‍💼 Административные маршруты

### Админ панель
```
GET /admin/dashboard
Middleware: auth, admin
Описание: Главная страница админ панели
Возвращает: resources/views/admin/dashboard.blade.php
```

---

## 🔑 Маршруты аутентификации (Laravel Breeze/UI)

```
GET /login - Форма входа
POST /login - Вход в систему
POST /logout - Выход из системы
GET /register - Форма регистрации
POST /register - Регистрация
GET /password/reset - Форма восстановления пароля
POST /password/email - Отправка ссылки восстановления
GET /password/reset/{token} - Форма установки нового пароля
POST /password/reset - Установка нового пароля
```

---

## 📊 Коды ответов

- **200** - Успешный запрос
- **302** - Redirect (после POST запросов)
- **401** - Unauthorized (требуется авторизация)
- **403** - Forbidden (недостаточно прав)
- **404** - Not Found (страница/ресурс не найден)
- **422** - Validation Error (ошибки валидации)
- **500** - Server Error (внутренняя ошибка сервера)

---

## 🔄 AJAX запросы

### Фильтрация моделей
```javascript
fetch('/models?' + new URLSearchParams({
    category: 'fashion',
    gender: 'female',
    age_from: 18,
    age_to: 25
}), {
    headers: {
        'X-Requested-With': 'XMLHttpRequest'
    }
})
```

### Бронирование модели
```javascript
fetch('/models/1/book', {
    method: 'POST',
    body: new FormData(form),
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
```

---

## 📝 Примечания

- Все POST/PUT/DELETE запросы требуют CSRF токен
- Формат дат: YYYY-MM-DD
- Формат времени: HH:MM
- Изображения принимаются: jpeg, png, jpg
- Максимальный размер изображения: 5MB
- Пагинация: 12 элементов на странице
- Session lifetime: 120 минут (по умолчанию)

---

## 🚀 Расширение API

Для добавления REST API:

1. Установить Laravel Sanctum:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

2. Создать API routes в `routes/api.php`
3. Добавить API Resources для моделей
4. Настроить CORS в `config/cors.php`

---

Обновлено: 15.01.2024
Версия API: 1.0
