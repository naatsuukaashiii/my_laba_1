<?php

namespace App\DTO\Permission;

use App\DTO\BaseDTO;

class PermissionDTO extends BaseDTO
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
