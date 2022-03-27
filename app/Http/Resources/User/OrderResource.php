<?php

namespace App\Http\Resources\User;
use App\Http\Resources\User\OrderDetailResource;
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
            'order_identifier'=> $this->order_identifier,
            'delivery_charge' => $this->delivery_charge,
            'total_price'     => $this->total_price,
            'delivery_address'=> $this->delivery_address,
            'payment_status'  => $this->payment_status,
            'status'          => $this->status,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'order_detail'    => OrderDetailResource::collection($this->orderDetail),
            
        ];
    }
}
