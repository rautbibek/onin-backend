<?php

namespace App\Http\Resources\User;

use App\Http\Helper\MediaHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'product_id'=> $this->product_id,
            'order_id' => $this->order_id,
            'quantity' => $this->quantity,
            'price'    => $this->price,
            'product_title' => $this->title,
            'product_slug' => $this->slug,
            'cover_image' => $this->cover?MediaHelper::getThumbnailUrl($this->cover,'thumb'):asset('/images/no-image.png'),
            'color' => $this->color  
        ];
    }
}
