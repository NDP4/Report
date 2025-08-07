# Telkomsel Monitoring Dashboard - API Documentation

Dokumentasi lengkap untuk API Telkomsel Monitoring Dashboard yang dibangun menggunakan Laravel 11 dan MySQL.

## 📋 Daftar File Dokumentasi

1. **[API_DOCUMENTATION.md](./API_DOCUMENTATION.md)** - Dokumentasi lengkap dalam format Markdown
2. **[Telkomsel_Monitoring_Dashboard_API.postman_collection.json](./Telkomsel_Monitoring_Dashboard_API.postman_collection.json)** - Postman Collection untuk testing
3. **[openapi.yaml](./openapi.yaml)** - OpenAPI/Swagger specification

## 🚀 Quick Start

### 1. Menjalankan Server

```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

### 2. Testing dengan cURL

#### Login

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "password"}'
```

#### Get User Info

```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

### 3. Testing dengan Postman

1. Import file `Telkomsel_Monitoring_Dashboard_API.postman_collection.json` ke Postman
2. Jalankan request "Login" untuk mendapatkan token
3. Token akan otomatis tersimpan dan digunakan untuk request lainnya

### 4. Swagger UI

Untuk melihat dokumentasi interaktif:

1. Install Swagger UI viewer atau gunakan online tool
2. Load file `openapi.yaml`
3. Atau gunakan extension VS Code "Swagger Viewer"

## 🔑 Default Test Users

| Username | Password | Role  |
| -------- | -------- | ----- |
| admin    | password | admin |
| user     | password | user  |

## 📁 Struktur Database

### Tabel Utama

-   `users` - Data pengguna sistem
-   `service_ticket` - Tiket layanan utama
-   `agent_log` - Log aktivitas agent
-   `roster_code` - Kode roster dan jadwal

### Tabel Lookup

-   `ticket_category` - Kategori tiket
-   `ticket_subcategory` - Sub-kategori tiket
-   `ticket_status` - Status tiket
-   `ticket_source` - Sumber tiket
-   `priority_level` - Level prioritas

## 🔧 Endpoints yang Tersedia

### Authentication

-   `POST /api/auth/login` - Login pengguna
-   `GET /api/auth/me` - Info pengguna yang login
-   `POST /api/auth/refresh` - Refresh JWT token
-   `POST /api/auth/logout` - Logout pengguna

## 🧪 Testing Otomatis

Jalankan test dengan perintah:

```bash
php artisan test --filter AuthTest
```

## 📝 Format Response

### Success Response

```json
{
    "success": true,
    "message": "Optional success message",
    "data": {
        // Response data
    }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## 🔒 Security

-   JWT token expires dalam 1 jam (3600 detik)
-   Password di-hash menggunakan Laravel Hash facade
-   Semua protected endpoint memerlukan valid JWT token
-   Token di-invalidate saat logout
-   Input validation pada semua endpoint

## 📞 Support

Untuk pertanyaan teknis atau bantuan mengenai API ini, silakan hubungi tim development.

---

**Last Updated:** Desember 2024  
**API Version:** 1.0  
**Laravel Version:** 11.x
