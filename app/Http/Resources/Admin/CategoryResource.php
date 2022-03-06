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
            'has_color' => $this->has_color,
            'has_size' => $this->has_size,
            'icon' => $this->icon,
            'children'=> $this->children,
            // 'cover'=> $this->cover_image,
            'created_at' => $this->created_at->diffForHumans(),
            // 'cat_options' => $this->categoryOptions->map(function($item){
            //     return $item->option_id;
            // }),
        ];
    }
}
