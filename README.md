# 🍎🥕 Fruits and Vegetables

This project implements a service for managing fruits and vegetables inventory. It provides a RESTful API for querying, adding, and removing food items from collections.

## 🎯 Goal
We want to build a service which will take a `request.json` and:
* Process the file and create two separate collections for `Fruits` and `Vegetables`
* Each collection has methods like `add()`, `remove()`, `list()`;
* Units have to be stored as grams;
* Store the collections in a storage engine of your choice. (e.g. Database, In-memory)
* Provide an API endpoint to query the collections. As a bonus, this endpoint can accept filters to be applied to the returning collection.
* Provide another API endpoint to add new items to the collections (i.e., your storage engine).
* As a bonus you might:
  * consider giving an option to decide which units are returned (kilograms/grams);
  * how to implement `search()` method collections;
  * use latest version of Symfony's to embed your logic 

### ✔️ How can I check if my code is working?
You have two ways of moving on:
* You call the Service from PHPUnit test like it's done in dummy test (just run `bin/phpunit` from the console)

or

* You create a Controller which will be calling the service with a json payload

## 💡 Hints before you start working on it
* Keep KISS, DRY, YAGNI, SOLID principles in mind
* We value a clean domain model, without unnecessary code duplication or complexity
* Think about how you will handle input validation
* Follow generally-accepted good practices, such as no logic in controllers, information hiding (see the first hint).
* Timebox your work - we expect that you would spend between 3 and 4 hours.
* Your code should be tested
* We don't care how you handle data persistence, no bonus points for having a complex method

## 🐳 Docker Compose
You can also use Docker Compose to run the application, which simplifies the commands.

### 🧱 Building and starting the application
```bash
docker-compose up -d
# Open http://127.0.0.1:8080 in your browser
```

### 🛂 Running tests with Docker Compose
```bash
docker-compose run --rm php bin/phpunit
```

### 🛑 Stopping the application
```bash
docker-compose down
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
