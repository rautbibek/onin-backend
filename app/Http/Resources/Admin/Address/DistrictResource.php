<?php

namespace App\Http\Resources\Admin\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
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
            'state_name' => $this->state_name,
            'state_id' => $this->state_id,
            'name' => $this->name,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
