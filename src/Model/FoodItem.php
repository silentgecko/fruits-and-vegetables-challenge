<?php

declare(strict_types=1);

namespace App\Model;

class FoodItem
{
    private int $id;
    private string $name;
    private string $type;
    private int $quantity; // Stored in grams
    
    public function __construct(int $id, string $name, string $type, int $quantity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->quantity = $quantity;
    }
    
    public function getId(): int
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function getType(): string
    {
        return $this->type;
    }
    
    public function getQuantity(): int
    {
        return $this->quantity;
    }
    
    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getQuantityInUnit(string $unit = 'g'): float
    {
        if ($unit === 'kg') {
            return $this->quantity / 1000;
        }
        
        return $this->quantity;
    }

    public static function fromArray(array $data): self
    {
        $quantity = $data['quantity'];
        
        // Convert to grams if needed
        if ($data['unit'] === 'kg') {
            $quantity *= 1000;
        }
        
        return new self(
            $data['id'],
            $data['name'],
            $data['type'],
            $quantity
        );
    }

    public function toArray(string $unit = 'g'): array
    {
        $quantity = $this->quantity;
        
        if ($unit === 'kg') {
            $quantity = $this->getQuantityInUnit($unit);
        }
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'quantity' => $quantity,
            'unit' => $unit
        ];
    }
}