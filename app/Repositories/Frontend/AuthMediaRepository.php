<?php
namespace App\Repositories\Frontend;

use App\Interfaces\Frontend\Repositories\IAuthMediaRepository;
use App\Models\PostMedia;

class AuthMediaRepository implements IAuthMediaRepository
{
    private $model;
    /**
     * Construct PostMedia Model.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = PostMedia::class;
    }

    /**
     * Get the authenticated user post media using posts and media unique keys.
     *
     * @param string|int $post_key
     * @param string|int $media_key
     * @return object
     */
    public function get_by_keys($post_key, $media_key)
    {
        return auth()->user()->posts()->whereSlug($post_key)->orWhere(
            'id',
            $post_key
        )->first()->media()->whereFileName($media_key)->orWhere(
            'id',
            $media_key
        )->first();
    }

    /**
     * Get the authenticated media by media id.
     *
     * @param int $id
     * @return object
     */
    public function get_by_id($id)
    {
        return $this->model::find($id);
    }

    /**
     * Delete media using its id.
     *
     * @param int $id
     * @return void
     */
    public function delete_by_id($id)
    {
        $this->model::destroy($id);
    }
}

