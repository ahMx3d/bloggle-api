<?php

namespace Database\Seeders;

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
        // $this->call(UsersTableSeeder::class);
        $this->call([
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            CategoriesTableSeeder::class,
            TagsTableSeeder::class,
            PostsTableSeeder::class,
            PostsTagsTableSeeder::class,
            PagesTableSeeder::class,
            CommentsTableSeeder::class,
            SettingsTableSeeder::class,
        ]);
    }
}
