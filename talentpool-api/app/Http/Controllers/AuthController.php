<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(request $request)
    {

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:client,admin'
        ]);
        
        $user = $this->authService->register($request->all());
        
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

     
        
        $result = $this->authService->login($request->all());
        
        if (!$result) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        
        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user'],
            'token' => $result['token']
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function refresh()
    {
        $result = $this->authService->refresh();
        
        return response()->json([
            'message' => 'Token refreshed successfully',
            'user' => $result['user'],
            'token' => $result['token']
        ]);
    }

    public function forgotPassword(request $request)
    {
        $status = $this->authService->forgotPassword($request->email);
        
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Password reset link sent to your email'
            ]);
        }
        
        return response()->json([
            'message' => 'Unable to send password reset link'
        ], 400);
    }

    public function resetPassword(Request $request)
    {
  

        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $status = $this->authService->resetPassword($request->token ,$request->password);
            if ($status) {
            return response()->json([
                'message' => 'Password has been reset successfully'
            ]);
        }
    
        return response()->json([
            'message' => 'Unable to reset password. Invalid token or user.'
        ], 400);
    }
    
}
