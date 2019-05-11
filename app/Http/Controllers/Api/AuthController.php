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
		$credidentials=$request->only('username','password');
		// ATTEMPTS LOGIN
		if($token=$this->guard()->attempt($credidentials)){
			// LOGIN SUCCESSFUL
			return response()->json([
				'status'=>true,
				'messages'=>'Login successful.',
			],200)->header('Authorization',$token);
		}
		// LOGIN FAILED
		return response()->json([
			'status'=>false,
			'messages'=>'Login error: invalid credidentials.',
		], 401);
	}

	public function logout(){
		$this->guard()->logout();
		return response()->json([
			'status'=>true
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

        // TOKEN FOR VALDIATION
        $token = JWTAuth::getToken();

        // HAS IT BEEN SENT
        if(!$token){
            return response()->json([
                'status'=>false,
                'messages'=>'Token is missing.',
            ],200);
        }

        // IF IT IS SENT
        try{
            // SETS NEW TOKEN IN PLACE OF OLD ONE
            $token = JWTAuth::refresh($token);
            JWTAuth::setToken($token);

        }catch(TokenInvalidException $e){
            // INVALID TOKEN
            return response()->json([
                'status'=>false,
                'messages'=>'Invalid token.',
            ],200);
        }

        return response()->json([
            'status'=>true,
            'Authorization'=>$token,
        ],200);
	}

	// SHORTHAND
	private function guard(){
		return Auth::guard();
	}

}
