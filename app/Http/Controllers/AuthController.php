<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\DTO\LoginResourceDTO;
use App\DTO\RegisterResourceDTO;
use App\DTO\UserResourceDTO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors' => ['username' => ['These credentials do not match our records.']]
            ], 401);
        }

        $user = Auth::user();
        $maxTokens = (int) env('MAX_TOKENS', 4);


        $tokens = $user->tokens()->orderBy('created_at', 'desc')->get();
        if ($tokens->count() >= $maxTokens) {
            $tokens->slice($maxTokens - 1)->each->delete();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResourceDTO(
                $user->id,
                $user->username,
                $user->email,
                $user->birthday
            )
        ], 200);
    }
    public function register(RegisterRequest $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'User already exists'], 409);
        }

        $minBirthDate = now()->subYears(14)->format('Y-m-d');
        if ($request->birthday > $minBirthDate) {
            return response()->json([
                'message' => 'Registration failed',
                'errors' => [
                    'birthday' => ['Age must be at least 14 years old']
                ]
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birthday' => $request->birthday,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResourceDTO(
                $user->id,
                $user->username,
                $user->email,
                $user->birthday
            )
        ], 201);
    }
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json(new UserResourceDTO(
            $user->id,
            $user->username,
            $user->email,
            $user->birthday
        ));
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully'], 200);
    }
    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens()->pluck('name');
        return response()->json(['tokens' => $tokens]);
    }
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'All tokens revoked'], 200);
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        return $fail('Current password is incorrect.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#]/',
            ],
        ]);
        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);
        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}
