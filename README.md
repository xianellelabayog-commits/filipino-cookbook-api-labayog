# Filipino Cookbook API

A secured RESTful API developed using the **Slim Framework** that provides information about traditional Filipino dishes, their categories, origins, and ingredients. The API supports CRUD (Create, Read, Update, Delete) operations and protects all API endpoints using Bearer Token Authentication.

---

# 1. API Title

**Filipino Cookbook API**

---

# 2. API Description

The Filipino Cookbook API is a RESTful web service that allows users to access and manage information about Filipino foods. It provides structured JSON responses and uses token-based authentication to secure all API endpoints.

### Purpose of the API
- Provide a centralized database of Filipino dishes.
- Demonstrate REST API development using PHP and Slim Framework.
- Practice CRUD operations with secured API endpoints.

### Type of Information Provided
- Filipino foods
- Food categories
- Food origins
- Ingredients
- Cooking instructions

### Intended Users
- Students learning REST API Development
- Developers integrating Filipino food information
- Instructors and evaluators

### Main Functions
- Retrieve all Filipino foods
- Search foods by name
- Retrieve a specific food
- Retrieve categories
- Retrieve ingredients
- Add new food
- Update existing food
- Delete food
- Authenticate requests using Bearer Token
- Return responses in JSON format

### Technologies Used
- PHP
- Slim Framework
- MySQL
- Composer
- JSON
- Apache
- XAMPP

---

# 3. Features

- Retrieve all Filipino foods
- Search food by name
- Retrieve a specific food
- Retrieve food categories
- Retrieve ingredients
- Add a new food
- Update food information
- Delete food
- Bearer Token Authentication
- JSON Responses
- Proper HTTP Status Codes
- Error Handling

---

# 4. Technologies Used

| Category | Technology |
|-----------|------------|
| Programming Language | PHP 8 |
| Framework | Slim Framework 4 |
| Database | MySQL |
| Dependency Manager | Composer |
| Data Format | JSON |
| Local Server | Apache / PHP Built-in Server |
| Development Environment | Visual Studio Code |
| Local Server Package | XAMPP |
| API Testing | Thunder Client / Postman |
| Version Control | Git |
| Repository Hosting | GitHub |

---

# 5. Installation Instructions

## Step 1. Clone Repository

```bash
git clone https://github.com/YOUR_USERNAME/filipino-cookbook-api.git
```

## Step 2. Open Project

```bash
cd filipino-cookbook-api
```

## Step 3. Install Dependencies

```bash
composer install
```

## Step 4. Import Database

Import the provided SQL file into MySQL.

Database Name

```
filipino_cookbook_api
```

## Step 5. Configure Database

Open:

```
public/index.php
```

Modify the database credentials if needed or configure environment variables:

```
DB_HOST=localhost
DB_NAME=filipino_cookbook_api
DB_USER=root
DB_PASS=
```

If you use XAMPP with the default MySQL setup, `root` and an empty password usually work.

## Step 6. Start Local Server

Open Terminal

```bash
cd C:\filipino-cookbook-api

& "C:\xampp\php\php.exe" -S localhost:8000 -t public
```

The API will run at

```
http://localhost:8000
```

---

# 6. Database Setup

### Database Name

```
filipino_cookbook_api
```

### SQL File

```
filipino_cookbook_api.sql
```

### Tables

- foods
- categories
- origins
- ingredients
- food_ingredients

### Relationships

```
categories
      |
      |
    foods
   /     \
origins  food_ingredients
               |
          ingredients
```

---

# 7. Base URL

```
http://localhost:8000/api
```

---

# 8. Authentication

All API endpoints require a Bearer Token except the Welcome Route.

## Token

```
dmmmsu-cookbook-token-2026
```

## Header

```
Authorization: Bearer dmmmsu-cookbook-token-2026
Accept: application/json
Content-Type: application/json
```

If the token is missing or invalid, the API returns:

```json
{
    "status":"error",
    "message":"Unauthorized access. Valid API token is required."
}
```

---

# 9. Endpoint Documentation

---

## 1. Welcome Route

### GET /

Description

Returns the welcome message.

Request

```
GET http://localhost:8000/
```

Response

```json
{
    "message":"Welcome to the Secured Filipino Cookbook API",
    "note":"Use a valid Bearer token to access /api endpoints."
}
```

---

## 2. Get All Foods

### GET /api/foods

Headers

```
Authorization: Bearer dmmmsu-cookbook-token-2026
```

Request

```
GET http://localhost:8000/api/foods
```

---

## 3. Get Food by ID

### GET /api/foods/{id}

Existing

```
GET http://localhost:8000/api/foods/2
```

Non-existing

```
GET http://localhost:8000/api/foods/55
```

404 Response

```json
{
    "status":"error",
    "message":"Food not found"
}
```

---

## 4. Search Food

### GET /api/foods/search/{name}

Existing

```
GET http://localhost:8000/api/foods/search/adobo
```

Non-existing

```
GET http://localhost:8000/api/foods/search/curry
```

404 Response

```json
{
    "status":"error",
    "message":"No food found matching 'curry'."
}
```

---

## 5. Get Categories

### GET /api/categories

Request

```
GET http://localhost:8000/api/categories
```

---

## 6. Get Ingredients

### GET /api/ingredients

Request

```
GET http://localhost:8000/api/ingredients
```

---

## 7. Add New Food

### POST /api/foods

Request

```
POST http://localhost:8000/api/foods
```

Body

```json
{
    "food_name":"Dinengdeng",
    "category_id":3,
    "origin_id":4,
    "instructions":"Boil vegetables with bagoong-based broth and add grilled fish before serving.",
    "ingredient_ids":[10,15,22]
}
```

Success Response

```json
{
    "status":"success",
    "message":"Food added successfully."
}
```

---

## 8. Update Food

### PUT /api/foods/{id}

Request

```
PUT http://localhost:8000/api/foods/16
```

Body

```json
{
    "food_name":"Dinengdeng Ilocano",
    "category_id":3,
    "origin_id":4,
    "instructions":"Cook the vegetables in bagoong broth and serve with grilled fish.",
    "ingredient_ids":[10,15,22]
}
```

Success Response

```json
{
    "status":"success",
    "message":"Food updated successfully."
}
```

---

## 9. Delete Food

### DELETE /api/foods/{id}

Request

```
DELETE http://localhost:8000/api/foods/16
```

Success Response

```json
{
    "status":"success",
    "message":"Food deleted successfully."
}
```

---

# 10. HTTP Status Codes

| Status Code | Meaning |
|-------------|---------|
| 200 | Request completed successfully |
| 201 | Resource created successfully |
| 400 | Invalid request or parameter |
| 401 | Missing or invalid authentication |
| 404 | Resource not found |
| 405 | Method not allowed |
| 409 | Duplicate resource |
| 500 | Internal server error |

---

# 11. Testing Evidence

The following screenshots present the testing results of the Filipino Cookbook API using Thunder Client. Each test verifies the functionality of a specific API endpoint and its corresponding response.

---

## A1. Public Welcome Route

**Endpoint**

```
GET http://localhost:8000/
```

Returns the public welcome message of the API.

<p align="center">
  <img src="screenshots/A1.png" alt="Public Welcome Route" width="900">
</p>

---

## A2. Get All Foods

**Endpoint**

```
GET http://localhost:8000/api/foods
```

Retrieves all available Filipino food records.

<p align="center">
  <img src="screenshots/A2.png" alt="Get All Foods" width="900">
</p>

---

## A3. Get Food by ID (Existing)

**Endpoint**

```
GET http://localhost:8000/api/foods/2
```

Successfully retrieves the food record with ID **2**.

<p align="center">
  <img src="screenshots/A3.png" alt="Get Food by ID" width="900">
</p>

---

## A4. Get Food by ID (Non-Existing)

**Endpoint**

```
GET http://localhost:8000/api/foods/55
```

Returns a **404 Not Found** response because the requested food does not exist.

<p align="center">
  <img src="screenshots/A4.png" alt="Food Not Found" width="900">
</p>

---

## A5. Search Food by Name (Existing)

**Endpoint**

```
GET http://localhost:8000/api/foods/search/adobo
```

Successfully searches and returns the food named **Adobo**.

<p align="center">
  <img src="screenshots/A5.png" alt="Search Existing Food" width="900">
</p>

---

## A6. Search Food by Name (Non-Existing)

**Endpoint**

```
GET http://localhost:8000/api/foods/search/curry
```

Returns a **404 Not Found** response because no matching food exists.

<p align="center">
  <img src="screenshots/A6.png" alt="Search Food Not Found" width="900">
</p>

---

## A7. Get All Categories

**Endpoint**

```
GET http://localhost:8000/api/categories
```

Displays all available Filipino food categories.

<p align="center">
  <img src="screenshots/A7.png" alt="Get Categories" width="900">
</p>

---

## A8. Get All Ingredients

**Endpoint**

```
GET http://localhost:8000/api/ingredients
```

Displays all available food ingredients.

<p align="center">
  <img src="screenshots/A8.png" alt="Get Ingredients" width="900">
</p>

---

## A9. Create New Food (POST)

**Endpoint**

```
POST http://localhost:8000/api/foods
```

**Request Body**

```json
{
  "food_name": "Dinengdeng",
  "category_id": 3,
  "origin_id": 4,
  "instructions": "Boil vegetables with bagoong-based broth and add grilled fish before serving.",
  "ingredient_ids": [10, 15, 22]
}
```

Successfully creates a new Filipino food record.

<p align="center">
  <img src="screenshots/A9.png" alt="Create New Food" width="900">
</p>

---

## A10. Update Existing Food (PUT)

**Endpoint**

```
PUT http://localhost:8000/api/foods/16
```

**Request Body**

```json
{
  "food_name": "Dinengdeng Ilocano",
  "category_id": 3,
  "origin_id": 4,
  "instructions": "Cook the vegetables in bagoong broth and serve with grilled fish.",
  "ingredient_ids": [10, 15, 22]
}
```

After updating, a **GET** request confirms that the modified food information has been successfully saved.

<p align="center">
  <img src="screenshots/A10.png" alt="Update Food" width="900">
</p>

---

## A11. Delete Food (DELETE)

**Endpoint**

```
DELETE http://localhost:8000/api/foods/16
```

Successfully deletes the selected food record from the database.

<p align="center">
  <img src="screenshots/A11.png" alt="Delete Food" width="900">
</p>

---

---

# 12. Developer Information

**Student Name:**
> Xianelle Jodi G. Labayog

**Course and Section:**
> Bachelor of Science in Information Technology 4C

**GitHub Username:**
> xianellelabayog-commits

**Repository Link:**

```
https://github.com/YOUR_USERNAME/filipino-cookbook-api
```

**Date Completed**

```
July 23, 2026
```

---

# License

This project was developed for educational purposes as part of the API Development Laboratory Activity using the Slim Framework.
