<?php

namespace App\Http\Controllers;
use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function options(){
        $options = Option::all();
        return response()->json($options);
    }
}