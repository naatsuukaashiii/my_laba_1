<?php // Важно: открывающий тег PHP должен быть в начале файла

namespace App\DTO;

class RegisterResourceDTO
{
    public $username;
    public $email;
    public $birthday;

    public function __construct(string $username, string $email, string $birthday)
    {
        $this->username = $username;
        $this->email = $email;
        $this->birthday = $birthday;
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'birthday' => $this->birthday
        ];
    }
}
