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

The following screenshots demonstrate the successful testing of the Filipino Cookbook API using Thunder Client.

---

## A1. Welcome Route

Returns the welcome message from the public endpoint.

![A1 - Welcome Route](screenshots/A1.png)

---

## A2. Get All Foods

Successfully retrieves all Filipino food records from the database.

![A2 - Get All Foods](screenshots/A2.png)

---

## A3. Get Food by ID

Successfully retrieves a specific Filipino food using its unique ID.

![A3 - Get Food by ID](screenshots/A3.png)

---

## A4. Search Food

Successfully searches for a Filipino dish using its name.

![A4 - Search Food](screenshots/A4.png)

---

## A5. Get Categories

Displays all available food categories.

![A5 - Get Categories](screenshots/A5.png)

---

## A6. Get Ingredients

Displays all available ingredients stored in the database.

![A6 - Get Ingredients](screenshots/A6.png)

---

## A7. Add New Food (POST)

Successfully adds a new Filipino food record to the database.

![A7 - Add New Food](screenshots/A7.png)

---

## A8. Update Food (PUT)

Successfully updates an existing Filipino food record.

![A8 - Update Food](screenshots/A8.png)

---

## A9. Delete Food (DELETE)

Successfully deletes the selected Filipino food record.

![A9 - Delete Food](screenshots/A9.png)

---

## A10. Invalid or Missing Token

Shows the authentication error returned when an invalid or missing Bearer Token is used.

![A10 - Authentication Error](screenshots/A10.png)

---

## A11. Resource Not Found

Demonstrates the API response when a requested food record or search result does not exist.

![A11 - Resource Not Found](screenshots/A11.png)

---

**Figure 1. Successful retrieval of all Filipino foods using the GET /api/foods endpoint.**

---

# 12. Developer Information

**Student Name:**
> YOUR NAME

**Course and Section:**
> BS Information Technology

**Subject:**
> API Development

**Instructor:**
> _______________________

**GitHub Username:**
> YOUR_GITHUB_USERNAME

**Repository Link:**

```
https://github.com/YOUR_USERNAME/filipino-cookbook-api
```

**Date Completed**

```
July 2026
```

---

# License

This project was developed for educational purposes as part of the API Development Laboratory Activity using the Slim Framework.
