<?php

namespace App\Http\Controllers;

use App\Mail\OrderCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WelcomeController extends Controller
{
    public function index(){
        if(auth()->guard('admin')->check()){
            return redirect()->route('dashboard');
        }
        return view('welcome');
    }

    public function sendEmail(){
        Mail::to('rautbibek47@gmail.com')->send(new OrderCompleted());
    }
}
