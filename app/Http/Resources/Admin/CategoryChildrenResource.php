<?php

namespace App\Http\Resources\Admin;
use App\Http\Helper\MediaHelper;
use App\Http\Resources\Admin\CategoryChildrenResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryChildrenResource extends JsonResource
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
            'children'=> CategoryChildrenResource::collection($this->children),
            'cover'=> $this->cover,
            'cover_url'=> $this->cover?MediaHelper::getThumbnailUrl($this->cover,'thumb'):null,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
