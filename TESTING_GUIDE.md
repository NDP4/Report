# API Testing Guide - Telkomsel Monitoring Dashboard

Panduan lengkap untuk testing API Telkomsel Monitoring Dashboard menggunakan berbagai tools.

## 🧪 Manual Testing dengan cURL

### 1. Test Login (Admin)

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin",
    "password": "password"
  }'
```

**Expected Response:**

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

### 2. Test Login (Regular User)

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "user",
    "password": "password"
  }'
```

### 3. Test Get User Info

```bash
# Ganti YOUR_JWT_TOKEN dengan token dari response login
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

**Expected Response:**

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

### 4. Test Refresh Token

```bash
curl -X POST http://localhost:8000/api/auth/refresh \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

### 5. Test Logout

```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

**Expected Response:**

```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

## 🚀 Testing dengan Postman

### Setup

1. Import collection: `Telkomsel_Monitoring_Dashboard_API.postman_collection.json`
2. Import environment: `Telkomsel_Monitoring_Dashboard.postman_environment.json`
3. Select environment yang sudah di-import

### Test Flow

1. **Login** - Jalankan request "Login" (token otomatis tersimpan)
2. **Get User Info** - Test endpoint protected
3. **Refresh Token** - Test token refresh
4. **Logout** - Test logout (token otomatis dihapus)

### Variables yang Tersedia

-   `{{base_url}}` - URL base API
-   `{{access_token}}` - JWT token (auto-managed)
-   `{{admin_username}}` / `{{admin_password}}` - Admin credentials
-   `{{user_username}}` / `{{user_password}}` - User credentials

## 🔍 Testing dengan Laravel Artisan

### Run Built-in Tests

```bash
php artisan test --filter AuthTest
```

### Manual Database Check

```bash
# Check users
php artisan tinker
>>> App\Models\User::all();

# Check if JWT is working
>>> use Tymon\JWTAuth\Facades\JWTAuth;
>>> $user = App\Models\User::first();
>>> $token = JWTAuth::fromUser($user);
>>> echo $token;
```

## 🛠️ Troubleshooting

### Common Issues

#### 1. "Route not found"

**Problem:** API routes tidak terdaftar  
**Solution:**

```bash
php artisan route:list --name=auth
```

#### 2. "Token mismatch" atau "Unauthenticated"

**Problem:** JWT configuration issue  
**Solution:**

```bash
php artisan jwt:secret
php artisan config:cache
php artisan cache:clear
```

#### 3. "Connection refused"

**Problem:** Server tidak running  
**Solution:**

```bash
php artisan serve
```

#### 4. "Invalid credentials" untuk user yang valid

**Problem:** Password hash mismatch  
**Solution:**

```bash
php artisan db:seed --class=UserSeeder
```

### Environment Check

```bash
# Check JWT secret
php artisan tinker
>>> config('jwt.secret');

# Check database connection
>>> \DB::connection()->getPdo();

# Check if users exist
>>> App\Models\User::count();
```

## 📊 Test Results Validation

### Success Indicators

-   ✅ Status code 200 untuk successful requests
-   ✅ Response mengandung `"success": true`
-   ✅ JWT token di-return saat login
-   ✅ Protected endpoints memerlukan valid token
-   ✅ Token invalidation pada logout

### Performance Benchmarks

-   Login response time: < 200ms
-   Protected endpoint response: < 100ms
-   Token refresh: < 150ms

## 🔐 Security Testing

### Test Invalid Credentials

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin",
    "password": "wrongpassword"
  }'
```

**Expected:** 401 Unauthorized

### Test Invalid Token

```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer invalid_token" \
  -H "Content-Type: application/json"
```

**Expected:** 401 Unauthorized

### Test Missing Token

```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Content-Type: application/json"
```

**Expected:** 401 Unauthorized

## 📝 Test Checklist

### Authentication Flow

-   [ ] Login dengan admin credentials
-   [ ] Login dengan user credentials
-   [ ] Login dengan invalid credentials (should fail)
-   [ ] Get user info dengan valid token
-   [ ] Get user info dengan invalid token (should fail)
-   [ ] Refresh token dengan valid token
-   [ ] Refresh token dengan expired token
-   [ ] Logout dengan valid token
-   [ ] Access protected endpoint setelah logout (should fail)

### Validation Testing

-   [ ] Login tanpa username (should fail)
-   [ ] Login tanpa password (should fail)
-   [ ] Login dengan password < 6 karakter (should fail)
-   [ ] Login dengan empty request body (should fail)

### Token Management

-   [ ] Token expires setelah 1 jam
-   [ ] Refresh token menghasilkan token baru
-   [ ] Token lama tidak valid setelah refresh
-   [ ] Logout menginvalidate token

## 🎯 Next Steps

Setelah testing authentication berhasil, langkah selanjutnya:

1. **Service Ticket CRUD** - Implement endpoints untuk ticket management
2. **Agent Log Management** - Endpoints untuk logging aktivitas agent
3. **Reporting** - Dashboard data dan analytics
4. **Real-time Updates** - WebSocket untuk notifikasi
5. **File Upload** - Attachment untuk tickets

---

**Testing Completed:** ✅  
**Last Updated:** Desember 2024
