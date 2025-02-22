<?php
namespace App\DTO;
class RolePermissionDTO
{
    public function __construct(
        public ?int $role_id,
        public ?int $permission_id
    ) {}
    public function toArray()
    {
        return [
            'role_id' => $this->role_id,
            'permission_id' => $this->permission_id,
        ];
    }
}