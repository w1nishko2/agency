# Настройка администратора

## 1. Запустить миграцию для добавления роли в таблицу users

```bash
php artisan migrate
```

Это добавит поле `role` (enum: 'user', 'admin') в таблицу `users`.

## 2. Создать администратора

```bash
php artisan db:seed --class=AdminUserSeeder
```

Это создаст пользователя-администратора:
- **Email:** admin@agency.local
- **Пароль:** admin123

⚠️ **ВАЖНО:** Измените пароль после первого входа!

## 3. Как назначить админа существующему пользователю

### Через Tinker:
```bash
php artisan tinker
```

```php
$user = User::find(1); // или User::where('email', 'user@example.com')->first()
$user->role = 'admin';
$user->save();
```

### Через SQL:
```sql
UPDATE users SET role = 'admin' WHERE email = 'your@email.com';
```

## Доступ к админ-панели

После назначения роли админа:
- Перейдите на https://agency/admin/dashboard
- Панель модерации моделей: https://agency/admin/models

## Проверка прав

Middleware `IsAdmin` автоматически проверяет:
1. Авторизован ли пользователь
2. Имеет ли роль 'admin'

Если нет - доступ запрещен (403).

## Методы в модели User

```php
$user->isAdmin()  // true/false
$user->isUser()   // true/false
```
