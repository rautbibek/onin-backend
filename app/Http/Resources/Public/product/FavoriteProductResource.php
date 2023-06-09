<?php

namespace App\Http\Resources\Public\product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Helper\MediaHelper;
use App\Http\Resources\Public\product\ProductVariant;
class FavoriteProductResource extends JsonResource
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
            'is_favorite' => true,
            'has_color'=> $this->has_color,
            'discount' => $this->discount,
            'cover_image' => $this->cover?MediaHelper::getThumbnailUrl($this->cover,'thumb'):asset('/images/no-image.png'),
            'variant' => $this->variant?ProductVariant::collection($this->variant):[],
            'created_at'=>$this->created_at,
        ];
    }
}
