<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unreadNotification(){
        return Auth::guard('admin')->user()->unreadNotifications;
    }
}
