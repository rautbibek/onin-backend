<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'=>$this->name,
            'first_name'=> '',
            'last_name' => '',
            'email' => $this->email,
            'phone' => '',
            'created_at' => $this->created_at,
            'avatar' => '',
        ];
    }
}
