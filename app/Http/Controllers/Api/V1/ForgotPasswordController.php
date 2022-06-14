<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Models\User;
use App\Rules\NotExists;
use App\Services\SendSmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function verfiyContactNumber(Request $request){
        $this->validate($request,[
            'contact_number'=>['required',new NotExists()],
        ]);
        $today_sms = SmsLog::where('mobile',$request->contact_number)->whereDate('created_at',Carbon::today())->where('status',true)->count();
        if($today_sms>=3){
            return response()->json([
                'message'=>'maximum attempt, please try after 24 hours',
            ],422);
        }
        $code = rand(10000,99999);
        $message = config('app.name').' Reset password verfication code, valid for 10 minutes - '.$code;
        $sms =  SendSmsService::sendSms($request->contact_number,$message);  
        $sms_log = SmsLog::create([
            'sms_type' => 'verification',
            'code'     =>$code,
            'mobile'   => $request->contact_number,
            'title'    => 'forgot password verification',
            'description' => $message,
            'ip_address' => request()->ip()
        ]);

        if($sms->error){
            $sms_log->update([
                'status' =>false
            ]);
            return response()->json([
                'message'=>'Unable to send sms'
            ],422);
        }
        return response()->json([
            'message'=>'verification code sent succefully.',
            'code'=> $code
        ],200);
    }
    
    public function resetPassword(Request $request){
        $this->validate($request,[
            'verification_code'=>'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $sms_log = SmsLog::where('code',$request->verification_code)->firstOrFail();
        $current_date = Carbon::now();
        $crossed_time = $current_date->diffInSeconds($sms_log->created_at);
        if(floor($crossed_time/60) > 10){
            return response()->json('Otp code has been expored.');
        }
        $user = User::where('mobile',$sms_log->mobile)->first();
        $user->password = bcrypt($request->password);
        $user->update();
        return response()->json([
            'message'=>'password updated succefully.'
        ],200);
    }
}
