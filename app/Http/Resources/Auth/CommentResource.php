<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'url'         => $this->url,
            'comment'     => $this->comment,
            'statusCode'  => $this->status(),
            'status'      => $this->status,
            'createdDate' => $this->created_at->format('d-m-Y h:i A'),
            'userRole'    => $this->userRole(),
        ];
    }
}
