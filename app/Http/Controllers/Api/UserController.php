<?php

namespace App\Http\Controllers\Api;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
    	return response()->json([
    		'write '=> false,
    		'messages' => 'Users fetched.',
    		'users' => $users,
    	]);
    }

    public function changeRole(UserUpdateRequest $request){
        // FRONT-END INFORMATION
        $id = $request->user['id'];
        $role = $request->user['role'];

        // GET USER INFORMATION
        $user = User::find($id);
        // CHANGE USER ROLE
        $user->role = $role;
        // UPDATE USER
        $user->save();

        // RETURN RESPONSE
        return response()->json([
    		'write '=> true,
            'messages' => 'User has been updated.',
        ]);
    }
}
