<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\FoodItem;
use App\Storage\FoodStorage;
use App\Storage\StorageInterface;

class StorageService
{
    /**
     * @throws \Exception
     */
    public function __construct(
        private string $foodDataJson,
        private ?StorageInterface $storage = null,
    )
    {
        $this->storage = $storage ?? new FoodStorage();
        $this->processData(file_get_contents($foodDataJson));
    }

    /**
     * @throws \Exception
     */
    private function processData(string $json): void
    {
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new \Exception('Invalid data, json error: ' . json_last_error_msg());
        }

        foreach ($data as $item) {
            $foodItem = FoodItem::fromArray($item);
            $this->storage->store($foodItem);
        }
    }

    public function getFruits(string $unit = 'g'): array
    {
        return $this->storage->getFruits()->list($unit);
    }

    public function getVegetables(string $unit = 'g'): array
    {
        return $this->storage->getVegetables()->list($unit);
    }

    public function getAll(string $unit = 'g'): array
    {
        return $this->storage->getAll()->list($unit);
    }

    public function search(string $name, string $unit = 'g'): array
    {
        return $this->storage->search($name)->list($unit);
    }

    public function addItem(array $data): bool
    {
        $foodItem = FoodItem::fromArray($data);
        if ($this->storage->store($foodItem)) {
            return $this->save();
        }

        return false;
    }

    public function removeItem(int $id): bool
    {
        if ($this->storage->remove($id)) {
            return $this->save();
        }

        return false;
    }

    private function save(): bool
    {
        $items = $this->storage->getAll()->list();
        $data = json_encode($items, JSON_PRETTY_PRINT);
        file_put_contents($this->foodDataJson, $data);
        return true;
    }
}
