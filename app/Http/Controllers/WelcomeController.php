<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        if(auth()->guard('admin')->check()){
            return redirect()->route('dashboard');
        }
        return view('welcome');
    }
}
