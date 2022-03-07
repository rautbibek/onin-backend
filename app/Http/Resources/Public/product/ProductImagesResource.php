<?php

namespace App\Http\Resources\Public\product;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Helper\MediaHelper;
class ProductImagesResource extends JsonResource
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
            'thumb'=> MediaHelper::getImageUrl($this->file,'thumb'),
            'image'=> MediaHelper::getImageUrl($this->file,'product')
        ];
    }
}
