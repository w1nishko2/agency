<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ModelProfile;
use App\Models\BlogCategory;
use App\Models\BlogPost;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Создание администратора
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@goldenmodels.ru',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        echo "✓ Админ создан: admin@goldenmodels.ru / password\n";

        // Создание тестовых моделей
        $models = [
            [
                'name' => 'Anna Ivanova',
                'email' => 'anna@example.com',
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
                'bio' => 'Профессиональная модель с опытом работы более 5 лет в fashion-индустрии.',
                'experience_years' => 5,
            ],
            [
                'name' => 'Maria Petrova',
                'email' => 'maria@example.com',
                'first_name' => 'Мария',
                'last_name' => 'Петрова',
                'gender' => 'female',
                'age' => 19,
                'city' => 'Санкт-Петербург',
                'height' => 178,
                'weight' => 58,
                'bust' => 84,
                'waist' => 60,
                'hips' => 88,
                'eye_color' => 'Карие',
                'hair_color' => 'Блонд',
                'categories' => ['fashion', 'runway'],
                'bio' => 'Молодая и перспективная модель, участница международных показов.',
                'experience_years' => 2,
            ],
            [
                'name' => 'Dmitry Sokolov',
                'email' => 'dmitry@example.com',
                'first_name' => 'Дмитрий',
                'last_name' => 'Соколов',
                'gender' => 'male',
                'age' => 25,
                'city' => 'Москва',
                'height' => 188,
                'weight' => 82,
                'bust' => 98,
                'waist' => 76,
                'hips' => 94,
                'eye_color' => 'Серые',
                'hair_color' => 'Каштановый',
                'categories' => ['commercial', 'fit'],
                'bio' => 'Модель и фитнес-тренер. Специализируюсь на рекламе спортивных брендов.',
                'experience_years' => 3,
            ],
            [
                'name' => 'Elena Volkova',
                'email' => 'elena@example.com',
                'first_name' => 'Елена',
                'last_name' => 'Волкова',
                'gender' => 'female',
                'age' => 21,
                'city' => 'Екатеринбург',
                'height' => 172,
                'weight' => 55,
                'bust' => 82,
                'waist' => 58,
                'hips' => 86,
                'eye_color' => 'Зелёные',
                'hair_color' => 'Рыжий',
                'categories' => ['commercial', 'beauty'],
                'bio' => 'Специализируюсь на рекламе косметики и парфюмерии.',
                'experience_years' => 4,
            ],
            [
                'name' => 'Victoria Kozlova',
                'email' => 'victoria@example.com',
                'first_name' => 'Виктория',
                'last_name' => 'Козлова',
                'gender' => 'female',
                'age' => 20,
                'city' => 'Казань',
                'height' => 176,
                'weight' => 59,
                'bust' => 85,
                'waist' => 61,
                'hips' => 89,
                'eye_color' => 'Голубые',
                'hair_color' => 'Русый',
                'categories' => ['fashion'],
                'bio' => 'Победительница конкурса "Мисс Татарстан 2023".',
                'experience_years' => 1,
            ],
        ];

        foreach ($models as $modelData) {
            $user = User::create([
                'name' => $modelData['name'],
                'email' => $modelData['email'],
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            ModelProfile::create([
                'user_id' => $user->id,
                'first_name' => $modelData['first_name'],
                'last_name' => $modelData['last_name'],
                'gender' => $modelData['gender'],
                'age' => $modelData['age'],
                'city' => $modelData['city'],
                'height' => $modelData['height'],
                'weight' => $modelData['weight'],
                'bust' => $modelData['bust'],
                'waist' => $modelData['waist'],
                'hips' => $modelData['hips'],
                'eye_color' => $modelData['eye_color'],
                'hair_color' => $modelData['hair_color'],
                'categories' => $modelData['categories'],
                'bio' => $modelData['bio'],
                'experience_years' => $modelData['experience_years'],
                'status' => 'active',
                'is_featured' => rand(0, 1) == 1,
            ]);
        }

        echo "✓ Создано " . count($models) . " моделей\n";

        // Создание категорий блога
        $categories = [
            [
                'name' => 'Мода и стиль',
                'slug' => 'fashion',
                'description' => 'Последние тренды модной индустрии',
            ],
            [
                'name' => 'Карьера модели',
                'slug' => 'career',
                'description' => 'Советы и рекомендации для начинающих моделей',
            ],
            [
                'name' => 'Новости агентства',
                'slug' => 'news',
                'description' => 'Актуальные новости Golden Models',
            ],
            [
                'name' => 'Здоровье и красота',
                'slug' => 'beauty',
                'description' => 'Уход за собой и поддержание формы',
            ],
            [
                'name' => 'Фотосессии',
                'slug' => 'photoshoot',
                'description' => 'За кулисами наших съёмок',
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $category) {
            $createdCategories[] = BlogCategory::create($category);
        }

        echo "✓ Создано " . count($categories) . " категорий блога\n";

        // Создание статей блога
        $posts = [
            [
                'title' => 'Как подготовиться к первой фотосессии',
                'slug' => 'kak-podgotovitsya-k-pervoj-fotosessii',
                'category_id' => 2,
                'excerpt' => 'Полное руководство для начинающих моделей: что взять с собой, как подготовить кожу и волосы, психологическая подготовка.',
                'content' => '<p>Первая фотосессия — это всегда волнительно. В этой статье мы собрали все необходимые рекомендации для успешного дебюта.</p><h2>Подготовка кожи и волос</h2><p>За неделю до съёмки начните особенно тщательно ухаживать за кожей лица и тела. Пейте много воды, используйте увлажняющие кремы...</p>',
                'tags' => ['фотосессия', 'советы', 'новичкам'],
                'read_time' => 5,
            ],
            [
                'title' => 'Топ-5 трендов модного сезона 2024',
                'slug' => 'top-5-trendov-modnogo-sezona-2024',
                'category_id' => 1,
                'excerpt' => 'Обзор главных трендов года от ведущих модных домов: цвета, фасоны, аксессуары.',
                'content' => '<p>Модный сезон 2024 обещает быть ярким и разнообразным. Мы проанализировали коллекции ведущих дизайнеров.</p><h2>Пастельные оттенки</h2><p>Нежные лавандовые, мятные и персиковые тона доминируют на подиумах...</p>',
                'tags' => ['мода', 'тренды', '2024'],
                'read_time' => 7,
            ],
            [
                'title' => 'Golden Models на Неделе моды в Милане',
                'slug' => 'golden-models-na-nedele-mody-v-milane',
                'category_id' => 3,
                'excerpt' => 'Наши модели приняли участие в показах на одной из главных модных площадок мира.',
                'content' => '<p>Мы рады сообщить, что три наших модели были приглашены на Неделю моды в Милане.</p><h2>Яркие моменты</h2><p>Анна Иванова открывала показ известного итальянского бренда...</p>',
                'tags' => ['новости', 'милан', 'неделя моды'],
                'read_time' => 4,
            ],
            [
                'title' => 'Правильное питание для моделей',
                'slug' => 'pravilnoe-pitanie-dlya-modelej',
                'category_id' => 4,
                'excerpt' => 'Как питаться, чтобы оставаться в форме и сохранять здоровье. Советы от профессионального диетолога.',
                'content' => '<p>Правильное питание — основа успешной карьеры модели. Важно не только контролировать вес, но и заботиться о здоровье.</p><h2>Основные принципы</h2><p>Сбалансированный рацион должен включать белки, жиры и углеводы...</p>',
                'tags' => ['здоровье', 'питание', 'диета'],
                'read_time' => 6,
            ],
            [
                'title' => 'За кулисами рекламной кампании',
                'slug' => 'za-kulisami-reklamnoj-kampanii',
                'category_id' => 5,
                'excerpt' => 'Фоторепортаж со съёмок новой рекламной кампании известного бренда одежды.',
                'content' => '<p>Делимся с вами атмосферой съёмочной площадки и рассказываем о работе над крупным рекламным проектом.</p><h2>Команда проекта</h2><p>В проекте участвовали более 30 специалистов: фотограф, стилисты, визажисты...</p>',
                'tags' => ['фотосессия', 'реклама', 'backstage'],
                'read_time' => 5,
            ],
        ];

        foreach ($posts as $postData) {
            BlogPost::create([
                'category_id' => $postData['category_id'],
                'author_id' => $admin->id,
                'title' => $postData['title'],
                'slug' => $postData['slug'],
                'excerpt' => $postData['excerpt'],
                'content' => $postData['content'],
                'tags' => $postData['tags'],
                'read_time' => $postData['read_time'],
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 30)),
                'views_count' => rand(50, 500),
                'is_featured' => rand(0, 1) == 1,
            ]);
        }

        // Обновляем счётчики постов в категориях
        foreach ($createdCategories as $category) {
            $category->updatePostsCount();
        }

        echo "✓ Создано " . count($posts) . " статей блога\n";
        echo "\n=== Тестовые данные успешно загружены! ===\n\n";
        echo "Учётные данные для входа:\n";
        echo "Администратор: admin@goldenmodels.ru / password\n";
        echo "Модель: anna@example.com / password\n";
    }
}
