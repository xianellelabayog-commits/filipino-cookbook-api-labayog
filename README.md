# Filipino Cookbook API

A secured RESTful API developed using the **Slim Framework** that provides information about traditional Filipino dishes, their categories, origins, and ingredients. The API supports CRUD (Create, Read, Update, Delete) operations and protects all API endpoints using Bearer Token Authentication.

> **Security Notice**
>
> This repository does **not** include real database passwords, authentication tokens, API keys, personal access tokens, or server credentials. Sensitive configuration values have been replaced with placeholders to follow secure development practices.

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
git clone https://github.com/xianellelabayog-commits/filipino-cookbook-api-labayog.git
```

## Step 2. Open Project

```bash
cd filipino-cookbook-api-labayog
```

## Step 3. Install Dependencies

```bash
composer install
```

## Step 4. Import Database

Import the provided SQL file into MySQL.

**Database Name**

```
filipino_cookbook_api
```

## Step 5. Configure Database

Create a local configuration file or use environment variables to store your database credentials.

Example:

```env
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_database_username
DB_PASS=your_database_password
```

> **Important:** Do **not** upload configuration files containing real credentials to GitHub.

## Step 6. Start Local Server

```bash
cd C:\xampp\htdocs\filipino-cookbook-api-labayog

php -S localhost:8000 -t public
```

The API will be available at:

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

All `/api` endpoints require a valid Bearer Token except the Welcome Route.

### Required Headers

```http
Authorization: Bearer YOUR_API_TOKEN
Accept: application/json
Content-Type: application/json
```

> **Security Notice:** The actual Bearer Token used during development is intentionally excluded from this repository. Configure your own token locally or store it securely using environment variables.

If authentication fails, the API returns:

```json
{
    "status": "error",
    "message": "Unauthorized access."
}
```

---

# 9. Endpoint Documentation

## 1. Welcome Route

### GET /

Returns the public welcome message.

**Request**

```
GET http://localhost:8000/
```

**Response**

```json
{
    "message":"Welcome to the Secured Filipino Cookbook API",
    "note":"Use a valid Bearer token to access /api endpoints."
}
```

---

## 2. Get All Foods

### GET /api/foods

**Headers**

```
Authorization: Bearer YOUR_API_TOKEN
```

**Request**

```
GET http://localhost:8000/api/foods
```

---

## 3. Get Food by ID

### GET /api/foods/{id}

**Existing**

```
GET http://localhost:8000/api/foods/2
```

**Non-existing**

```
GET http://localhost:8000/api/foods/55
```

**404 Response**

```json
{
    "status":"error",
    "message":"Food not found"
}
```

---

## 4. Search Food

### GET /api/foods/search/{name}

**Existing**

```
GET http://localhost:8000/api/foods/search/adobo
```

**Non-existing**

```
GET http://localhost:8000/api/foods/search/curry
```

**404 Response**

```json
{
    "status":"error",
    "message":"No food found matching 'curry'."
}
```

---

## 5. Get Categories

### GET /api/categories

```
GET http://localhost:8000/api/categories
```

---

## 6. Get Ingredients

### GET /api/ingredients

```
GET http://localhost:8000/api/ingredients
```

---

## 7. Add New Food

### POST /api/foods

```
POST http://localhost:8000/api/foods
```

**Body**

```json
{
    "food_name":"Dinengdeng",
    "category_id":3,
    "origin_id":4,
    "instructions":"Boil vegetables with bagoong-based broth and add grilled fish before serving.",
    "ingredient_ids":[10,15,22]
}
```

**Success Response**

```json
{
    "status":"success",
    "message":"Food added successfully."
}
```

---

## 8. Update Food

### PUT /api/foods/{id}

```
PUT http://localhost:8000/api/foods/16
```

**Body**

```json
{
    "food_name":"Dinengdeng Ilocano",
    "category_id":3,
    "origin_id":4,
    "instructions":"Cook the vegetables in bagoong broth and serve with grilled fish.",
    "ingredient_ids":[10,15,22]
}
```

**Success Response**

```json
{
    "status":"success",
    "message":"Food updated successfully."
}
```

---

## 9. Delete Food

### DELETE /api/foods/{id}

```
DELETE http://localhost:8000/api/foods/16
```

**Success Response**

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

The following screenshots present the testing results of the Filipino Cookbook API using Thunder Client.

## A1. Public Welcome Route

**Endpoint**

```
GET http://localhost:8000/
```

<p align="center">
<img src="screenshots/A1.png" width="900">
</p>

---

## A2. Get All Foods

```
GET http://localhost:8000/api/foods
```

<p align="center">
<img src="screenshots/A2.png" width="900">
</p>

---

## A3. Get Food by ID (Existing)

```
GET http://localhost:8000/api/foods/2
```

<p align="center">
<img src="screenshots/A3.png" width="900">
</p>

---

## A4. Get Food by ID (Non-Existing)

```
GET http://localhost:8000/api/foods/55
```

<p align="center">
<img src="screenshots/A4.png" width="900">
</p>

---

## A5. Search Food by Name (Existing)

```
GET http://localhost:8000/api/foods/search/adobo
```

<p align="center">
<img src="screenshots/A5.png" width="900">
</p>

---

## A6. Search Food by Name (Non-Existing)

```
GET http://localhost:8000/api/foods/search/curry
```

<p align="center">
<img src="screenshots/A6.png" width="900">
</p>

---

## A7. Get All Categories

```
GET http://localhost:8000/api/categories
```

<p align="center">
<img src="screenshots/A7.png" width="900">
</p>

---

## A8. Get All Ingredients

```
GET http://localhost:8000/api/ingredients
```

<p align="center">
<img src="screenshots/A8.png" width="900">
</p>

---

## A9. Create New Food (POST)

```
POST http://localhost:8000/api/foods
```

```json
{
  "food_name": "Dinengdeng",
  "category_id": 3,
  "origin_id": 4,
  "instructions": "Boil vegetables with bagoong-based broth and add grilled fish before serving.",
  "ingredient_ids": [10, 15, 22]
}
```

<p align="center">
<img src="screenshots/A9.png" width="900">
</p>

---

## A10. Update Existing Food (PUT)

```
PUT http://localhost:8000/api/foods/16
```

```json
{
  "food_name": "Dinengdeng Ilocano",
  "category_id": 3,
  "origin_id": 4,
  "instructions": "Cook the vegetables in bagoong broth and serve with grilled fish.",
  "ingredient_ids": [10, 15, 22]
}
```

<p align="center">
<img src="screenshots/A10.png" width="900">
</p>

---

## A11. Delete Food (DELETE)

```
DELETE http://localhost:8000/api/foods/16
```

<p align="center">
<img src="screenshots/A11.png" width="900">
</p>

---

# 12. Security

This project follows secure development practices.

The repository **does not include**:

- Database passwords
- Private API keys
- Bearer authentication tokens
- Personal access tokens
- Server credentials
- Environment configuration files

Create your own local configuration (for example, a `.env` file) and ensure it is added to `.gitignore` before pushing your project to GitHub.

Example `.gitignore` entries:

```gitignore
.env
.env.local
config.php
settings.php
```

---

# 13. Developer Information

**Student Name**

> Xianelle Jodi G. Labayog

**Course and Section**

> Bachelor of Science in Information Technology 4C

**GitHub Username**

> xianellelabayog-commits

**Repository**

https://github.com/xianellelabayog-commits/filipino-cookbook-api-labayog

**Date Completed**

```
July 23, 2026
```

---

# License

This project was developed for educational purposes as part of the REST API Development Laboratory Activity using the Slim Framework.
