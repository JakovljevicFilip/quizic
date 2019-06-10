<?php

namespace App\Http\Controllers\Api;

use App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserChangeRoleRequest;
use App\Http\Requests\UserChangePasswordRequest;
use App\Http\Requests\UserDestroyRequest;


class UserController extends Controller
{
    public function index(request $request){
        $users = new User;
        $users = $users->fetchUsers($request);
        return response()->json([
            'title' => 'Users',
            'message' => 'Users fetched.',
            'write' => false,
            'users' => $users->items(),
            'last_page' => $users->lastPage(),
        ], 200);
    }

    public function changeRole(UserChangeRoleRequest $request){
        // GET USER ID
        $id = $request->user['id'];
        // GET ROLE
        $role = $request->user['role'];
        // USER SHOULD BE LOGGED OUT IF THEY CHANGE THEIR OWN STATUS
        $logout = Auth::user()->id === $id;

        // GET USER INFORMATION
        $user = User::find($id);
        // CHANGE USER ROLE
        $user->role = $role;
        // UPDATE USER
        $user->save();

        // RETURN RESPONSE
        return response()->json([
    		'title' => 'User',
            'message' => 'User has been updated.',
            'write' => true,
            'logout' => $logout,
        ], 200);
    }

    public function changePassword(UserChangePasswordRequest $request){
        // GET USER ID
        $id = Auth::user()->id;
        // GET NEW PASSWORD FROM REQUEST
        $passwordNew = $request->password;

        // FIND USER
        $user = User::find($id);
        // UPDATE USER PASSWORD
        $user->updatePassword($passwordNew);

        return response()->json([
    		'title' => 'Password',
            'message' => 'Password has been changed.',
            'write' => true,
        ], 200);
    }

    public function destroy(UserDestroyRequest $request){
        // GET USER
        $user = User::find($request->id);

        // USER SHOULD BE LOGGED OUT IF THEY CHANGE THEIR OWN STATUS
        $logout = Auth::user()->id === $user->id;

        // DELETE USER
        $user->delete();

        return response()->json([
            'title' => 'User',
            'message' => 'User has been deleted.',
            'write ' => true,
            'logout' => $logout,
        ]);
    }
}
