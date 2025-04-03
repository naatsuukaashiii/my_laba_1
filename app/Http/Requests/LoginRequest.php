<?php

namespace App\Http\Requests;

use App\DTO\LoginDTO;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Или ваша логика авторизации
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ];
    }

    public function toDTO(): LoginDTO
    {
        $dto = new LoginDTO();
        $dto->email = $this->validated('email');
        $dto->password = $this->validated('password');

        return $dto;
    }
}
