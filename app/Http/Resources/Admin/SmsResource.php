<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SmsResource extends JsonResource
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
            'code'=>$this->code,
            'sms_type'=> $this->sms_type,
            'mobile'=>$this->mobile,
            'title' => $this->title,
            'status'=> $this->status,
            'ip_address'=>$this->ip_address,
            'message'=>$this->description,
            'created_at'=>$this->created_at,
        ];
    }
}
