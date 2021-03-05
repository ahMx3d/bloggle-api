<?php
namespace App\Repositories\Frontend;

use App\Events\PostUpdated;
use Stevebauman\Purify\Facades\Purify;
use App\Interfaces\Frontend\Repositories\IAuthPostRepository;
use App\Models\Post;

class AuthPostRepository implements IAuthPostRepository
{
    private $model;
    /**
     * Construct PostMedia Model.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = Post::class;
    }

    /**
     * Store post data.
     *
     * @param object $request
     * @return object
     */
    public function post_store($request)
    {
        $data = [
            'title'        => $request->title,
            'description'  => Purify::clean($request->description),
            'status'       => $request->status,
            'comment_able' => $request->comment_able,
            'category_id'  => $request->category_id,
        ];
        return auth()->user()->posts()->create($data);
    }

    /**
     * Get post by slug or id.
     *
     * @param string|int $key
     * @return object
     */
    public function post_get_by_key($key)
    {
        return auth()->user()->posts()->whereSlug($key)->orWhere('id',$key)->first();
    }

    /**
     * Update post data.
     *
     * @param object $request
     * @param object $post
     * @return void
     */
    public function post_update($request, $post)
    {
        $data = [
            'title'        => $request->title,
            'description'  => Purify::clean($request->description),
            'status'       => $request->status,
            'comment_able' => $request->comment_able,
            'category_id'  => $request->category_id,
        ];
        $post->update($data);
        event(new PostUpdated($post));
    }

    /**
     * Delete post data.
     *
     * @param int $id
     * @return void
     */
    public function post_delete_by_id($id)
    {
        $this->model::destroy($id);
    }
}
