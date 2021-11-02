<?php

namespace App\Http\Resources\Global;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryMenuResource extends JsonResource
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
            'id'=>$this->id,
            'parent_id'=>$this->parent_id,
            'name'=>$this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'cover'=> $this->cover,
        ];
    }
}
