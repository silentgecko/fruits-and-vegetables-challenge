<?php

namespace App\Tests\App\Service;

use App\Service\StorageService;
use PHPUnit\Framework\TestCase;

class StorageServiceTest extends TestCase
{
    private string $testFile;

    public function setUp(): void
    {
        $this->testFile = __DIR__ . '/../../../request.json';

        parent::setUp();
    }

    /**
     * @throws \Exception
     */

    public function testReceivingRequest(): void
    {
        $storageService = new StorageService($this->testFile);

        $this->assertNotEmpty($storageService->getAll());
        $this->assertIsArray($storageService->getAll());
    }

    /**
     * @throws \Exception
     */
    public function testProcessRequest(): void
    {
        $storageService = new StorageService($this->testFile);

        // Test that fruits and vegetables are properly separated
        $fruits = $storageService->getFruits();
        $vegetables = $storageService->getVegetables();

        $this->assertNotEmpty($fruits);
        $this->assertNotEmpty($vegetables);

        // Check that all fruits have type 'fruit'
        foreach ($fruits as $fruit) {
            $this->assertEquals('fruit', $fruit['type']);
        }

        // Check that all vegetables have type 'vegetable'
        foreach ($vegetables as $vegetable) {
            $this->assertEquals('vegetable', $vegetable['type']);
        }
    }

    /**
     * @throws \Exception
     */
    public function testUnitConversion(): void
    {
        $storageService = new StorageService($this->testFile);

        // Get fruits in grams
        $fruitsInGrams = $storageService->getFruits();

        // Get fruits in kilograms
        $fruitsInKilograms = $storageService->getFruits('kg');

        // Check that the quantities are properly converted
        foreach ($fruitsInGrams as $index => $fruitInGrams) {
            $fruitInKilograms = $fruitsInKilograms[$index];

            $this->assertEquals($fruitInGrams['id'], $fruitInKilograms['id']);
            $this->assertEquals($fruitInGrams['name'], $fruitInKilograms['name']);
            $this->assertEquals($fruitInGrams['type'], $fruitInKilograms['type']);

            // Check that the quantity in kilograms is 1/1000 of the quantity in grams
            $this->assertEquals($fruitInGrams['quantity'] / 1000, $fruitInKilograms['quantity']);
            $this->assertEquals('g', $fruitInGrams['unit']);
            $this->assertEquals('kg', $fruitInKilograms['unit']);
        }
    }

    /**
     * @throws \Exception
     */
    public function testSearch(): void
    {
        $storageService = new StorageService($this->testFile);

        // Search for items containing 'apple'
        $apples = $storageService->search('apple');

        // Check that all results contain 'apple' in their name (case-insensitive)
        foreach ($apples as $apple) {
            $this->assertStringContainsStringIgnoringCase('apple', $apple['name']);
        }
    }


    /**
     * @throws \Exception
     */
    public function testAddAndRemoveItem(): void
    {
        $storageService = new StorageService($this->testFile);

        // Add a new fruit
        $newFruit = [
            'id' => 100,
            'name' => 'Test Fruit',
            'type' => 'fruit',
            'quantity' => 500,
            'unit' => 'g'
        ];

        $this->assertTrue($storageService->addItem($newFruit));

        // Check that the fruit was added
        $fruits = $storageService->getFruits();
        $found = false;
        foreach ($fruits as $fruit) {
            if ($fruit['id'] === 100) {
                $found = true;
                $this->assertEquals('Test Fruit', $fruit['name']);
                $this->assertEquals('fruit', $fruit['type']);
                $this->assertEquals(500, $fruit['quantity']);
                $this->assertEquals('g', $fruit['unit']);
                break;
            }
        }
        $this->assertTrue($found, 'The added fruit was not found');

        // Remove the fruit
        $this->assertTrue($storageService->removeItem(100));

        // Check that the fruit was removed
        $fruits = $storageService->getFruits();
        foreach ($fruits as $fruit) {
            $this->assertNotEquals(100, $fruit['id']);
        }
    }
}
