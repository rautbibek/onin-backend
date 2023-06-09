<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'password' =>'required',
        ]);
    }
}
