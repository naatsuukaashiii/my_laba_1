<?php
namespace App\DTO;
class RoleDTO
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $description,
        public string $code,
        public array $permissions
    ) {}
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'code' => $this->code,
            'permissions' => $this->permissions,
        ];
    }
}