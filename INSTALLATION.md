# 🚀 INSTALLATION GUIDE

Panduan lengkap untuk setup dan menjalankan project **Public Transportation Tracker** secara lokal.

## 📋 Prerequisites

Sebelum memulai, pastikan Anda memiliki:

- **PHP 8.2+** - Download dari [php.net](https://www.php.net/downloads)
- **Composer** - Download dari [getcomposer.org](https://getcomposer.org)
- **MySQL 5.7+** - Download dari [mysql.com](https://www.mysql.com) atau gunakan [XAMPP](https://www.apachefriends.org)
- **Git** - Download dari [git-scm.com](https://git-scm.com)
- **Text Editor/IDE** - VSCode, PHPStorm, dll

### Verifikasi Installation

Cek apakah semua tools sudah terinstall:

```bash
php --version
composer --version
mysql --version
git --version
```

---

## 📥 Step 1: Clone Repository

```bash
git clone https://github.com/varelsaurus/UTS_IAE_Kelompok-3.git
cd UTS_IAE_Kelompok-3
```

---

## 🔧 Step 2: Setup Database

### 2.1 Create Databases

Buka MySQL command line atau GUI tool (phpMyAdmin, Navicat, dll):

```sql
-- Create database untuk Bus Service
CREATE DATABASE buses_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create database untuk Route Service
CREATE DATABASE rute_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Verify
SHOW DATABASES;
```

---

## 📦 Step 3: Install Dependencies

Jalankan composer install di **setiap folder service**:

### API Gateway

```bash
cd api-gateway
composer install
```

### Bus Service

```bash
cd ../bus-service
composer install
```

### Route Service

```bash
cd ../route-service
composer install
```

---

## ⚙️ Step 4: Environment Setup

Setup `.env` file di masing-masing service.

### API Gateway

```bash
cd api-gateway

# Copy environment file
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

**Verify `.env` configuration:**
```env
APP_NAME=Laravel
APP_KEY=base64:xxxxx (sudah di-generate)
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=api_gateway (optional)
DB_USERNAME=root
DB_PASSWORD=

ROUTE_SERVICE_URL=http://localhost:8001
BUS_SERVICE_URL=http://localhost:8002
```

### Bus Service

```bash
cd ../bus-service

# Copy environment file
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

**Verify `.env` configuration:**
```env
APP_NAME=Laravel
APP_KEY=base64:xxxxx (sudah di-generated)
APP_URL=http://localhost:8002

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=buses_db
DB_USERNAME=root
DB_PASSWORD=
```

### Route Service

```bash
cd ../route-service

# Copy environment file
cp .env.example .env

# Generate APP_KEY
php artisan key:generate
```

**Verify `.env` configuration:**
```env
APP_NAME=Laravel
APP_KEY=base64:xxxxx (sudah di-generated)
APP_URL=http://localhost:8001

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rute_db
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🗄️ Step 5: Database Migrations & Seeding

Jalankan migration dan seeder di masing-masing service.

### Bus Service

```bash
cd bus-service

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed --class=BusSeeder
```

Atau langsung dengan fresh migration (reset semua data):
```bash
php artisan migrate:fresh --seed
```

### Route Service

```bash
cd ../route-service

# Run migrations
php artisan migrate

# Seed sample data
php artisan db:seed --class=RuteHalteSeeder
```

Atau langsung dengan fresh migration:
```bash
php artisan migrate:fresh --seed
```

### API Gateway

```bash
cd ../api-gateway

# Run migrations (optional, untuk session storage)
php artisan migrate

# Generate Swagger documentation
php artisan l5-swagger:generate
```

---

## ▶️ Step 6: Jalankan Services

Buka **3 terminal** dan jalankan setiap service.

### Terminal 1 - API Gateway

```bash
cd api-gateway
php artisan serve --port=8000
```

**Output:**
```
Laravel development server started on [http://127.0.0.1:8000]
```

### Terminal 2 - Bus Service

```bash
cd bus-service
php artisan serve --port=8002
```

**Output:**
```
Laravel development server started on [http://127.0.0.1:8002]
```

### Terminal 3 - Route Service

```bash
cd route-service
php artisan serve --port=8001
```

**Output:**
```
Laravel development server started on [http://127.0.0.1:8001]
```

---

## ✅ Step 7: Verify Installation

Pastikan semua services berjalan dengan baik.

### Check API Endpoints

```bash
# Check Bus Service
curl http://localhost:8002/api/buses

# Check Route Service
curl http://localhost:8001/api/rute

# Check API Gateway
curl http://localhost:8000/api/buses
```

Semua harus return JSON response dengan data sample.

### Check Dashboard & Documentation

Buka di browser:

- 🌐 **Dashboard**: http://localhost:8000
- 📚 **API Docs**: http://localhost:8000/docs
- 📜 **Swagger UI**: http://localhost:8000/api/documentation

---

## 🧪 Step 8: Test dengan Postman (Optional)

Untuk advanced testing dengan Postman:

1. Download & install [Postman](https://www.postman.com/downloads/)
2. Import collection:
   - File: `api-gateway/API_GATEWAY.postman_collection.json`
   - Atau buka URL: `http://localhost:8000/storage/api-docs/api-docs.json` dan import ke Postman
3. Set environment variable `base_url` ke `http://localhost:8000`
4. Test endpoints dengan pre-built requests

---

## 🔄 Troubleshooting

### Port Already In Use

Jika port 8000, 8001, 8002 sudah digunakan:

```bash
# Change port (example: use 8100 instead of 8000)
php artisan serve --port=8100

# Update .env di service yang lain sesuai port baru
```

### Composer Update Failed

```bash
# Clear composer cache
composer clearcache

# Try again
composer install
```

### Migration Failed

```bash
# Reset migrations
php artisan migrate:reset

# Run again
php artisan migrate:fresh --seed
```

### Database Connection Error

Pastikan:
1. MySQL service berjalan
2. Database sudah dibuat
3. Database credentials di `.env` benar
4. User MySQL memiliki permission

```bash
# Test connection
mysql -h 127.0.0.1 -u root -p buses_db
```

### Swagger Documentation Not Generated

```bash
cd api-gateway
php artisan l5-swagger:generate
```

---

## 📝 Quick Reference Commands

```bash
# Setup single service
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# Run service
php artisan serve --port=8000

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed

# Cache
php artisan cache:clear
php artisan config:clear

# Swagger (API Gateway only)
php artisan l5-swagger:generate
```

---

## 🎓 Next Steps

1. Baca [Main README](../README.md) untuk overview project
2. Baca README di setiap service folder
3. Explore API documentation di http://localhost:8000/docs
4. Test endpoints dengan dashboard atau Postman
5. Customize sesuai kebutuhan Anda

---

## 📞 Need Help?

Jika ada masalah:

1. Check [troubleshooting section](#-troubleshooting)
2. Baca main [README.md](../README.md)
3. Baca service-specific README
4. Check Laravel documentation

---

**Happy Installation! 🎉**
