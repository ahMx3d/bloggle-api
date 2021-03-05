<?php
namespace App\Interfaces\Frontend\Repositories;

interface IAuthUserRepository
{
    public function paginate_posts_desc($pagination_count);
    public function password_update($password);
    public function info_update($request, $file_name);
}
