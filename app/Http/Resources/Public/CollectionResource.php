<?php

namespace App\Http\Resources\Public;
use App\Http\Resources\Public\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
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
            'slug' => $this->slug,
            'product' => ProductResource::collection($this->product)
        ];
    }
}
