<?php

namespace App\Http\Resources\Global;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request):array
    {
        return [
            'name'       => $this->name,
            'slug'       => $this->slug,
            'postsCount' => $this->postsCount(),
            'url'        => route('tags.show', $this->slug),
        ];
    }
}
