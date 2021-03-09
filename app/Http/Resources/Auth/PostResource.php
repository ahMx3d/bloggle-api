<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'url'             => route('posts.show', $this->slug),
            'statusCode'      => $this->status(),
            'status'          => $this->status,
            'commentableCode' => $this->comment_able,
            'commentable'     => $this->commentable(),
            'createdDate'     => $this->created_at->format('d-m-Y h:i A'),
            'commentsCount'   => $this->approvedCommentsCount(),
        ];
    }
}
