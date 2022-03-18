<?php

namespace App\Http\Resources\Admin\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class LocalAreaResource extends JsonResource
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
            'id'=> $this->id,
            'city_name' => $this->city_name,
            'city_id' => $this->city_id,
            'name' => $this->name,
            'delivery_charge' => $this->price,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
