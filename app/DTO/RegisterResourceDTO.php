<?php
namespace App\DTO;
class RegisterResourceDTO
{
    public function __construct(
        public string $username,
        public string $email,
        public string $birthday
    ) {}
    public function toArray()
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            'birthday' => $this->birthday,
        ];
    }
}
