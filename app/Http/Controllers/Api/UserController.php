<?php

namespace App\Http\Controllers\Api;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserChangeRoleRequest;
use App\Http\Requests\UserChangePasswordRequest;


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

    public function changeRole(UserChangeRoleRequest $request){
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

    public function changePassword(UserChangePasswordRequest $request){
        // GET NEW PASSWORD FROM REQUEST
        $passwordNew = $request->user['password'];
        // FIND USER
        $user = User::find($request->user['id']);
        // UPDATE USER PASSWORD
        $user->updatePassword($passwordNew);

        return response()->json([
    		'write '=> true,
            'messages' => 'Password has been changed.',
        ]);
    }
}
