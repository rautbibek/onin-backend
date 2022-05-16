<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}
