<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name"  => "required|string|max:255",
            "email" => "required|email|unique:users,email",
            "password"  => "required|string|min:8",
            'password_confirm' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message"=> $validator->errors(),
            ],401);
        }

        try {
            $user = User::create([
                "name"      => $request->name,
                "email"     => $request->email,
                "password"  =>  bcrypt($request->password),
                "role"      =>  config('constant.USERS_ROLE.USER'),
            ]);

            $apiToken = $user->createToken('accessToken')->plainTextToken;

            return response()->json([
                "status"    => true,
                "message"   => "User Created Successfully",
                "token"     => $apiToken,
                "data"      => new UserResource($user),
            ],201);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password"  => "required",
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message"=> $validator->errors(),
            ],401);
        }

        try {
            $user = User::whereEmail($request->email)->first();

            if (!$user) {
                return response()->json([
                    "status" => false,
                    "message"=> "User not found",
                ],401);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    "status" => false,
                    "message"=> "Invalid Password",
                ],401);
            }

            $apiToken = $user->createToken('accessToken')->plainTextToken;

            return response()->json([
                "status"    => true,
                "message"   => "User Login Successfully",
                "token"     => $apiToken,
                "data"      => new UserResource($user),
            ],200);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged out'
            ]);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
