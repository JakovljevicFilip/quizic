<?php

namespace App\Http\Controllers\Api;

// MODELS
use App\User;

// CLASSES
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
	public function register(Request $request){
		// VALIDATION
		$validation = Validator::make($request->all(),[
			'username'=>'required|string|unique:users',
			'email'=>'required|string|email|unique:users',
			'password'=>'required|min:6|confirmed',
		]);
		// DID IT FAIL?
		if($validation->fails()){
			// STOPS THE CODE
			return response()->json([
				'status'=>false,
				'messages'=>$validation->errors()->all(),
			],422);
		}
		// CREATES NEW USER
		$user=new User();
		$user->username=$request->username;
		$user->email=$request->email;
		$user->password=bcrypt($request->password);
		$user->save();

		// RETURNS RESPONSE
		return response()->json([
			'status'=>true,
			'messages'=>'Registration successful.',
		],200);
	}

	public function login(Request $request){
		// DATA NECESSARY FOR LOGIN
		$credentials = $request->only('username','password');
		// ATTEMPTS LOGIN
		if($token = auth()->attempt($credentials)){
			// LOGIN SUCCESSFUL
			return response()->json([
				'status'=>true,
				'messages'=>'Logged in.',
			],200)->header('Authorization',$token);
		}
		// LOGIN FAILED
		return response()->json([
			'status'=>false,
			'messages'=>'Username and/or password are wrong.',
		], 401);
	}

	public function logout(){
		$this->guard()->logout();
		return response()->json([
            'status'=>true,
            'messages'=>'Logged out.'
		],200);
	}

	// FETCHES USER INFORMATION
	public function user(Request $request){
		$user = User::find(Auth::user()->id);
		return response()->json([
			'status' => 'success',
			'data' => $user
		]);
	}

	// REFRESHES AUTHORIZATION TOKEN
	public function refresh(){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
        $newToken = auth()->refresh();
        // SEND NEW TOKEN
        return response()->json([
            'status'=>true,
        ],200)->header('Authorization',$newToken);
	}

	// SHORTHAND
	private function guard(){
		return Auth::guard();
	}

}
