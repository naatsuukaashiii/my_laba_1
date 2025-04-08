<?php
namespace App\DTO;

class LoginResourceDTO
{
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
