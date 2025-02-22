<?php
namespace App\DTO;
class UserRoleDTO
{
    public function __construct(
        public ?int $user_id,
        public ?int $role_id
    ) {}
    public function toArray()
    {
        return [
            'user_id' => $this->user_id,
            'role_id' => $this->role_id,
        ];
    }
}