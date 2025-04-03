<?php

namespace App\Http\Requests;

use App\DTO\RegisterDTO;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Или ваша логика авторизации
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function toDTO(): RegisterDTO
    {
        $dto = new RegisterDTO();
        $dto->name = $this->validated('name');
        $dto->email = $this->validated('email');
        $dto->password = $this->validated('password');

        return $dto;
    }
}
