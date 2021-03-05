<?php
namespace App\Interfaces\Frontend\Repositories;

interface IUserRepository{
    public function user_get_by_username($key);
}
