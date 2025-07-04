<?php

declare(strict_types=1);

namespace App\Collection;

use App\Model\FoodItem;

class FoodCollection
{
    /** @var FoodItem[] */
    private array $items = [];

    public function add(FoodItem $item): self
    {
        $this->items[$item->getId()] = $item;
        return $this;
    }

    public function list(string $unit = 'g'): array
    {
        $result = [];
        foreach ($this->items as $item) {
            $result[] = $item->toArray($unit);
        }
        return $result;
    }
}