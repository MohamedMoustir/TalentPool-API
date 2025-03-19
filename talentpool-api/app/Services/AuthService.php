<?php
// app/Services/AuthService.php
namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        return $user;
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);

            return [
                'user' => $user,
                'token' => $token
            ];
        }

        return 'message Invalid credentials';

    }


    public function logout()
    {
        Auth::user()->tokens()->delete();
        return true;
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return null;
            }

            $user = JWTAuth::toUser($token);

            if (!$user) {
                return null;
            }

            $user->tokens()->delete();

            $newToken = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $newToken
            ];

        } catch (\Exception $e) {
            return null;
        }
    }


    public function forgotPassword($email)
    {
        $status = Password::sendResetLink(['email' => $email]);
        return $status;
    }


    public function resetPassword($token, $password)
    {
        try {
            $user = JWTAuth::toUser($token);

            if (!$user) {
                return response()->json(['message' => 'Invalid token'], 400);
            }

            $user->password = Hash::make($password);
            $user->save();


            return response()->json(['message' => 'Password has been reset successfully']);

        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage());
            return response()->json(['message' => 'Unable to reset password'], 400);
        }
    }
}


