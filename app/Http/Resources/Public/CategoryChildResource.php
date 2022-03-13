<?php

namespace App\Http\Resources\Public;
use App\Http\Helper\MediaHelper;
use App\Http\Resources\Public\CategoryChildResource;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryChildResource extends JsonResource
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
            'slug'=> $this->slug,
            'parent_id'=> $this->parent_id,
            'cover'=> $this->cover?MediaHelper::getThumbnailUrl($this->cover,'thumb'):null,
            'children'=> CategoryChildResource::collection($this->children),
        ];
    }
}
