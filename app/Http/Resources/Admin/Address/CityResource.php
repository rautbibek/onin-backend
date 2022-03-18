<?php

namespace App\Http\Resources\Admin\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'name' => $this->name,
            'district_name' => $this->district_name,
            'district_id' => $this->district_id,
            'delivery_charge' => $this->price,
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
}
