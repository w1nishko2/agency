<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очищаем таблицу настроек
        SiteSetting::truncate();

        $settings = [
            // Основные контакты
            [
                'key' => 'contact_phone',
                'value' => '+7 (495) 123-45-67',
                'type' => 'phone',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_phone_2',
                'value' => '+7 (926) 987-65-43',
                'type' => 'phone',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@golden-models.ru',
                'type' => 'email',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_email_booking',
                'value' => 'booking@golden-models.ru',
                'type' => 'email',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => 'г. Москва, ул. Тверская, д. 12, офис 501',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_working_hours',
                'value' => 'Пн-Пт: 10:00 - 19:00, Сб-Вс: выходной',
                'type' => 'text',
                'group' => 'contact',
            ],

            // Социальные сети
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/golden_models',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_vk',
                'value' => 'https://vk.com/golden_models',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_telegram',
                'value' => 'https://t.me/golden_models',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/@golden_models',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/golden.models',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_tiktok',
                'value' => 'https://tiktok.com/@golden_models',
                'type' => 'text',
                'group' => 'social',
            ],

            // Информация о компании
            [
                'key' => 'company_name',
                'value' => 'Golden Models',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'company_full_name',
                'value' => 'ООО "Голден Моделс"',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'company_inn',
                'value' => '7701234567',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'company_ogrn',
                'value' => '1234567890123',
                'type' => 'text',
                'group' => 'general',
            ],
            [
                'key' => 'company_description',
                'value' => 'Профессиональное модельное агентство полного цикла. Мы специализируемся на поиске, обучении и продвижении моделей для работы в России и за рубежом.',
                'type' => 'textarea',
                'group' => 'general',
            ],
            [
                'key' => 'company_year_founded',
                'value' => '2014',
                'type' => 'number',
                'group' => 'general',
            ],

            // SEO
            [
                'key' => 'site_title',
                'value' => 'Golden Models - Модельное агентство в Москве',
                'type' => 'text',
                'group' => 'seo',
            ],
            [
                'key' => 'site_description',
                'value' => 'Модельное агентство Golden Models - кастинг, подбор и продвижение моделей. 10+ лет опыта, более 3000 моделей. Профессиональный подход и гарантия качества.',
                'type' => 'textarea',
                'group' => 'seo',
            ],
            [
                'key' => 'site_keywords',
                'value' => 'модельное агентство, кастинг моделей, стать моделью, модели Москва, профессиональные модели',
                'type' => 'text',
                'group' => 'seo',
            ],

            // Мессенджеры
            [
                'key' => 'messenger_whatsapp',
                'value' => '+79269876543',
                'type' => 'phone',
                'group' => 'messenger',
            ],
            [
                'key' => 'messenger_telegram',
                'value' => '+79269876543',
                'type' => 'phone',
                'group' => 'messenger',
            ],
            [
                'key' => 'messenger_viber',
                'value' => '+79269876543',
                'type' => 'phone',
                'group' => 'messenger',
            ],

            // Дополнительная информация
            [
                'key' => 'contact_map_latitude',
                'value' => '55.755814',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'contact_map_longitude',
                'value' => '37.617635',
                'type' => 'text',
                'group' => 'contact',
            ],
            [
                'key' => 'models_count',
                'value' => '3000',
                'type' => 'number',
                'group' => 'stats',
            ],
            [
                'key' => 'successful_projects',
                'value' => '500',
                'type' => 'number',
                'group' => 'stats',
            ],
            [
                'key' => 'partner_brands',
                'value' => '150',
                'type' => 'number',
                'group' => 'stats',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }

        $this->command->info('✅ Настройки сайта успешно добавлены!');
    }
}
