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
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(new LoginResourceDTO($token), 200);
    }
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'birthday' => $data['birthday'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json((new RegisterResourceDTO(
                $user->username,
                $user->email,
                $user->birthday
            ))->toArray() + ['token' => $token], 201);
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
