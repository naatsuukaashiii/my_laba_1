<?php
namespace App\DTO;
class UserResourceDTO
{
    public function __construct(
        public int $id,
        public string $username,
        public string $email,
        public string $birthday
    ) {}
}
