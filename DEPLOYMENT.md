# 🚀 Инструкция по развертыванию Golden Models

## Быстрый старт для разработки

### 1. Установка зависимостей

```powershell
# Backend зависимости
composer install

# Frontend зависимости
npm install
```

### 2. Настройка окружения

```powershell
# Копируем файл окружения
Copy-Item .env.example .env

# Генерируем ключ приложения
php artisan key:generate
```

### 3. Настройка базы данных

Откройте файл `.env` и настройте параметры БД:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=golden_models
DB_USERNAME=root
DB_PASSWORD=your_password
```

Создайте базу данных:

```sql
CREATE DATABASE golden_models CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Запуск миграций

```powershell
# Выполнить миграции
php artisan migrate

# Или с пересозданием (ВНИМАНИЕ: удалит все данные!)
php artisan migrate:fresh
```

### 5. Создание символической ссылки для хранилища

```powershell
php artisan storage:link
```

### 6. Сборка frontend

```powershell
# Для разработки (с hot reload)
npm run dev

# Для продакшена (минификация)
npm run build
```

### 7. Запуск сервера разработки

```powershell
# Laravel сервер
php artisan serve
# Сайт будет доступен: http://localhost:8000
```

---

## 📋 Тестовые данные (опционально)

Создайте файл `database/seeders/TestDataSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ModelProfile;
use App\Models\BlogCategory;
use App\Models\BlogPost;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // Создание админа
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@goldenmodels.ru',
            'password' => bcrypt('password'),
        ]);

        // Создание тестовой модели
        $modelUser = User::create([
            'name' => 'Anna Ivanova',
            'email' => 'anna@example.com',
            'password' => bcrypt('password'),
        ]);

        ModelProfile::create([
            'user_id' => $modelUser->id,
            'first_name' => 'Анна',
            'last_name' => 'Иванова',
            'gender' => 'female',
            'age' => 22,
            'city' => 'Москва',
            'height' => 175,
            'weight' => 60,
            'bust' => 86,
            'waist' => 62,
            'hips' => 90,
            'eye_color' => 'Голубые',
            'hair_color' => 'Русый',
            'categories' => ['fashion', 'commercial'],
            'status' => 'active',
        ]);

        // Создание категорий блога
        $categories = [
            ['name' => 'Мода и стиль', 'slug' => 'fashion'],
            ['name' => 'Карьера модели', 'slug' => 'career'],
            ['name' => 'Новости агентства', 'slug' => 'news'],
        ];

        foreach ($categories as $cat) {
            BlogCategory::create($cat);
        }
    }
}
```

Запустите seeder:

```powershell
php artisan db:seed --class=TestDataSeeder
```

---

## 🔧 Настройка OSPanel (для Windows)

### 1. Настройка домена

1. Откройте OSPanel
2. Добавьте новый домен через меню
3. Имя домена: `goldenmodels.local`
4. Папка: `c:\ospanel\domains\agency\public`

### 2. Настройка PHP

В `.env` убедитесь:

```env
APP_URL=http://goldenmodels.local
```

### 3. Права доступа

Убедитесь, что папки имеют права на запись:
- `storage/`
- `bootstrap/cache/`

```powershell
# Если возникают проблемы с правами
icacls "storage" /grant Everyone:F /t
icacls "bootstrap/cache" /grant Everyone:F /t
```

---

## 🌐 Развертывание на production

### 1. Настройка .env для production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://goldenmodels.ru

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=production_db
DB_USERNAME=production_user
DB_PASSWORD=secure_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.yandex.ru
MAIL_PORT=465
MAIL_USERNAME=info@goldenmodels.ru
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=info@goldenmodels.ru
```

### 2. Оптимизация для production

```powershell
# Кеширование конфигурации
php artisan config:cache

# Кеширование маршрутов
php artisan route:cache

# Кеширование представлений
php artisan view:cache

# Оптимизация автозагрузки
composer install --optimize-autoloader --no-dev

# Сборка assets для production
npm run build
```

### 3. Настройка очередей (Queue)

В `.env`:

```env
QUEUE_CONNECTION=database
```

Создать таблицу для очередей:

```powershell
php artisan queue:table
php artisan migrate
```

Запустить worker:

```powershell
php artisan queue:work
```

Для автоматического запуска используйте Supervisor (Linux) или Task Scheduler (Windows).

### 4. Настройка Cron (для планировщика задач)

Linux:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Windows (Task Scheduler):
- Program: `php.exe`
- Arguments: `artisan schedule:run`
- Start in: `C:\ospanel\domains\agency`
- Trigger: Every 1 minute

### 5. Права доступа (Linux)

```bash
sudo chown -R www-data:www-data /path-to-project
sudo chmod -R 755 /path-to-project
sudo chmod -R 775 /path-to-project/storage
sudo chmod -R 775 /path-to-project/bootstrap/cache
```

### 6. Nginx конфигурация

```nginx
server {
    listen 80;
    server_name goldenmodels.ru www.goldenmodels.ru;
    root /var/www/agency/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 7. Apache конфигурация (.htaccess уже настроен)

Убедитесь, что включены модули:

```apache
a2enmod rewrite
a2enmod headers
```

---

## ✅ Чеклист развертывания

- [ ] Установлены все зависимости (composer, npm)
- [ ] Скопирован и настроен .env файл
- [ ] Создана база данных
- [ ] Выполнены миграции
- [ ] Создана символическая ссылка storage
- [ ] Собраны frontend assets
- [ ] Настроены права доступа к папкам
- [ ] Запущен Laravel сервер или настроен веб-сервер
- [ ] Тестовые данные загружены (опционально)
- [ ] Проверена работа всех страниц
- [ ] Настроена отправка email
- [ ] Настроены очереди (для production)
- [ ] Настроен планировщик задач (для production)

---

## 🔍 Проверка работы

После развертывания проверьте:

1. **Главная страница**: http://localhost:8000
2. **Каталог моделей**: http://localhost:8000/models
3. **Кастинг**: http://localhost:8000/casting
4. **Блог**: http://localhost:8000/blog
5. **Контакты**: http://localhost:8000/contacts

### Тестовые учетные данные:

**Модель:**
- Email: anna@example.com
- Password: password

**Админ:**
- Email: admin@goldenmodels.ru
- Password: password

---

## 🐛 Устранение проблем

### Ошибка 500

```powershell
# Посмотреть логи
Get-Content storage/logs/laravel.log -Tail 50

# Очистить кеш
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Проблемы с правами доступа

```powershell
# Windows
icacls "storage" /grant Everyone:F /t
icacls "bootstrap/cache" /grant Everyone:F /t
```

### Vite не собирает assets

```powershell
# Удалить node_modules и переустановить
Remove-Item -Recurse -Force node_modules
Remove-Item package-lock.json
npm install
npm run build
```

### База данных не подключается

1. Проверьте запущен ли MySQL
2. Проверьте правильность данных в .env
3. Проверьте существует ли база данных

```powershell
# Проверка подключения
php artisan tinker
> DB::connection()->getPdo();
```

---

## 📞 Поддержка

При возникновении проблем:
1. Проверьте логи: `storage/logs/laravel.log`
2. Проверьте версии PHP, Composer, Node.js
3. Убедитесь что все расширения PHP установлены
4. Проверьте документацию Laravel: https://laravel.com/docs

---

Успешного развертывания! 🚀
