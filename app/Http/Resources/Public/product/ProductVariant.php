<?php

namespace App\Http\Resources\Public\product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariant extends JsonResource
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
            'id'   => $this->id,
            'color'=>$this->color,
            
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->quantity
        ];
    }
}
