<?php
namespace App\DTO;
class UserCollectionDTO
{
    public function __construct(public array $users) {}
    public function toArray()
    {
        return ['users' => $this->users];
    }
}