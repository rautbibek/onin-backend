<?php

namespace App\Http\Resources\Public;
use App\Http\Helper\MediaHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'cart_quantity' => $this->quantity,
            'title' => $this->title,
            'discount_type' => $this->discount_type,
            'discount' =>$this->discount,
            'price' =>$this->price,
            'variant_id' => $this->varaint_id,
            'cover_image' => $this->cover?MediaHelper::getThumbnailUrl($this->cover,'thumb'):asset('/images/no-image.png'),
            'product_stock' => $this->stock,
            'last_modified_date' => $this->updated_at
        ];
    }
}
