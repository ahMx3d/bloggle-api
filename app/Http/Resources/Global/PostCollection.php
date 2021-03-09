<?php

namespace App\Http\Resources\Global;

use Illuminate\Http\Resources\Json\JsonResource;

class PostCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request):array
    {
        return [
            'title'         => $this->title,
            'slug'          => $this->slug,
            'url'           => route('posts.show', $this->slug),
            'description'   => $this->description,
            'status'        => $this->status(),
            'commentable'   => $this->comment_able,
            'createdDate'   => $this->created_at->format('d-m-Y h:i A'),
            'author'        => new UserResource($this->user),
            'category'      => new CategoryResource($this->category),
            'tags'          => TagResource::collection($this->tags),
            'media'         => MediaResource::collection($this->media),
            'commentsCount' => $this->approvedCommentsCount(),
        ];
    }
}
