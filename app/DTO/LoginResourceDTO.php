<?php
namespace App\DTO;

class LoginResourceDTO
{
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}
