<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

//use App\Models\Admin;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request){

        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);

        try{
            if(Auth::guard('admin')->attempt(['email'=>$request->email, 'password'=>$request->password])){
                return redirect()->intended(route('dashboard'));

            }
            return redirect()->back();
            //return $request->all();

        }catch(\Exception $e){
            return $e;
        }

        // return redirect()->intended(route('dashboard'));
        // return response()->json($request->all());

    }
}
