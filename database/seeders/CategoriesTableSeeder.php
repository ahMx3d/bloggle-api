<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Un-Categorized Category
        Category::create([
            'name'   => 'un-categorized',
            'slug'   => Str::slug('un-categorized'),
            'status' => 1,
        ]);
        // Natural Category
        Category::create([
            'name'   => 'Natural',
            'slug'   => Str::slug('Natural'),
            'status' => 1,
        ]);
        // Flowers Category
        Category::create([
            'name'   => 'Flowers',
            'slug'   => Str::slug('Flowers'),
            'status' => 1,
        ]);
        // Kitchen Category
        Category::create([
            'name'   => 'Kitchen',
            'slug'   => Str::slug('Kitchen'),
            'status' => 0,
        ]);
    }
}
