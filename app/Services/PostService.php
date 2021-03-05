<?php
namespace App\Services;

class PostService
{
    /**
     * Map image database attributes to array for insertion.
     *
     * @param array $images
     * @param object $post
     * @return array
     */
    public function map_image_attributes_to_array($images, $post)
    {
        return collect($images)->map(function ($image) use($post)
        {
            $image['post_id']    = $post->id;
            $image['created_at'] = $post->created_at;
            $image['updated_at'] = $post->updated_at;
            return $image;
        })->toArray();
    }
}
