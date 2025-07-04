# 🍎🥕 Fruits and Vegetables

This project implements a service for managing fruits and vegetables inventory. It provides a RESTful API for querying, adding, and removing food items from collections.

It's using php 8.3, phpunit 12 and symfony 6.4 (lts) as base while running in a docker (compose) container

## 🐳 Docker Compose
You can use Docker Compose to run the application.

### 🧱 Building and starting the application
```bash
docker compose up -d
# Open http://127.0.0.1:8080 in your browser
```

### 🛂 Running tests with Docker Compose
```bash
docker compose run --rm php vendor/bin/phpunit
```

### 🛑 Stopping the application
```bash
docker compose down
```

## 🌐 REST API Documentation

The application provides a RESTful API to interact with the food collections. All endpoints are prefixed with `/api`.

### Available Endpoints

#### Get All Fruits
```
GET /api/fruits
```

Query Parameters:
- `unit` (optional): Unit of measurement for quantities. Possible values: `g` (default) or `kg`.

Example Request:
```bash
curl -X GET "http://localhost:8080/api/fruits?unit=kg"
```

Example Response:
```json
{
  "fruits": [
    {
      "id": 2,
      "name": "Apples",
      "type": "fruit",
      "quantity": 20,
      "unit": "kg"
    },
    {
      "id": 3,
      "name": "Pears",
      "type": "fruit",
      "quantity": 3.5,
      "unit": "kg"
    }
  ]
}
```

#### Get All Vegetables
```
GET /api/vegetables
```

Query Parameters:
- `unit` (optional): Unit of measurement for quantities. Possible values: `g` (default) or `kg`.

Example Request:
```bash
curl -X GET "http://localhost:8080/api/vegetables"
```

Example Response:
```json
{
  "vegetables": [
    {
      "id": 1,
      "name": "Carrot",
      "type": "vegetable",
      "quantity": 10922,
      "unit": "g"
    },
    {
      "id": 5,
      "name": "Beans",
      "type": "vegetable",
      "quantity": 65000,
      "unit": "g"
    }
  ]
}
```

#### Get All Food Items
```
GET /api/food
```

Query Parameters:
- `unit` (optional): Unit of measurement for quantities. Possible values: `g` (default) or `kg`.
- `name` (optional): Filter food items by name (case-insensitive partial match).

Example Request (all food items):
```bash
curl -X GET "http://localhost:8080/api/food"
```

Example Request (with name filter):
```bash
curl -X GET "http://localhost:8080/api/food?name=apple&unit=kg"
```

Example Response:
```json
{
  "food": [
    {
      "id": 2,
      "name": "Apples",
      "type": "fruit",
      "quantity": 20,
      "unit": "kg"
    }
  ]
}
```

#### Add a New Food Item
```
POST /api/food
```

Request Body:
```json
{
  "name": "Strawberries",
  "type": "fruit",
  "quantity": 500,
  "unit": "g"
}
```

Required fields:
- `name`: Name of the food item
- `type`: Type of food item. Must be either `fruit` or `vegetable`.
- `quantity`: Quantity of the food item. Must be a positive number.
- `unit`: Unit of measurement. Must be either `g` or `kg`.

Optional fields:
- `id`: Unique identifier for the food item. If not provided, a timestamp will be used.

Example Request:
```bash
curl -X POST "http://localhost:8080/api/food" \
  -H "Content-Type: application/json" \
  -d '{"name":"Strawberries","type":"fruit","quantity":500,"unit":"g"}'
```

Example Response (success):
```json
{
  "success": true,
  "message": "Food item added successfully",
  "id": 1683721234
}
```

Example Response (error):
```json
{
  "error": "Invalid data. Required fields: name, type, quantity, unit"
}
```

#### Remove a Food Item
```
DELETE /api/food/{id}
```

Path Parameters:
- `id`: ID of the food item to remove

Example Request:
```bash
curl -X DELETE "http://localhost:8080/api/food/1"
```

Example Response (success):
```json
{
  "success": true,
  "message": "Food item removed successfully"
}
```

Example Response (error):
```json
{
  "error": "Food item not found"
}
```

### Error Handling

The API returns appropriate HTTP status codes:
- `200 OK`: Request was successful
- `201 Created`: Resource was successfully created
- `400 Bad Request`: Invalid request data
- `404 Not Found`: Resource not found
- `500 Internal Server Error`: Server error
