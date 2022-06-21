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
        return  UserResource::collection($users)->response()
        ->setStatusCode(200);
    }

    public function userDetail($id){
        $user = User::with(['address','orders','favorites','reviews'])->where('id',$id)->firstOrFail();
        return response()->json($user,200);
    }
}
