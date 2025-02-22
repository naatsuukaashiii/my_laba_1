<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'username' => [
                'sometimes',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z0-9_]+$/',
                'unique:users,username,' . $this->route('user'),
            ],
            'email' => [
                'sometimes',
                'string',
                'email',
                'unique:users,email,' . $this->route('user'),
            ],
            'password' => [
                'nullable',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'birthday' => [
                'nullable',
                'date',
                'before_or_equal:' . now()->subYears(14)->toDateString(),
            ],
        ];
    }
    public function messages()
    {
        return [
            'username.min' => 'Username must be at least 3 characters.',
            'username.max' => 'Username cannot exceed 50 characters.',
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
            'username.unique' => 'This username is already taken.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'birthday.before_or_equal' => 'You must be at least 13 years old.',
        ];
    }
}