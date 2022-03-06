<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Banner;
use App\Http\Resources\Banner\HomeBannerResource;
use App\Http\Resources\Banner\OfferBannerResource;
use App\Http\Resources\Banner\AdBannerResource;
use App\Http\Resources\Banner\OtherBannerResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function banner(){
        $banner = Banner::limit(25)->get();
        
        $home = $banner->where('type', 'home')->toArray();
        //return HomeBannerResource::collection($home);
        $offer = $banner->where('type', 'offer')->toArray();
        $ad = $banner->where('type', 'ad')->toArray();
        $other = $banner->where('type', 'other')->toArray();
        return response()->json([
            'home'=>HomeBannerResource::collection($home),
            'offer'=>OfferBannerResource::collection($offer),
            'ad' => AdBannerResource::collection($ad),
            'other' => OtherBannerResource::collection($other)
        ]);
    }
}
