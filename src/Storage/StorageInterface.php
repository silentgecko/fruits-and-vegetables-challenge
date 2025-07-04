<?php

declare(strict_types=1);

namespace App\Storage;

use App\Collection\FoodCollection;
use App\Model\FoodItem;

interface StorageInterface
{
    /**
     * Store a food item
     * 
     * @param FoodItem $item
     * @return bool
     */
    public function store(FoodItem $item): bool;
    
    /**
     * Remove a food item by ID
     * 
     * @param int $id
     * @return bool
     */
    public function remove(int $id): bool;
    
    /**
     * Get a food item by ID
     * 
     * @param int $id
     * @return FoodItem|null
     */
    public function getById(int $id): ?FoodItem;
    
    /**
     * Get all food items
     * 
     * @return FoodCollection
     */
    public function getAll(): FoodCollection;
    
    /**
     * Get all fruits
     * 
     * @return FoodCollection
     */
    public function getFruits(): FoodCollection;
    
    /**
     * Get all vegetables
     * 
     * @return FoodCollection
     */
    public function getVegetables(): FoodCollection;
    
    /**
     * Search for food items by name
     * 
     * @param string $name
     * @return FoodCollection
     */
    public function search(string $name): FoodCollection;
}