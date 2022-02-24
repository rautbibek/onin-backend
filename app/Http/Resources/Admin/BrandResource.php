<?php

namespace App\Http\Resources\Admin;
use App\Http\Helper\MediaHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'category' => $this->category,
            'attacment' => $this->logo?MediaHelper::getThumbnailUrl($this->logo,'thumb'):asset('/images/no-image.png'),
            'created_at'=>$this->created_at->diffForHumans(),
        ];
    }
}
