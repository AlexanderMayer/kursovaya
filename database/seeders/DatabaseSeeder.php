<?php

namespace Database\Seeders;


use App\Models\Category;
use App\Models\Complaint;
use App\Models\Lot;
use App\Models\Message;
use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

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

        Lot::factory(44)->create();
        Photo::factory(20)->create();
        Message::factory(25)->create();
        Complaint::factory(20)->create();

    }
}
