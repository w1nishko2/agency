<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelProfile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoriesList = [
            ['fashion', 'подиум'],
            ['commercial', 'реклама'],
            ['fitness'],
            ['plus-size'],
            ['детское'],
            ['промо']
        ];
        $cities = ['Москва', 'Санкт-Петербург', 'Казань', 'Новосибирск', 'Екатеринбург', 'Нижний Новгород'];
        $eyeColors = ['Карие', 'Голубые', 'Зелёные', 'Серые'];
        $hairColors = ['Блонд', 'Русый', 'Каштановый', 'Рыжий', 'Чёрный'];
        
        $firstNamesFemale = ['Анна', 'Мария', 'Елена', 'Ольга', 'Ирина', 'Наталья', 'Татьяна', 'Юлия', 'Светлана', 'Екатерина', 'Виктория', 'Алина', 'Дарья', 'Полина', 'Кристина'];
        $firstNamesMale = ['Александр', 'Дмитрий', 'Сергей', 'Андрей', 'Алексей', 'Артем', 'Иван', 'Максим', 'Никита', 'Михаил', 'Егор', 'Владимир', 'Павел', 'Роман', 'Денис'];
        $lastNames = ['Иванов', 'Петров', 'Сидоров', 'Смирнов', 'Кузнецов', 'Попов', 'Васильев', 'Соколов', 'Михайлов', 'Новиков', 'Федоров', 'Морозов', 'Волков', 'Алексеев', 'Лебедев'];
        
        $clothingSizesFemale = ['XS', 'S', 'M', 'L', 'XL'];
        $clothingSizesMale = ['S', 'M', 'L', 'XL', 'XXL'];

        // Создаем 30 моделей
        for ($i = 1; $i <= 30; $i++) {
            $gender = $i <= 20 ? 'female' : 'male'; // 20 девушек, 10 парней
            
            $firstName = $gender === 'female' 
                ? $firstNamesFemale[array_rand($firstNamesFemale)]
                : $firstNamesMale[array_rand($firstNamesMale)];
            
            $lastName = $lastNames[array_rand($lastNames)] . ($gender === 'female' ? 'а' : '');
            
            // Создаем пользователя
            $email = Str::slug($firstName . $lastName . $i) . '@test.ru';
            $user = User::create([
                'name' => $firstName . ' ' . $lastName,
                'email' => $email,
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]);

            // Параметры в зависимости от пола
            if ($gender === 'female') {
                $age = rand(18, 35);
                $height = rand(165, 185);
                $weight = rand(50, 70);
                $bust = rand(85, 95);
                $waist = rand(60, 75);
                $hips = rand(88, 98);
                $shoeSize = rand(36, 40);
                $clothingSize = $clothingSizesFemale[array_rand($clothingSizesFemale)];
            } else {
                $age = rand(18, 40);
                $height = rand(175, 195);
                $weight = rand(70, 90);
                $bust = rand(95, 110);
                $waist = rand(75, 90);
                $hips = rand(95, 105);
                $shoeSize = rand(41, 45);
                $clothingSize = $clothingSizesMale[array_rand($clothingSizesMale)];
            }

            $birthDate = now()->subYears($age)->subDays(rand(1, 365));
            
            $categories = $categoriesList[array_rand($categoriesList)];
            
            // Для plus-size увеличиваем параметры
            if (in_array('plus-size', $categories) && $gender === 'female') {
                $weight = rand(75, 95);
                $bust = rand(100, 115);
                $waist = rand(80, 95);
                $hips = rand(105, 120);
                $clothingSize = 'XL';
            }

            ModelProfile::create([
                'user_id' => $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'gender' => $gender,
                'age' => $age,
                'city' => $cities[array_rand($cities)],
                
                // Параметры
                'height' => $height,
                'weight' => $weight,
                'bust' => $bust,
                'waist' => $waist,
                'hips' => $hips,
                'shoe_size' => $shoeSize,
                'clothing_size' => $clothingSize,
                
                // Внешность
                'eye_color' => $eyeColors[array_rand($eyeColors)],
                'hair_color' => $hairColors[array_rand($hairColors)],
                
                // Дополнительно
                'bio' => "Профессиональная модель с опытом работы. Участвовала в различных проектах.",
                'languages' => json_encode(['ru', 'en']),
                'skills' => json_encode(['фотосессии', 'подиум']),
                
                // Контакты
                'telegram' => rand(0, 1) ? '@model' . $i : null,
                'vk' => rand(0, 1) ? '@model' . $i : null,
                
                // Категории
                'categories' => json_encode($categories),
                
                // Статус
                'status' => 'active',
                'is_featured' => $i <= 8, // Первые 8 моделей - избранные
                'experience_years' => rand(0, 5),
                'experience_description' => rand(0, 1) ? 'Опыт работы на показах, фотосессиях, рекламных кампаниях' : null,
                'has_modeling_school' => rand(0, 1) == 1,
                'views_count' => rand(10, 500),
                'bookings_count' => rand(0, 50),
            ]);
        }

        $this->command->info('✅ Создано 30 тестовых моделей');
    }
}
