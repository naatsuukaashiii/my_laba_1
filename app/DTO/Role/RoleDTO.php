<?php

namespace App\DTO\Role;

use App\DTO\BaseDTO;

class RoleDTO extends BaseDTO
{
    private $name;
    private $code;
    private $description;

    public function __construct(
        string $name,
        string $code,
        ?string $description = null
    ) {
        $this->name = $name;
        $this->code = $code;
        $this->description = $description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
