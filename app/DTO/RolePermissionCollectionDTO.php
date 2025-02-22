<?php
namespace App\DTO;
class RolePermissionCollectionDTO
{
    public function __construct(public array $rolePermissions) {}
    public function toArray()
    {
        return ['role_permissions' => $this->rolePermissions];
    }
}