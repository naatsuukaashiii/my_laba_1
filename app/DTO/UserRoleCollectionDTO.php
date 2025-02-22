<?php
namespace App\DTO;
class UserRoleCollectionDTO
{
    public function __construct(public array $userRoles) {}
    public function toArray()
    {
        return ['user_roles' => $this->userRoles];
    }
}