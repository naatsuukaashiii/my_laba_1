<?php

namespace App\DTO\Role;

use App\DTO\BaseCollectionDTO;
use Illuminate\Support\Collection;

class RoleCollectionDTO extends BaseCollectionDTO
{
    public function __construct(Collection $items)
    {
        parent::__construct($items);
    }
}
