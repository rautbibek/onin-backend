<?php

namespace App\Http\Resources\Public\product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Public\product\ProductOptions;
use App\Http\Resources\Public\product\ProductVariant;
use App\Http\Resources\Public\product\ProductImagesResource;
use App\Http\Helper\MediaHelper;
class ProductDetailResource extends JsonResource
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
            'title'=> $this->title,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'short_desc' => $this->short_description,
            'description' => $this->description,
            'has_size' => $this->has_size,
            'has_color'=> $this->has_color,
            'meta_keyword' => $this->meta_keyword,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'brand' => $this->brand_name,
            'cover' =>  MediaHelper::getImageUrl($this->cover,'product'),
            'cover_thumb' =>  MediaHelper::getImageUrl($this->cover,'thumb'),
            'options' => $this->optionValues?ProductOptions::collection($this->optionValues):[],
            'variant' => $this->variant?ProductVariant::collection($this->variant):[],
            'images' => $this->images?ProductImagesResource::collection($this->images):[],

        ];
    }
}
