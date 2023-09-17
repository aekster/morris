<?php

namespace Morris\Core\Http\Controllers;

use Morris\Core\Http\Requests\RegisterRequest;
use Morris\Core\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Morris\Core\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): ApiResponse
    {
        $user = User::create([
            'name' => $request->validated(['name']),
            'email' => $request->validated(['email']),
            'password' => bcrypt($request->validated(['password'])),
        ]);

        // You can generate a token here if you're using Laravel Passport or a similar package

        // Return a response
        return response()->json(['message' => 'Registration successful', 'user' => $user]);
    }
}
