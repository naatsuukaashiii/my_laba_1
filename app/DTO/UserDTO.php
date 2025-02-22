<?php
namespace App\DTO;
class UserDTO
{
    public function __construct(
        public ?int $id,
        public string $username,
        public string $email,
        public ?string $birthday,
        public array $roles
    ) {}
    public function toArray()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'roles' => $this->roles,
        ];
    }
}