<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use App\Models\Lot;
use App\Models\Message;
use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(4)->create();
        User::factory()->createMany([
            ['name'=>"qwe", 'surname'=>"qwe", 'login'=>"qwe", 'email'=>"qwe@ya.ru", 'password'=>"qwe",],
            ['name'=>"admin", 'surname'=>"admin", 'login'=>"admin", 'email'=>"admin@ya.ru", 'password'=>"123",'role_id'=>2],
            ['name'=>"admin2", 'surname'=>"admin2", 'login'=>"admin2", 'email'=>"admin2@ya.ru", 'password'=>"123",'role_id'=>2],
        ]);

        Role::factory()->createMany([
            ['role_name' => 'user'],
            ['role_name' => 'admin'],
            ['role_name' => 'moder'],
        ]);

        Category::factory()->createMany([
            ['category_name' => 'Антиквариат'],
            ['category_name' => 'Игрушки'],
            ['category_name' => 'Инструменты'],
            ['category_name' => 'Техника'],
            ['category_name' => 'Книги'],
        ]);

        Lot::factory(10)->create();
        Photo::factory(4)->create();
        Message::factory(15)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
