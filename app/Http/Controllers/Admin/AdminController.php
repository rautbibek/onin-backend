<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{


    public function logout(){
        Auth::guard('admin')->logout();
        return response()->json('logged out');
    }
}
