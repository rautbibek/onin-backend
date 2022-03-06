<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\ChildrenResource;
class CategorySelectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name
        ];
        //$name = $this->id;
        if(!$this->children->isEmpty()){
            $data['children'] = ChildrenResource::collection($this->children);
        }
        return $data;
    }
}
