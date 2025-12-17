# API Documentation
## JWT E-Commerce API - Complete Reference

**Base URL:** `http://localhost:8000/api`  
**Version:** 1.0.0  
**Authentication:** Bearer Token (JWT)

---

## Table of Contents

1. [Authentication Endpoints](#authentication-endpoints)
2. [Product Endpoints](#product-endpoints)
3. [Order Endpoints](#order-endpoints)
4. [Review Endpoints](#review-endpoints)
5. [Vendor Endpoints](#vendor-endpoints)
6. [Admin Endpoints](#admin-endpoints)
7. [Error Responses](#error-responses)
8. [HTTP Status Codes](#http-status-codes)

---

## Authentication Endpoints

### 1. Register User

Register a new user account.

**Endpoint:** `POST /auth/register`  
**Authentication:** Not required  
**Role:** Public

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "email": "string (required, email, unique)",
  "password": "string (required, min:8, confirmed)",
  "password_confirmation": "string (required)",
  "role": "string (optional, enum: customer|vendor)"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "customer",
    "status": "active"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

---

### 2. Login

Authenticate user and receive JWT token.

**Endpoint:** `POST /auth/login`  
**Authentication:** Not required  
**Role:** Public

**Request Body:**
```json
{
  "email": "string (required, email)",
  "password": "string (required)"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "customer",
    "status": "active"
  }
}
```

**Error Response (401):**
```json
{
  "success": false,
  "message": "Invalid credentials"
}
```

---

### 3. Get Authenticated User

Get details of currently authenticated user.

**Endpoint:** `GET /auth/me`  
**Authentication:** Required  
**Role:** Any authenticated user

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "customer",
    "status": "active",
    "email_verified_at": "2025-12-16T10:00:00.000000Z",
    "created_at": "2025-12-16T09:00:00.000000Z"
  }
}
```

---

### 4. Refresh Token

Refresh JWT token to extend session.

**Endpoint:** `POST /auth/refresh`  
**Authentication:** Required  
**Role:** Any authenticated user

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": { ... }
}
```

---

### 5. Logout

Logout user and invalidate token.

**Endpoint:** `POST /auth/logout`  
**Authentication:** Required  
**Role:** Any authenticated user

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

## Product Endpoints

### 1. List Products

Get paginated list of published products.

**Endpoint:** `GET /products`  
**Authentication:** Not required  
**Role:** Public

**Query Parameters:**
- `search` (string): Search in name and description
- `category` (string): Filter by category slug
- `min_price` (number): Minimum price filter
- `max_price` (number): Maximum price filter
- `sort_by` (string): Sort field (default: created_at)
- `sort_order` (string): Sort direction (asc|desc, default: desc)
- `per_page` (number): Items per page (default: 15)

**Example:** `GET /products?search=laptop&category=electronics&min_price=1000&sort_by=price&sort_order=asc`

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "MacBook Pro 16\"",
      "slug": "macbook-pro-16",
      "description": "Powerful laptop with M2 Pro chip",
      "price": "2499.99",
      "stock_quantity": 10,
      "status": "published",
      "vendor": {
        "id": 2,
        "name": "Tech Vendor"
      },
      "categories": ["Electronics", "Computers"],
      "average_rating": 4.5,
      "reviews_count": 2,
      "created_at": "2025-12-16T10:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 45
  }
}
```

---

### 2. Get Product Details

Get detailed information about a specific product.

**Endpoint:** `GET /products/{id}`  
**Authentication:** Not required  
**Role:** Public

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "MacBook Pro 16\"",
    "slug": "macbook-pro-16",
    "description": "Powerful laptop with M2 Pro chip, 16GB RAM, 512GB SSD",
    "price": "2499.99",
    "stock_quantity": 10,
    "status": "published",
    "vendor": {
      "id": 2,
      "name": "Tech Vendor"
    },
    "categories": [
      {
        "id": 1,
        "name": "Electronics",
        "slug": "electronics"
      }
    ],
    "average_rating": 4.5,
    "reviews_count": 2,
    "reviews": [
      {
        "id": 1,
        "rating": 5,
        "comment": "Excellent laptop!",
        "user": "John Doe",
        "created_at": "2025-12-16T10:00:00.000000Z"
      }
    ],
    "created_at": "2025-12-16T09:00:00.000000Z",
    "updated_at": "2025-12-16T09:00:00.000000Z"
  }
}
```

---

### 3. Create Product

Create a new product (Vendor/Admin only).

**Endpoint:** `POST /products`  
**Authentication:** Required  
**Role:** vendor, admin

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "description": "string (required)",
  "price": "number (required, min:0)",
  "stock_quantity": "integer (required, min:0)",
  "categories": "array (optional, array of category IDs)",
  "status": "string (optional, enum: draft|published|archived)"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "id": 6,
    "vendor_id": 2,
    "name": "Wireless Mouse",
    "slug": "wireless-mouse-abc123",
    "description": "Ergonomic wireless mouse",
    "price": "49.99",
    "stock_quantity": 100,
    "status": "published",
    "categories": [ ... ]
  }
}
```

---

### 4. Update Product

Update an existing product (Owner/Admin only).

**Endpoint:** `PUT /products/{id}`  
**Authentication:** Required  
**Role:** Product owner (vendor) or admin

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "name": "string (optional)",
  "description": "string (optional)",
  "price": "number (optional)",
  "stock_quantity": "integer (optional)",
  "categories": "array (optional)",
  "status": "string (optional)"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Product updated successfully",
  "data": { ... }
}
```

---

### 5. Delete Product

Delete a product (Owner/Admin only).

**Endpoint:** `DELETE /products/{id}`  
**Authentication:** Required  
**Role:** Product owner (vendor) or admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```

---

## Order Endpoints

### 1. List Orders

Get user's orders.

**Endpoint:** `GET /orders`  
**Authentication:** Required  
**Role:** customer, vendor, admin

**Headers:**
```
Authorization: Bearer {token}
```

**Behavior:**
- **Customer**: Sees only their own orders
- **Vendor**: Sees orders containing their products
- **Admin**: Sees all orders

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "order_number": "ORD-ABC123",
      "total_amount": "2899.97",
      "status": "processing",
      "items_count": 3,
      "customer": {
        "id": 4,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "created_at": "2025-12-16T10:00:00.000000Z",
      "updated_at": "2025-12-16T11:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "total": 25
  }
}
```

---

### 2. Create Order

Place a new order.

**Endpoint:** `POST /orders`  
**Authentication:** Required  
**Role:** Any authenticated user

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "items": [
    {
      "product_id": "integer (required, exists in products)",
      "quantity": "integer (required, min:1)"
    }
  ]
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "id": 3,
    "order_number": "ORD-XYZ789",
    "total_amount": "2899.97",
    "status": "pending",
    "items": [
      {
        "product": "MacBook Pro 16\"",
        "quantity": 1,
        "price": "2499.99",
        "subtotal": "2499.99"
      },
      {
        "product": "Wireless Headphones",
        "quantity": 2,
        "price": "199.99",
        "subtotal": "399.98"
      }
    ],
    "created_at": "2025-12-16T10:00:00.000000Z"
  }
}
```

**Error Response (500):**
```json
{
  "success": false,
  "message": "Failed to create order",
  "error": "Insufficient stock for MacBook Pro 16\""
}
```

---

### 3. Get Order Details

Get detailed information about a specific order.

**Endpoint:** `GET /orders/{id}`  
**Authentication:** Required  
**Role:** Order owner, vendor (if order contains their products), admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "order_number": "ORD-ABC123",
    "total_amount": "2899.97",
    "status": "processing",
    "customer": {
      "id": 4,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "items": [
      {
        "id": 1,
        "product": {
          "id": 1,
          "name": "MacBook Pro 16\"",
          "vendor": "Tech Vendor"
        },
        "quantity": 1,
        "price": "2499.99",
        "subtotal": "2499.99"
      }
    ],
    "created_at": "2025-12-16T10:00:00.000000Z",
    "updated_at": "2025-12-16T11:00:00.000000Z"
  }
}
```

---

### 4. Update Order Status

Update order status (Vendor/Admin only).

**Endpoint:** `PUT /orders/{id}/status`  
**Authentication:** Required  
**Role:** vendor, admin

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "status": "string (required, enum: pending|processing|shipped|delivered|cancelled)"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Order status updated successfully",
  "data": { ... }
}
```

---

### 5. Cancel Order

Cancel an order.

**Endpoint:** `DELETE /orders/{id}`  
**Authentication:** Required  
**Role:** Order owner or admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Order cancelled successfully"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Order cannot be cancelled. Current status: delivered"
}
```

---

## Review Endpoints

### 1. Get Product Reviews

Get all reviews for a product.

**Endpoint:** `GET /products/{productId}/reviews`  
**Authentication:** Required  
**Role:** Any authenticated user

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "rating": 5,
      "comment": "Excellent product!",
      "user": {
        "id": 4,
        "name": "John Doe"
      },
      "created_at": "2025-12-16T10:00:00.000000Z",
      "updated_at": "2025-12-16T10:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 1,
    "total": 5,
    "average_rating": 4.5
  }
}
```

---

### 2. Create Review

Add a review for a product.

**Endpoint:** `POST /products/{productId}/reviews`  
**Authentication:** Required  
**Role:** Any authenticated user

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "rating": "integer (required, min:1, max:5)",
  "comment": "string (optional, max:1000)"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Review created successfully",
  "data": {
    "id": 6,
    "rating": 5,
    "comment": "Amazing product!",
    "user": "John Doe",
    "created_at": "2025-12-16T10:00:00.000000Z"
  }
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "You have already reviewed this product"
}
```

---

### 3. Update Review

Update an existing review.

**Endpoint:** `PUT /reviews/{id}`  
**Authentication:** Required  
**Role:** Review owner or admin

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
  "rating": "integer (optional, min:1, max:5)",
  "comment": "string (optional, max:1000)"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Review updated successfully",
  "data": { ... }
}
```

---

### 4. Delete Review

Delete a review.

**Endpoint:** `DELETE /reviews/{id}`  
**Authentication:** Required  
**Role:** Review owner or admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Review deleted successfully"
}
```

---

## Vendor Endpoints

### 1. Get Vendor Products

Get all products created by the vendor.

**Endpoint:** `GET /vendor/products`  
**Authentication:** Required  
**Role:** vendor, admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "MacBook Pro 16\"",
      "price": "2499.99",
      "stock_quantity": 10,
      "status": "published",
      "categories": [...],
      "reviews_count": 5,
      "average_rating": 4.5
    }
  ]
}
```

---

### 2. Get Vendor Orders

Get orders containing vendor's products.

**Endpoint:** `GET /vendor/orders`  
**Authentication:** Required  
**Role:** vendor, admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [ ... ]
}
```

---

## Admin Endpoints

### 1. Get All Users

List all users in the system.

**Endpoint:** `GET /admin/users`  
**Authentication:** Required  
**Role:** admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "customer",
      "status": "active",
      "created_at": "2025-12-16T10:00:00.000000Z"
    }
  ]
}
```

---

### 2. Get System Statistics

Get overall system statistics.

**Endpoint:** `GET /admin/statistics`  
**Authentication:** Required  
**Role:** admin

**Headers:**
```
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "total_users": 100,
    "total_products": 250,
    "total_orders": 450,
    "total_reviews": 320,
    "pending_orders": 25
  }
}
```

---

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation errors",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Unauthorized. You do not have permission to access this resource.",
  "required_role": ["admin"],
  "your_role": "customer"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Product not found"
}
```

### Token Expired (401)
```json
{
  "success": false,
  "message": "Token has expired",
  "error": "token_expired"
}
```

### Token Invalid (401)
```json
{
  "success": false,
  "message": "Token is invalid",
  "error": "token_invalid"
}
```

---

## HTTP Status Codes

| Code | Meaning | Usage |
|------|---------|-------|
| 200 | OK | Successful GET, PUT, DELETE |
| 201 | Created | Successful POST (resource created) |
| 400 | Bad Request | Invalid request data |
| 401 | Unauthorized | Missing or invalid token |
| 403 | Forbidden | Insufficient permissions |
| 404 | Not Found | Resource doesn't exist |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Server error |

---

## Rate Limiting

- **General API**: 60 requests per minute per IP
- **Login Endpoint**: 5 requests per minute per IP

**Rate Limit Headers:**
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 58
Retry-After: 43
```

---

## Pagination

Most list endpoints support pagination:

**Query Parameters:**
- `page` (integer): Page number (default: 1)
- `per_page` (integer): Items per page (default: 15, max: 100)

**Response Meta:**
```json
{
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 72
  }
}
```

---

**API Version:** 1.0.0  
**Last Updated:** December 16, 2025  
**Maintained By:** Senior Laravel Architect
