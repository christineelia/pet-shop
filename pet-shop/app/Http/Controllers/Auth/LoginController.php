<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Handlers\AuthHandler;

class LoginController extends Controller
{
/**
 * @OA\Post(
 *     path="/admin/login",
 *     summary="Login",
 *     description="Authenticate a user",
 *     operationId="login",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         description="User credentials",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email", example="admin@buckhill.co.uk"),
 *             @OA\Property(property="password", type="string", example="admin")
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Authentication successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Authentication failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Invalid Login credentials")
 *         )
 *     )
 * )
 */

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
