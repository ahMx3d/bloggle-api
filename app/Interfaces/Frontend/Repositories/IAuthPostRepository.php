<?php
namespace App\Interfaces\Frontend\Repositories;

interface IAuthPostRepository{
    public function post_store($request);
    public function post_get_by_key($key);
    public function post_update($request, $post);
    public function post_delete_by_id($id);
}
