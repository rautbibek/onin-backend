<?php

namespace App\Http\Resources\Public;
use App\Http\Helper\MediaHelper;
use App\Http\Resources\Public\product\ProductVariant;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'title' => $this->title,
            'short_desc' => $this->short_description,
            'slug' => $this->slug,
            'discount_type' => $this->discount_type,
            'discount' => $this->discount,
            'cover_image' => $this->image?$this->image?MediaHelper::getThumbnailUrl($this->image[0],'thumb'):'':asset('/images/no-image.png'),
            'variant' => $this->variant?ProductVariant::collection($this->variant):[],
        ];
    }
}
