<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BlogCategory;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Новости',
                'slug' => 'news',
                'description' => 'Новости модельного агентства и индустрии моды',
            ],
            [
                'name' => 'Советы моделям',
                'slug' => 'model-tips',
                'description' => 'Полезные советы и рекомендации для начинающих и опытных моделей',
            ],
            [
                'name' => 'Фотосессии',
                'slug' => 'photoshoots',
                'description' => 'Обзоры фотосессий, закулисье и истории съемок',
            ],
            [
                'name' => 'Индустрия моды',
                'slug' => 'fashion-industry',
                'description' => 'Тренды, события и новости мира моды',
            ],
            [
                'name' => 'Кастинги',
                'slug' => 'castings',
                'description' => 'Информация о предстоящих кастингах и проектах',
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Создано ' . count($categories) . ' категорий блога');
    }
}
