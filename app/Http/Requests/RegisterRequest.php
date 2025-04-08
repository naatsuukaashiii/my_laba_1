<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'username' => [
                'required',
                'string',
                'unique:users,username',
                'regex:/^[A-Z][a-zA-Z]{6,}$/i',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase(),
            ],
            'c_password' => [
                'required',
                'same:password',
            ],
            'birthday' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(14)->toDateString(),
            ],
        ];
    }
    public function messages()
    {
        return [
            'username.regex' => 'Имя пользователя должно содержать только буквы латинского алфавита, начинаться с большой буквы и иметь длину не менее 7 символов.',
            'password' => 'Пароль должен содержать минимум 8 символов, включая цифры, символы и буквы в верхнем и нижнем регистре.',
            'birthday.before_or_equal' => 'Возраст пользователя должен быть не менее 14 лет.',
        ];
    }
}
