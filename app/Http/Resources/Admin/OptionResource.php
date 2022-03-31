<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
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
            'name'=>$this->code,
            'type'=> $this->type,
            'is_filterable'=> $this->is_filterable,
            'code'=> $this->key,
            'values' => $this->values,
            'created_at'=> $this->created_at->diffForHumans(),
        ];
    }
}
