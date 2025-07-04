<?php

declare(strict_types=1);

namespace App\Storage;

use App\Collection\FoodCollection;
use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Model\FoodItem;
class FoodStorage implements StorageInterface
{

    /** @var FoodItem[] */
    private array $items = [];

    public function store(FoodItem $item): bool
    {
        $this->items[$item->getId()] = $item;

        return true;
    }

    public function remove(int $id): bool
    {
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
            return true;
        }

        return false;
    }

    public function getById(int $id): ?FoodItem
    {
        return $this->items[$id] ?? null;
    }

    public function getAll(): FoodCollection
    {
        $collection = new FoodCollection();
        foreach ($this->items as $item) {
            $collection->add($item);
        }

        return $collection;
    }

    public function getFruits(): FoodCollection
    {
        $collection = new FruitCollection();
        foreach ($this->items as $item) {
            $collection->add($item);
        }

        return $collection;
    }

    public function getVegetables(): FoodCollection
    {
        $collection = new VegetableCollection();
        foreach ($this->items as $item) {
            $collection->add($item);
        }

        return $collection;
    }

    public function search(string $name): FoodCollection
    {
        $collection = new FoodCollection();
        foreach ($this->items as $item) {
            if (stripos($item->getName(), $name) !== false) {
                $collection->add($item);
            }
        }

        return $collection;
    }
}