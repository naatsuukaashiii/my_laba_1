<?php
namespace App\DTO;
class LoginResourceDTO
{
    public function __construct(public string $token) {}
}
