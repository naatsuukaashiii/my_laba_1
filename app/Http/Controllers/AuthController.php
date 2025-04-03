<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // Необходимо для logout
use Illuminate\Support\Facades\DB;
use Exception;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Получите DTO из класса запроса
        $registerDTO = $request->toDTO();

        // Используйте транзакцию для обеспечения целостности данных
        DB::beginTransaction();

        try {
            // Создайте нового пользователя
            $user = User::create([
                'name' => $registerDTO->name,
                'email' => $registerDTO->email,
                'password' => Hash::make($registerDTO->password),
            ]);

            // Зафиксируйте транзакцию
            DB::commit();

            // Верните экземпляр ресурса UserResource со статусом 201
            return (new UserResource($user))->response()->setStatusCode(201);

        } catch (Exception $e) {
            // Откатите транзакцию в случае ошибки
            DB::rollBack();

            // Верните сообщение об ошибке со статусом 500
            return response()->json(['message' => 'Registration failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        // Получите DTO из класса запроса
        $loginDTO = $request->toDTO();

        // Попытка аутентификации
        if (Auth::attempt(['email' => $loginDTO->email, 'password' => $loginDTO->password])) {
            $user = Auth::user();

            // Проверьте количество токенов
            $maxTokens = env('MAX_TOKENS_PER_USER', 5); // Значение по умолчанию - 5
            if ($user->tokens()->count() >= $maxTokens) {
                // Удалите старые токены (например, самый старый)
                $user->tokens()->oldest()->first()->delete(); // Удалите самый старый токен
            }

            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Successfully logged in',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);

        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Отзовите текущий токен
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
