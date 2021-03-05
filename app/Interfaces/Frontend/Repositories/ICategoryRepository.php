<?php
namespace App\Interfaces\Frontend\Repositories;

interface ICategoryRepository{
    public function category_get_by_key($key);
    public function all_categories_get();
    public function all_categories_pluck();
}
