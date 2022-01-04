<?php

namespace App\Http\Resources\Admin;
use App\Http\Helper\MediaHelper;
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
            'id'=>$this->id,
            'title'=>$this->title,
            'category' => $this->category?$this->category->name:'-',
            'brand' => $this->brand?$this->brand->name:'-',
            'discount' => $this->discount_percent,
            'status'   =>$this->status,
            'category_id'=> $this->category_id,
            'brand_id' =>$this->brand_id,
            //'total_variant'=> $this->variant_count,
            'variant'=>$this->variant,
            'inventory_track' => $this->inventory_track,
            'image' => $this->image?$this->image?MediaHelper::getThumbnailUrl($this->image[0],'thumb'):'':asset('/images/no-image.png'),
            'created_at'=>$this->created_at,
            'short_desc'=>$this->short_description,

        ];
    }
}
