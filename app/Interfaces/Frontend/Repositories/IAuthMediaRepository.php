<?php
namespace App\Interfaces\Frontend\Repositories;

interface IAuthMediaRepository{
    public function get_by_keys($post_key, $media_key);
    public function delete_by_id($id);
    public function get_by_id($id);
}
