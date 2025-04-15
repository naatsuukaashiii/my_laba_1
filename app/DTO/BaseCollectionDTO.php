<?php

namespace App\DTO;

use Illuminate\Support\Collection;

abstract class BaseCollectionDTO
{
    protected $items;
    public function __construct(Collection $items)
    {
        $this->items = $items;
    }
    public function getItems(): Collection
    {
        return $this->items;
    }
    public function toArray(): array
    {
        return $this->items->toArray();
    }
}
