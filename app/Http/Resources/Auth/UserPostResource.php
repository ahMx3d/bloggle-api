<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Global\MediaResource;
use App\Http\Resources\Global\CommentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'slug'            => $this->slug,
            'description'     => $this->description,
            'statusCode'      => $this->status(),
            'status'          => $this->status,
            'commentableCode' => $this->comment_able,
            'commentable'     => $this->commentable(),
            'category'        => new CategoryResource($this->category),
            'tags'            => TagResource::collection($this->tags),
            'media'           => MediaResource::collection($this->media),
            'commentsCount'   => $this->commentsCount(),
            'comments'        => CommentResource::collection($this->comments)
        ];
    }
}
