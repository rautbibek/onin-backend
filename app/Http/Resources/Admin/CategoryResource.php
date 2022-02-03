<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'parent'=> $this->parent,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
