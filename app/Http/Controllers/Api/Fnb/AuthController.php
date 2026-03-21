<?php

namespace App\Http\Controllers\Api\Fnb;

use App\Http\Controllers\Controller;
use App\Http\Resources\Fnb\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validation->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('email', $request->login)->orWhere('username', $request->login)->first();
         if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password',
            ], Response::HTTP_UNAUTHORIZED);
         }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => UserResource::make($user),
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout successful',
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Profile retrieved successfully',
            'data' => UserResource::make(Auth::user()),
        ]);
    }
}
