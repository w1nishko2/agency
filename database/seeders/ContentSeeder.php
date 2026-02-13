<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\SiteSetting;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание страниц
        $pages = [
            [
                'title' => 'Главная страница',
                'slug' => '',
                'description' => 'Добро пожаловать в наше модельное агентство! Мы предоставляем профессиональные услуги в сфере модельного бизнеса.',
                'meta_title' => 'Модельное агентство - Главная',
                'meta_description' => 'Профессиональное модельное агентство. Кастинги, фотосессии, работа для моделей.',
                'is_active' => true,
            ],
            [
                'title' => 'О нас',
                'slug' => 'about',
                'description' => 'Наше агентство работает на рынке модельных услуг уже много лет. Мы гордимся нашими моделями и их достижениями.',
                'meta_title' => 'О нас - Модельное агентство',
                'meta_description' => 'История и миссия нашего модельного агентства. Узнайте больше о нашей команде и успехах.',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }

        // Создание настроек контактов
        $contactSettings = [
            ['key' => 'contact_phone', 'value' => '+7 905 717 30 12', 'type' => 'phone', 'group' => 'contact'],
            ['key' => 'contact_phone_models', 'value' => '+7 905 717 30 12', 'type' => 'phone', 'group' => 'contact'],
            ['key' => 'contact_phone_partners', 'value' => '+7 906 729 97 17', 'type' => 'phone', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'gma@golden-models.ru', 'type' => 'email', 'group' => 'contact'],
            ['key' => 'contact_email_models', 'value' => 'casting@golden-models.ru', 'type' => 'email', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '+79057173012', 'type' => 'phone', 'group' => 'contact'],
            ['key' => 'contact_telegram', 'value' => '@goldenmodels', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Москва, м. Цветной Бульвар, ул. Цветной Бульвар, д.19 строение 5, 4 этаж', 'type' => 'textarea', 'group' => 'contact'],
            ['key' => 'contact_working_hours', 'value' => 'Пн-Пт: 10:00-20:00, Сб-Вс: 11:00-18:00', 'type' => 'text', 'group' => 'contact'],
        ];

        // Создание настроек социальных сетей
        $socialSettings = [
            ['key' => 'social_vk', 'value' => 'https://vk.com/goldenmodels', 'type' => 'url', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/goldenmodels', 'type' => 'url', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@goldenmodels', 'type' => 'url', 'group' => 'social'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/goldenmodels', 'type' => 'url', 'group' => 'social'],
            ['key' => 'social_telegram', 'value' => 'https://t.me/goldenmodels', 'type' => 'url', 'group' => 'social'],
        ];

        // Создание общих настроек
        $generalSettings = [
            ['key' => 'site_name', 'value' => 'Модельное агентство', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Профессиональное модельное агентство', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'site_keywords', 'value' => 'модели, агентство, кастинг, фотосессии', 'type' => 'text', 'group' => 'general'],
        ];

        $allSettings = array_merge($contactSettings, $socialSettings, $generalSettings);

        foreach ($allSettings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Страницы и настройки успешно созданы!');
    }
}
