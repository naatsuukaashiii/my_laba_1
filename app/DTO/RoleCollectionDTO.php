<?php
namespace App\DTO;
class RoleCollectionDTO
{
    public function __construct(public array $roles) {}
    public function toArray()
    {
        return ['roles' => $this->roles];
    }
}