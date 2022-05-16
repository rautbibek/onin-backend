<?php

namespace App\Http\Controllers;

use App\Models\SmsLog;
use App\Models\User;
use App\Rules\NotExists;
use App\Services\SendSmsService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Ui\Presets\React;

class UserController extends Controller
{
    public function updateProfile(Request $request){
        $this->validate($request,[
            'name'=>'required'
        ]);
        $user = User::findOrFail(Auth::id());
        $user->update([
            'name'=>$request->name,
        ]);

        return response()->json([
            'message'=>'Profile updated succefully.'
        ]);
    }

    public function changePassword(Request $request){
        $this->validate($request,[
            'current_password' => [
                'required',
                
                function ($attribute, $value, $fail){
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Your password was not updated, since the provided current password does not match.');
                    }
                }
            ],
            'new_password' => [
                'required', 'min:6', 'different:current_password'
            ],
            'password_confirmation' => 'required_with:password|same:new_password|min:6'
        ]);
        $user = Auth::user();
        $user->update([
            'password' => bcrypt($request->new_password)
        ]);
        return response()->json([
            'user'=>Auth::user(),
            'message'=>'Password updted succefully'
        ]);
    }

    public function reset_password_otp(Request $request){
       
         $this->validate($request,[
            'contact_number'=>[
                'required',
                new NotExists(),
            ]
        ]);
        $today_sms = SmsLog::where('mobile',$request->contact_number)
                   ->whereDate('created_at', Carbon::today())
                   ->count();
        if($today_sms>2){
            return response()->json([
                'message'=>'maximum attempt please try after 24 hours'
            ],422);
        }
        DB::beginTransaction();
        try{
            $code = rand(10000,99999);
            $message = config('app.name').' Rest password verification code is - '.$code;
            $sms =  SendSmsService::sendSms($request->contact_number,$message);
            if($sms->error){
                return response()->json([
                    'message'=>'Unable to send sms to this contact number'
                ],422);
            }else{
                $sms_log = SmsLog::create([
                    'sms_type' => 'forget_password',
                    //'user_id'  => $user->id,
                    'mobile'   => $request->contact_number,
                    'title'    => 'forget password',
                    'description' => $message,
                    'ip_address' => request()->ip()
                ]);
            }
            DB::commit();
            return response()->json([
                'message'=>'confirmation code sent to your contact number succefully.'
            ]);
        }catch(\Exception $e){
            //Log::channel('slack')->error($exception);
            DB::rollBack();
            
            return response()->json(array(
                'code' => $e->getCode(),
                'error'=> $e->getMessage(),
                'message' => 'something went wrong'
            ), 500);
        }
    }

    public function resetPassword(Request $request){
        $this->validate($request,[
            'password'=>[
                'required','min:6'
            ],
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'code'=>'required',
        ]);
        $otp = SmsLog::where('code',$request->code)->first();
        return $otp;
    }
}


