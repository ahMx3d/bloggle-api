<?php

namespace Database\Seeders;

use App\Models\Page;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Faker Factory
        $faker = Factory::create();

        // Three Static Pages
        Page::create([
            'title'        => 'About Us',
            'description'  => $faker->paragraph(),
            'status'       => 1,
            'comment_able' => 0,
            'post_type'    => 'page',
            'user_id'      => 1,
            'category_id'  => 1,
        ]);
        Page::create([
            'title'        => 'Our Vision',
            'description'  => $faker->paragraph(),
            'status'       => 1,
            'comment_able' => 0,
            'post_type'    => 'page',
            'user_id'      => 1,
            'category_id'  => 1,
        ]);
        Page::create([
            'title'        => 'Contact Us',
            'description'  => $faker->paragraph(),
            'status'       => 1,
            'comment_able' => 0,
            'post_type'    => 'page',
            'user_id'      => 1,
            'category_id'  => 1,
        ]);
    }
}
