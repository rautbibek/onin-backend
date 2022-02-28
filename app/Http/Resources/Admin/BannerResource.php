<?php

namespace App\Http\Resources\Admin;
use App\Http\Helper\MediaHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'id'  => $this->id,
            'type'=> $this->type,
            'title'=>$this->title,
            'subtitle' => $this->subtitle,
            'link' => $this->link,
            'attachment' => $this->image?MediaHelper::getThumbnailUrl($this->image,'thumb'):asset('/images/no-image.png'),
            'created_at'=>$this->created_at->diffForHumans(),
        ];
    }
}
