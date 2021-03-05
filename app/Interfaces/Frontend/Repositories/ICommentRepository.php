<?php
namespace App\Interfaces\Frontend\Repositories;

interface ICommentRepository
{
    public function all_comments_limit($limitation_count);
    public function comment_store($request,$post_id,$user_id);
    public function store_on_post($request, $post);
    public function get_by_id($id);
    public function update($request, $comment);
    public function delete_by_id($id);
    public function paginate_by_post_id($pagination_count, $post_id);
    public function paginate_all($pagination_count);
}
