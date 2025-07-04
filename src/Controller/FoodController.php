<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class FoodController extends AbstractController
{
    private StorageService $storageService;
    
    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }
    
    /**
     * @Route("/fruits", name="fruits", methods={"GET"})
     */
    public function getFruits(Request $request): JsonResponse
    {
        return $this->json([
            'fruits' => $this->storageService->getFruits($this->getUnit($request))
        ]);
    }
    
    /**
     * @Route("/vegetables", name="vegetables", methods={"GET"})
     */
    public function getVegetables(Request $request): JsonResponse
    {
        return $this->json([
            'vegetables' => $this->storageService->getVegetables($unit = $this->getUnit($request))
        ]);
    }
    
    /**
     * @Route("/food", name="food", methods={"GET"})
     */
    public function getAllFood(Request $request): JsonResponse
    {
        $unit = $this->getUnit($request);
        $name = $request->query->get('name');
        
        if ($name) {
            return $this->json([
                'food' => $this->storageService->search($name, $unit)
            ]);
        }
        
        return $this->json([
            'food' => $this->storageService->getAll($unit)
        ]);
    }
    
    /**
     * @Route("/food", name="add_food", methods={"POST"})
     */
    public function addFood(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!$data || !isset($data['name']) || !isset($data['type']) || !isset($data['quantity']) || !isset($data['unit'])) {
            return $this->json([
                'error' => 'Invalid data. Required fields: name, type, quantity, unit'
            ], 400);
        }
        
        if (!in_array($data['type'], ['fruit', 'vegetable'])) {
            return $this->json([
                'error' => 'Invalid type. Must be "fruit" or "vegetable"'
            ], 400);
        }
        
        if (!is_numeric($data['quantity']) || $data['quantity'] <= 0) {
            return $this->json([
                'error' => 'Invalid quantity. Must be a positive number'
            ], 400);
        }
        
        if (!in_array($data['unit'], ['g', 'kg'])) {
            return $this->json([
                'error' => 'Invalid unit. Must be "g" or "kg"'
            ], 400);
        }
        
        // Generate a new ID if not provided
        if (!isset($data['id'])) {
            $data['id'] = time();
        }
        
        $success = $this->storageService->addItem($data);
        
        if ($success) {
            return $this->json([
                'success' => true,
                'message' => 'Food item added successfully',
                'id' => $data['id']
            ], 201);
        }
        
        return $this->json([
            'error' => 'Failed to add food item'
        ], 500);
    }
    
    /**
     * @Route("/food/{id}", name="remove_food", methods={"DELETE"})
     */
    public function removeFood(int $id): JsonResponse
    {
        $success = $this->storageService->removeItem($id);
        
        if ($success) {
            return $this->json([
                'success' => true,
                'message' => 'Food item removed successfully'
            ]);
        }
        
        return $this->json([
            'error' => 'Food item not found'
        ], 404);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getUnit(Request $request): string
    {
        $unit = $request->query->get('unit', 'g');
        if (!in_array($unit, ['g', 'kg'])) {
            throw new \InvalidArgumentException('Invalid unit. Must be "g" or "kg"');
        }
        return $unit;
    }
}