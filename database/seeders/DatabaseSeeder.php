<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    //наполнить бд 

    public function run(): void
    {
        // создать роли
        $userRole = \App\Models\Role::create(['name' => 'user', 'description' => 'Regular user']);
        $adminRole = \App\Models\Role::create(['name' => 'admin', 'description' => 'Administrator']);

        // создать единицы измерения
        \App\Models\Dimension::factory(5)->create();

        // создать admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
        ]);

        // создать обычных пользователей
        User::factory(5)->create([
            'role_id' => $userRole->id,
        ]);

        // создать места
        \App\Models\Place::factory(8)->create();

        // создать вещи
        \App\Models\Thing::factory(20)->create();

        // создать выдать некоторые вещи
        $users = User::all();
        $things = \App\Models\Thing::all();
        $places = \App\Models\Place::all();
        $dimensions = \App\Models\Dimension::all();

        foreach ($things->random(10) as $thing) {
            \App\Models\Usage::create([
                'thing_id' => $thing->id,
                'user_id' => $users->random()->id,
                'place_id' => $places->random()->id,
                'amount' => rand(1, 10),
                'dimension_id' => $dimensions->random()->id,
            ]);
        }
    }
}
