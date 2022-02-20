<?php

namespace App\Http\Resources\Admin;

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
            'id'=>$this->id,
            'name'=>$this->name,
            'discount_type' => $this->discount_type?$this->discount_type:'-',
            'discount' => $this->discount?$this->discount:'-',
            'expire_at'=> $this->expire_at?$this->expire_at:'lifetime',
            'status' => $this->status, 
            'created_at'=>$this->created_at->diffForHumans(),
        ];
    }
}
