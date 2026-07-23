# Filipino Cookbook API

## API Description

The **Filipino Cookbook API** is a RESTful web service developed using the **Slim Framework** and **PHP**. It provides information about popular Filipino foods, their categories, origins, ingredients, and cooking instructions.

The API is designed for educational purposes and demonstrates REST API development, database integration, CRUD operations, JSON responses, and token-based authentication.

### Purpose of the API
- Provide access to Filipino food information through REST API endpoints.
- Demonstrate secure API development using Bearer Token authentication.
- Serve as a learning project for API development using Slim Framework.

### Type of Information Provided
- Filipino food recipes
- Food categories
- Food origins
- Ingredients
- Cooking instructions

### Intended Users
- Students
- Web Developers
- Mobile App Developers
- API Learners

### Main Functions
- Retrieve all Filipino foods
- Search foods by name
- Retrieve food details
- Add new food
- Update food information
- Delete food
- Retrieve categories
- Retrieve ingredients
- Authenticate API requests using Bearer Token
- Return responses in JSON format

---

# Features

- Retrieve all Filipino foods
- Retrieve food categories
- Retrieve ingredients
- Search food by name
- View food details
- Add new food
- Update existing food
- Delete food
- Authenticate requests using Bearer Token
- Return JSON responses
- Proper HTTP Status Codes
- Error handling

---

# Technologies Used

| Technology | Purpose |
|------------|---------|
| PHP | Backend Programming Language |
| Slim Framework 4 | REST API Framework |
| MySQL | Database |
| PDO | Database Connection |
| Composer | Dependency Management |
| JSON | Response Format |
| Apache | Web Server |
| XAMPP | Local Development Server |
| Thunder Client / Postman | API Testing |
| Git | Version Control |
| GitHub | Repository Hosting |

---

# Installation Instructions

## Step 1 Clone Repository

```bash
git clone https://github.com/YOUR_USERNAME/filipino-cookbook-api.git
```

## Step 2

```bash
cd filipino-cookbook-api
```

## Step 3 Install Dependencies

```bash
composer install
```

## Step 4 Create Database

Create a MySQL database named

```
filipino_cookbook_api
```

## Step 5 Import SQL File

Import

```
database/filipino_foods_relational.sql
```

using phpMyAdmin.

## Step 6 Configure Database

Open

```
public/index.php
```

Update the database settings if necessary.

```
Host: localhost
Database: filipino_cookbook_api
Username: root
Password:
```

## Step 7 Start Local Server

```bash
php -S localhost:8000 -t public
```

or

```bash
C:\xampp\php\php.exe -S localhost:8000 -t public
```

## Step 8 Test the API

Open

```
http://localhost:8000/
```

You should receive

```json
{
    "message":"Welcome to the Secured Filipino Cookbook API",
    "note":"Use a valid Bearer token to access /api endpoints."
}
```

---

# Database Setup

### Database Name

```
filipino_cookbook_api
```

### SQL File

```
database/filipino_foods_relational.sql
```

### Tables

- foods
- categories
- origins
- ingredients
- food_ingredients

### Table Relationships

```
categories
      |
      |
foods
 /   \
origins
     |
food_ingredients
     |
ingredients
```

Relationship

```
categories -> foods <- origins
foods -> food_ingredients <- ingredients
```

---

# Base URL

```
http://localhost:8000/api
```

---

# Authentication

This API uses **Bearer Token Authentication**.

### Header

```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

Example

```
Authorization: Bearer dmmmsu-cookbook-token-2026
```

If the token is missing or invalid, the API returns

```json
{
    "status":"error",
    "message":"Unauthorized access. Valid API token is required."
}
```

---

# Endpoint Documentation

---

## Public Welcome

### Endpoint

```
GET /
```

Description

Returns the welcome message.

Authentication

Not Required

Example

```
GET http://localhost:8000/
```

---

## Get All Foods

### Endpoint

```
GET /api/foods
```

Required Headers

```
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

Example

```
GET http://localhost:8000/api/foods
```

Success Response

```json
[
  {
    "food_id":1,
    "food_name":"Adobo",
    "category_name":"Main Dish",
    "origin_name":"Luzon"
  }
]
```

---

## Get Food by ID

### Endpoint

```
GET /api/foods/{id}
```

Example

```
GET /api/foods/1
```

404 Response

```json
{
    "status":"error",
    "message":"Food not found"
}
```

---

## Search Food

### Endpoint

```
GET /api/foods/search/{name}
```

Example

```
GET /api/foods/search/adobo
```

404 Response

```json
{
    "status":"error",
    "message":"No food found matching 'adobo'."
}
```

---

## Get Categories

### Endpoint

```
GET /api/categories
```

Returns all categories.

---

## Get Ingredients

### Endpoint

```
GET /api/ingredients
```

Returns all ingredients.

---

## Add Food

### Endpoint

```
POST /api/foods
```

Headers

```
Authorization: Bearer YOUR_ACCESS_TOKEN
Content-Type: application/json
```

Example Body

```json
{
    "food_name":"Dinengdeng",
    "category_id":3,
    "origin_id":4,
    "instructions":"Boil vegetables.",
    "ingredient_ids":[10,15,22]
}
```

Success

```json
{
    "status":"success",
    "message":"Food added successfully."
}
```

---

## Update Food

### Endpoint

```
PUT /api/foods/{id}
```

Example

```
PUT /api/foods/1
```

Returns

```json
{
    "status":"success",
    "message":"Food updated successfully."
}
```

---

## Delete Food

### Endpoint

```
DELETE /api/foods/{id}
```

Returns

```json
{
    "status":"success",
    "message":"Food deleted successfully."
}
```

---

# HTTP Status Codes

| Status Code | Meaning |
|-------------|---------|
| 200 | Request completed successfully |
| 201 | Resource created successfully |
| 400 | Invalid request or parameter |
| 401 | Missing or invalid authentication |
| 403 | Access is forbidden |
| 404 | Resource not found |
| 405 | Method not allowed |
| 409 | Duplicate record / Conflict |
| 429 | Too many requests |
| 500 | Internal server error |

---

# Testing Evidence

Insert the following screenshots.

| Screenshot | Caption |
|------------|---------|
| A1.png | Public Welcome Route |
| A2.png | Get All Foods |
| A3.png | Get Food by ID |
| A4.png | Food Not Found (404) |
| A5.png | Search Food |
| A6.png | Search Food Not Found |
| A7.png | Get Categories |
| A8.png | Get Ingredients |
| A9.png | Add New Food |
| A10.png | Update Food |
| A11.png | Delete Food |

---

# Project Structure

```
filipino-cookbook-api/
│
├── database/
│     └── filipino_foods_relational.sql
│
├── public/
│     └── index.php
│
├── vendor/
│
├── composer.json
│
└── README.md
```

---

# Developer Information

**Student Name:** YOUR NAME

**Course & Section:**
Bachelor of Science in Information Technology

**GitHub Username:**
YOUR_GITHUB_USERNAME

**Repository Link:**

```
https://github.com/YOUR_USERNAME/filipino-cookbook-api
```

**Date Completed:**

```
July 2026
```

---

# Security Notice

For security purposes, this repository does **NOT** include:

- Database passwords
- Private API keys
- Personal access tokens
- Secret authentication tokens
- Private server credentials
- Sensitive configuration files

Always use environment variables or configuration files excluded from Git version control.
