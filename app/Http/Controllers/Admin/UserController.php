<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Helper\Datatable;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Support\Collection;
//use App\Http\Resources\Admin\UserResources;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        $users = new User;
        $users = Datatable::filter($users,['name','email']);

        //$data = UserResource::collection($users);
        return  UserResource::collection($users)->response()
        ->setStatusCode(200);;

        //return response()->json($users,200);
    }
}
