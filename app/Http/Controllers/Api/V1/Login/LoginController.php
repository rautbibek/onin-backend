<?php

namespace App\Http\Controllers\Api\V1\Login;
use App\Models\User;
use App\Http\Controllers\Controller;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'password'=>'required',
        ]);
        $user = User::where('email',$request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'error'=>'invalid credentials'
            ]);
        }
        return  $user->createToken('Auth Token')->accessToken;

    }

    public function getLoggedInUser(){
        return auth()->user();
    }
}
