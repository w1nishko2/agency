<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем администратора, если его еще нет
        User::firstOrCreate(
            ['email' => 'admin@agency.local'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Администратор создан:');
        $this->command->info('Email: admin@agency.local');
        $this->command->info('Пароль: admin123');
        $this->command->warn('⚠️  ВАЖНО: Измените пароль после первого входа!');
    }
}
