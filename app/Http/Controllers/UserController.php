<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePasswordRequest; 

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        // Обновите существующего пользователя (требует валидации и авторизации)
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->update($request->all()); // Обновите пользователя данными из запроса
        return response()->json($user);
    }

    public function destroy($id)
    {
        // Удалите пользователя (требует авторизации)
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function profile()
    {
        // Получите информацию о текущем аутентифицированном пользователе
        $user = auth()->user(); // Предполагается, что вы используете middleware аутентификации

        // Верните экземпляр ресурса UserResource
        return new UserResource($user);
    }

    public function tokens(Request $request)
    {
        $user = $request->user(); // Получите текущего аутентифицированного пользователя

        // Получите список токенов пользователя
        $tokens = $user->tokens;

        // Верните список токенов (можно использовать ресурс для форматирования)
        return response()->json($tokens);
    }

    public function revokeAllTokens(Request $request)
    {
        $user = $request->user(); // Получите текущего аутентифицированного пользователя

        // Отзовите все токены пользователя
        $user->tokens()->delete();

        return response()->json(['message' => 'All tokens revoked successfully'], 200);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user(); // Получите текущего аутентифицированного пользователя

        $validated = $request->validated(); // Валидированные данные

        $user->update([
            'password' => Hash::make($validated['password']), // Хешируйте новый пароль
        ]);

        return response()->json(['message' => 'Пароль успешно изменен'], 200);
    }
}
