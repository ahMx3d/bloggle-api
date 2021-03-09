<?php

namespace App\Http\Resources\Global;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'     => $this->name,
            'username' => $this->username,
            'avatar'   => $this->userImage(),
            'status'   => $this->status,
            'bio'      => $this->name,
        ];
    }
}
