<?php

namespace App\Http\Resources\Global;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'name' => $this->file_name,
            'type' => $this->file_type,
            'size' => $this->file_size,
            'url'  => $this->postImages(),
        ];
    }
}
