<?php

declare(strict_types=1);

namespace App\Collection;

use App\Model\FoodItem;

class VegetableCollection extends FoodCollection
{
    public function add(FoodItem $item): self
    {
        if ($item->getType() === 'vegetable') {
            return parent::add($item);
        }
        
        return $this;
    }
}