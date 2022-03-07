<?php

namespace App\Http\Resources\Public\product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptions extends JsonResource
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
            'name' => str_replace('_', ' ', $this->option),
            'value' => $this->option_value
        ];
    }
}
