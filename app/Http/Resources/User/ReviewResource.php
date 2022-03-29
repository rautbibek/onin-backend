<?php

namespace App\Http\Resources\User;
use App\Http\Helper\MediaHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'product_title'=>$this->title,
            'product_id' => $this->product_id,
            'rating' => $this->rating,
            'review_message' => $this->message,
            'slug' => $this->slug,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
            'cover_image' => $this->cover?MediaHelper::getThumbnailUrl($this->cover,'thumb'):asset('/images/no-image.png'), 
        ];
    }
}
