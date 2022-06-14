<?php

namespace App\Http\Controllers\Admin;
use App\Helper\Datatable;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SmsResource;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index(){
        $sms = new SmsLog;
        $sms = Datatable::filter($sms,['mobile','title','description']);
        return  SmsResource::collection($sms)->response()
        ->setStatusCode(200);
    }
}
