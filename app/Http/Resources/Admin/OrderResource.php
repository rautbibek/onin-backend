<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_identifier'=>$this->order_identifier,
            'order_by'=>$this->order_by,
            'created_at'=>$this->created_at,
            'delivery_charge'=>$this->delivery_charge,
            'total_price'=>$this->total_price,
            'payment_type'=>$this->payment_type,
            'payment_status'=> $this->payment_status,
        ];
    }
}
