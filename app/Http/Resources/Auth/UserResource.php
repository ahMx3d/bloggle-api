<?php

namespace App\Http\Resources\Auth;

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
            'id'         => $this->id,
            'name'       => $this->name,
            'username'   => $this->username,
            'email'      => $this->email,
            'mobile'     => $this->mobile,
            'avatar'     => $this->userImage(),
            'statusCode' => $this->status,
            'statusText' => $this->status(),
            'bio'        => $this->bio,
            'notifiable' => $this->receive_email,
        ];
    }
}
