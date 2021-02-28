<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(1)->create(['email' => 'tobexkee@gmail.com', 'image' => 'https://res.cloudinary.com/anifowosetobi/image/upload/v1603537126/15f9408e29051b_1603537122.jpg.jpg']);
        User::factory(1)->create(['email' => 'tobi@gmail.com', 'image' => 'https://res.cloudinary.com/anifowosetobi/image/upload/v1602164678/15f7f17c6da005_1602164678.jpg.jpg']);
        User::factory(1)->create(['email' => 'tobie@gmail.com', 'image' => 'https://res.cloudinary.com/anifowosetobi/image/upload/v1599343112/15f540a07dbc10_1599343111.jpg.jpg']);
        Chat::factory(50)->create();
    }
}
