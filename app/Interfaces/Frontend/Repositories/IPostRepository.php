<?php
namespace App\Interfaces\Frontend\Repositories;

interface IPostRepository
{
    public function all_posts_limit($limitation_count);
    public function archived_posts_pluck();
    public function all_posts_paginate($pagination_count);
    public function posts_search($request, $pagination_count);
    public function post_get_by_slug_with_relations($slug);
    public function post_get_by_slug($slug);
    public function posts_get_by_category_with_relations($category_id, $pagination_count);
    public function posts_get_by_date_with_relations($date, $pagination_count);
    public function posts_get_by_user_with_relations($user_id, $pagination_count);
}
