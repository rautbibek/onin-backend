<?php

namespace App\Http\Controllers\Api\V1\Login;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\SmsLog;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Resources\User\MeResource;
use Illuminate\Support\Facades\Auth;
use App\Services\SendSmsService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function register (Request $request) {
        $this->validate($request,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|numeric|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        DB::beginTransaction();
        try{
            $request['password']=Hash::make($request['password']);
            $request['remember_token'] = Str::random(10);
            $user = User::create($request->toArray());
            $token = $user->createToken('authToken')->plainTextToken;
            $code = rand(10000,99999);
            $message = config('app.name').' Mobile verification code is - '.$code;
            //$sms =  SendSmsService::sendSms($request->mobile,$message);
            
            $sms_log = SmsLog::create([
                'sms_type' => 'verification',
                'user_id'  => $user->id,
                'mobile'   => $request->mobile,
                'title'    => 'user registration',
                'description' => $message,
                'ip_address' => request()->ip()
            ]);
            // if($sms->error){
            //     $sms_log->update([
            //         'status' =>false
            //     ]);
            // }
            DB::commit();


        }catch(\Exception $e){
            //Log::channel('slack')->error($exception);
            DB::rollBack();
            
            return response()->json(array(
                'code' => 500,
                'error'=> $e,
                'message' => 'something went wrong'
            ), 500);
        }
        
        $response = [
            'user'=> $user,
            'token' => $token
        ];
        return response($response, 200);
    }


    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('authToken')->plainTextToken;
                Auth::login($user);
                $response = [
                    'user' => Auth::user(),
                    'authToken' => $token,
                ];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function getLoggedInUser(){
        return new MeResource(auth()->user());
        
    }
    public function logOut(){
        $token = Auth::user()->tokens();
        $token->delete();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function mobileVerification(){
        $otp = rand(10000,99999);
        $sms_log = SmsLog::where('mobile'); 
        $message = config('app.name').' mobile verification code '.$otp;
        $sms =  SendSmsService::sendSms(986375627,$message);
        
        if($sms->error){
            return response()->json([
                'message' => 'Invalid contact number'
            ],422);
        }
        return response()->json([
            'message' => 'Otp token sent to provided contact number'
        ]);
    }
}
