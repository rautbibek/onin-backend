<?php

namespace App\Http\Controllers\Api\V1\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'password'=>'required',
        ]);
        return $request->all();
    }
}
