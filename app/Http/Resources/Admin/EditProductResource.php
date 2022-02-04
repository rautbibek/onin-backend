<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class EditProductResource extends JsonResource
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
            'id' =>$this->id,
            'title' => $this->title,
            'category_id' =>$this->category_id,
            'meta_tags' => $this->meta_keyword,
            'search_text' => $this->search_text,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'status'=> $this->status,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'parent_id'=> $this->category_id,
            'has_color'=>$this->has_color,
            'has_size' => $this->has_size,
            'discount_value'=> $this->discount_type,
            'discount'=>$this->discount,
            'brand_id'=> $this->brand_id,
            'collection' => $this->collection->map(function($item){
                return $item->id;
            }),
            'option_value'=> $this->optionValues->map(function($data){
                return $data;
            }),
            'images'=>$this->images,
            'variant'=>$this->variant
        ];
    }
}
