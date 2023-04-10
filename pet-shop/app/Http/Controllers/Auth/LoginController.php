<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Handlers\AuthHandler;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validate = \Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);
            
            
        if ($validate->fails()) {
            $response = [
                'data' => $validate->errors(),
                'message' => 'Validation Failed!'
            ];
    
            return response()->json($response, 422);
        }
    
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();
            
            $authHandler = new AuthHandler;
            $token = $authHandler->GenerateToken($user);

            $success = ['user' => $user, 'token' => (string) $token];

            $response = [
                'data' => $success,
                'message' => 'Logged In Sucessfully'
            ];
            return response()->json($response, 200);
        }
        else{
            $response = [
                'data' => ['error' => "Invalid Login credentials"],
                'message' => 'Unauthorized'
            ];
            return response()->json($response, 401);
        }

    }

    
}
