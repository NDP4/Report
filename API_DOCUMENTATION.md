# Telkomsel Monitoring Dashboard API Documentation

## Overview

This API provides authentication and data management endpoints for the Telkomsel Monitoring Dashboard built with Laravel 11 and MySQL. The API uses JWT (JSON Web Token) for authentication.

**Base URL:** `http://localhost:8000/api`  
**Authentication:** JWT Bearer Token  
**Content-Type:** `application/json`

---

## Authentication Endpoints

### 1. Login

**Endpoint:** `POST /auth/login`  
**Description:** Authenticate user and receive JWT token  
**Authentication Required:** No

#### Request Body

```json
{
    "username": "string",
    "password": "string"
}
```

#### Request Example

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin",
    "password": "password"
  }'
```

#### Success Response (200)

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600,
        "user": {
            "id": 1,
            "username": "admin",
            "role": "admin"
        }
    }
}
```

#### Error Responses

**Validation Error (400)**

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "username": ["The username field is required."],
        "password": ["The password field is required."]
    }
}
```

**Invalid Credentials (401)**

```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

---

### 2. Get User Info

**Endpoint:** `GET /auth/me`  
**Description:** Get authenticated user information  
**Authentication Required:** Yes (JWT Token)

#### Request Headers

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

#### Request Example

```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -H "Content-Type: application/json"
```

#### Success Response (200)

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "username": "admin",
            "role": "admin"
        }
    }
}
```

#### Error Response (401)

```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

---

### 3. Refresh Token

**Endpoint:** `POST /auth/refresh`  
**Description:** Refresh JWT token  
**Authentication Required:** Yes (JWT Token)

#### Request Headers

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

#### Request Example

```bash
curl -X POST http://localhost:8000/api/auth/refresh \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -H "Content-Type: application/json"
```

#### Success Response (200)

```json
{
    "success": true,
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

#### Error Response (401)

```json
{
    "success": false,
    "message": "Token could not be refreshed"
}
```

---

### 4. Logout

**Endpoint:** `POST /auth/logout`  
**Description:** Invalidate current JWT token  
**Authentication Required:** Yes (JWT Token)

#### Request Headers

```
Authorization: Bearer {jwt_token}
Content-Type: application/json
```

#### Request Example

```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." \
  -H "Content-Type: application/json"
```

#### Success Response (200)

```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

#### Error Response (500)

```json
{
    "success": false,
    "message": "Failed to logout"
}
```

---

## Database Schema

### Users Table

| Column        | Type                  | Description        |
| ------------- | --------------------- | ------------------ |
| id            | BIGINT                | Primary key        |
| username      | VARCHAR               | Unique username    |
| password_hash | VARCHAR               | Hashed password    |
| role          | ENUM('admin', 'user') | User role          |
| created_at    | TIMESTAMP             | Creation timestamp |
| updated_at    | TIMESTAMP             | Update timestamp   |

### Service Ticket Table

| Column          | Type      | Description                           |
| --------------- | --------- | ------------------------------------- |
| ticket_id       | BIGINT    | Primary key                           |
| user_id         | BIGINT    | Foreign key to users                  |
| subject         | VARCHAR   | Ticket subject                        |
| description     | TEXT      | Ticket description                    |
| category_id     | BIGINT    | Foreign key to ticket_category        |
| subcategory_id  | BIGINT    | Foreign key to ticket_subcategory     |
| status_id       | BIGINT    | Foreign key to ticket_status          |
| source_id       | BIGINT    | Foreign key to ticket_source          |
| assigned_to     | BIGINT    | Foreign key to users (assigned agent) |
| priority_id     | BIGINT    | Foreign key to priority_level         |
| date_open       | DATETIME  | Ticket open date                      |
| date_close      | DATETIME  | Ticket close date                     |
| sla_minutes     | INT       | SLA in minutes                        |
| time_to_resolve | INT       | Actual resolution time                |
| sla_met         | TINYINT   | Whether SLA was met (boolean)         |
| created_at      | TIMESTAMP | Creation timestamp                    |
| updated_at      | TIMESTAMP | Update timestamp                      |

### Other Lookup Tables

-   **roster_code**: Agent roster codes and schedules
-   **agent_log**: Agent activity logging
-   **ticket_category**: Ticket categories
-   **ticket_subcategory**: Ticket subcategories
-   **ticket_status**: Ticket statuses
-   **ticket_source**: Ticket sources
-   **priority_level**: Priority levels

---

## Authentication Flow

1. **Login**: Send username and password to `/auth/login`
2. **Store Token**: Save the received JWT token
3. **API Calls**: Include token in `Authorization: Bearer {token}` header
4. **Refresh**: Use `/auth/refresh` to get new token before expiry
5. **Logout**: Call `/auth/logout` to invalidate token

---

## Error Handling

All API responses follow a consistent format:

### Success Response Format

```json
{
    "success": true,
    "message": "Optional success message",
    "data": {
        // Response data here
    }
}
```

### Error Response Format

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

### HTTP Status Codes

-   `200` - Success
-   `400` - Bad Request (validation errors)
-   `401` - Unauthorized (invalid credentials or token)
-   `403` - Forbidden (insufficient permissions)
-   `404` - Not Found
-   `500` - Internal Server Error

---

## Testing with Postman

### Environment Variables

```
base_url: http://localhost:8000/api
token: {{access_token}}
```

### Test Collection Steps

1. **Login** - POST `{{base_url}}/auth/login`
    - Set `access_token` variable from response
2. **Get User Info** - GET `{{base_url}}/auth/me`
    - Header: `Authorization: Bearer {{token}}`
3. **Refresh Token** - POST `{{base_url}}/auth/refresh`
    - Header: `Authorization: Bearer {{token}}`
4. **Logout** - POST `{{base_url}}/auth/logout`
    - Header: `Authorization: Bearer {{token}}`

---

## Security Considerations

-   JWT tokens expire after 1 hour (3600 seconds)
-   Passwords are hashed using Laravel's built-in Hash facade
-   All protected endpoints require valid JWT token
-   Tokens are invalidated on logout
-   Input validation is implemented on all endpoints

---

## Default Test Users

The system comes with two default users for testing:

| Username | Password | Role  |
| -------- | -------- | ----- |
| admin    | password | admin |
| user     | password | user  |

---

## Next Steps for Development

This API documentation covers the authentication system. Future endpoints to be implemented:

-   Service Ticket CRUD operations
-   Agent Log management
-   Roster Code management
-   Reporting and dashboard data endpoints
-   File upload for ticket attachments
-   Real-time notifications

---

## Support

For technical support or questions about this API, please contact the development team.

**Last Updated:** December 2024  
**API Version:** 1.0  
**Laravel Version:** 11.x
