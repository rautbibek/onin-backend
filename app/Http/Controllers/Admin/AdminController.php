<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


    public function logout(){
        Auth::guard('admin')->logout();
        return response()->json('logged out');
    }

    public function changePassword(Request $request){
        $this->validate($request,[
            'password' => [
                'required',
                'string',
                
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                    'confirmed',
                
            ],
            
        ]);
        $admin = Admin::findOrFail(Auth::guard('admin')->id());
        return $admin;
        $admin->password = bcrypt($request->password);
        $admin->update();
        return response()->json([
            'message'=>'Password updated succefully'
        ]);
    }
}
