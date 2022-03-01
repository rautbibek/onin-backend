<?php

namespace App\Http\Controllers\Api\V1;
use App\Models\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function banner(){
        $banner = Banner::limit(25)->get();
        
        $home = $banner->where('type', 'home')->toArray();
        $offer = $banner->where('type', 'offer')->toArray();
        $ad = $banner->where('type', 'ad')->toArray();
        $other = $banner->where('type', 'other')->toArray();
        return response()->json([
            'home'=>$home,
            'offer'=>$offer,
            'ad' => $ad,
            'other' => $other
        ]);
    }
}
