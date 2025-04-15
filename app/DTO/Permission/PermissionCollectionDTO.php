<?php

namespace App\DTO\Permission;

use App\DTO\BaseCollectionDTO;
use Illuminate\Support\Collection;

class PermissionCollectionDTO extends BaseCollectionDTO
{
    public function __construct(Collection $items)
    {
        parent::__construct($items);
    }
}
