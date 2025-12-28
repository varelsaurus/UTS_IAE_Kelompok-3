# 🚌 Public Transportation Tracker - Microservices Architecture

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://php.net)
[![Status](https://img.shields.io/badge/Status-Active-brightgreen)]()

Platform **API-first** untuk mengelola sistem transportasi publik dengan arsitektur **microservices**. 
Terdiri dari 3 service independen yang berkomunikasi melalui **API Gateway**.

---

## 📋 Daftar Isi

- [🎯 Overview](#-overview)
- [🏗️ Arsitektur](#-arsitektur)
- [🚀 Quick Start](#-quick-start)
- [� Quick Start with Docker (Recommended)](#-quick-start-with-docker-recommended)
- [🎮 GraphQL Playground](#-graphql-playground)
- [�📚 Dokumentasi](#-dokumentasi)
- [🔗 API Endpoints](#-api-endpoints)
- [📁 Project Structure](#-project-structure)
- [⚙️ Teknologi](#-teknologi)
- [👥 Tim Development](#-tim-development)
- [📝 License](#-license)

---

## 🎯 Overview

**Public Transportation Tracker** adalah sistem manajemen transportasi publik modern yang memisahkan concern dengan arsitektur microservices. Setiap domain (Bus, Rute) memiliki service independen, dan API Gateway sebagai single entry point.

### ✨ Fitur Utama

- 🚌 **Manajemen Bus** - Kelola armada bus dengan detail lokasi GPS dan kapasitas
- 🛣️ **Manajemen Rute** - Kelola rute perjalanan dengan daftar halte terstruktur
- 📊 **Dashboard UI** - Interface interaktif untuk monitoring dan CRUD data
- 📚 **API Documentation** - Swagger/OpenAPI dengan interactive UI
- 🔄 **API Gateway** - Single point untuk semua operasi
- 🗄️ **Database Seeder** - Data sample untuk development dan testing

---

## 🏗️ Arsitektur

### Microservices Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Frontend / Client                        │
│              (Dashboard UI / Mobile App / etc)              │
└────────────────────────┬────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                   API GATEWAY (Port 8000)                    │
│        ┌──────────────────────────────────────────┐         │
│        │  • Request Routing                       │         │
│        │  • Swagger/OpenAPI Documentation        │         │
│        │  • Dashboard UI                         │         │
│        │  • Response Transformation              │         │
│        └──────────────────────────────────────────┘         │
└────────┬────────────────────────────────────────────┬────────┘
         │                                            │
         ▼                                            ▼
    ┌─────────────┐                         ┌──────────────┐
    │  BUS        │                         │  ROUTE       │
    │  SERVICE    │                         │  SERVICE     │
    │ (Port 8002) │                         │ (Port 8001)  │
    │             │                         │              │
    │ • Models    │                         │ • Models     │
    │ • Database  │                         │ • Database   │
    │ • Business  │                         │ • Business   │
    │   Logic     │                         │   Logic      │
    └─────────────┘                         └──────────────┘
         │                                            │
         ▼                                            ▼
    ┌─────────────┐                         ┌──────────────┐
    │   MySQL     │                         │   MySQL      │
    │  (buses)    │                         │   (rute)     │
    └─────────────┘                         └──────────────┘
```

### Component Diagram

| Component | Port | Fungsi |
|-----------|------|--------|
| **API Gateway** | 8000 | Central entry point, routing, dokumentasi |
| **Bus Service** | 8002 | CRUD bus, manajemen armada |
| **Route Service** | 8001 | CRUD rute, manajemen halte |

---

## 🚀 Quick Start

### Prerequisites

**Opsi 1: Manual Setup**
- PHP 8.2+
- Composer
- MySQL 5.7+
- Node.js 18+ (optional, untuk frontend development)

**Opsi 2: Docker (Recommended)**
- Docker Desktop
- Docker Compose

### Installation

#### 1. Clone Repository

```bash
git clone https://github.com/varelsaurus/UTS_IAE_Kelompok-3.git
cd UTS_IAE_Kelompok-3
```

#### 2. Setup Each Service

Jalankan setup di **masing-masing folder** (api-gateway, bus-service, route-service):

```bash
# API Gateway
cd api-gateway
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan l5-swagger:generate

# Bus Service
cd ../bus-service
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# Route Service
cd ../route-service
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```

#### 3. Jalankan Ketiga Services

Buka **3 terminal** dan jalankan masing-masing service:

**Terminal 1 - API Gateway (Port 8000):**
```bash
cd api-gateway
php artisan serve --port=8000
```

**Terminal 2 - Bus Service (Port 8002):**
```bash
cd bus-service
php artisan serve --port=8002
```

**Terminal 3 - Route Service (Port 8001):**
```bash
cd route-service
php artisan serve --port=8001
```

#### 4. Akses Aplikasi

- 🌐 **Dashboard**: http://localhost:8000
- 📚 **API Docs**: http://localhost:8000/docs
- 📜 **Swagger UI**: http://localhost:8000/api/documentation

---

## � Quick Start with Docker (Recommended)

### Prerequisites

- Docker Desktop
- Docker Compose

### Installation with Docker

#### 1. Clone Repository

```bash
git clone https://github.com/varelsaurus/UTS_IAE_Kelompok-3.git
cd UTS_IAE_Kelompok-3
```

#### 2. Jalankan Semua Services

```bash
# Jalankan semua services dengan Docker Compose
docker-compose up -d

# Cek status services
docker-compose ps
```

#### 3. Setup Database

```bash
# Migrate database untuk semua services
docker-compose exec gateway php artisan migrate --force
docker-compose exec bus-service php artisan migrate --force
docker-compose exec route-service php artisan migrate --force

# Seed data sample (optional)
docker-compose exec gateway php artisan db:seed --force
```

#### 4. Akses Aplikasi

- 🌐 **API Gateway Dashboard**: http://localhost:8000
- 🚌 **Bus Service**: http://localhost:8001
- 🛣️ **Route Service**: http://localhost:8002
- 📚 **API Docs**: http://localhost:8000/docs
- 📜 **Swagger UI**: http://localhost:8000/api/documentation

### Docker Commands

```bash
# Stop semua services
docker-compose down

# Rebuild dan restart
docker-compose down && docker-compose up --build -d

# Lihat logs
docker-compose logs -f [service-name]

# Masuk ke container
docker-compose exec [service-name] bash
```

---

## 🎮 GraphQL Playground

Setiap service dilengkapi dengan **GraphQL Playground** untuk testing dan development API.

### Akses Playground

| Service | URL | Deskripsi |
|---------|-----|-----------|
| **API Gateway** | http://localhost:8000/graphiql | Query rute dan halte |
| **Bus Service** | http://localhost:8001/graphiql | Query dan mutation bus |
| **Route Service** | http://localhost:8002/graphiql | Query dan mutation rute |

### Contoh Query

#### Bus Service - Ambil semua bus
```graphql
query {
  buses {
    id
    code
    capacity
    route_id
    lat
    lng
  }
}
```

#### Bus Service - Tambah bus baru
```graphql
mutation {
  addBus(
    code: "BUS-001"
    capacity: 50
    route_id: 1
    lat: -6.2088
    lng: 106.8456
  ) {
    id
    code
    capacity
  }
}
```

#### Route Service - Ambil semua rute
```graphql
query {
  rutes {
    id
    name
    origin
    destination
    halte {
      id
      nama_halte
      urutan
    }
  }
}
```

#### Route Service - Tambah rute baru
```graphql
mutation {
  createRute(
    name: "Rute Jakarta-Bandung"
    origin: "Jakarta"
    destination: "Bandung"
    jadwal: "{\"catatan\": \"Rute utama\", \"rute_teks\": \"Jakarta - Bandung\"}"
  ) {
    id
    name
    origin
    destination
  }
}
```

### Fitur Playground

- **IntelliSense**: Auto-complete untuk query dan mutation
- **Documentation**: Klik tab "Docs" untuk melihat schema lengkap
- **Query Variables**: Untuk parameter dinamis
- **HTTP Headers**: Untuk authentication jika diperlukan
- **Real-time**: Response langsung dari server

### Schema Overview

#### Bus Service Schema
- **Queries**: `buses`, `bus(id)`
- **Mutations**: `addBus`, `updateBus`, `deleteBus`
- **Types**: `Bus` (id, code, capacity, route_id, lat, lng)

#### Route Service Schema
- **Queries**: `rutes`, `rute(id)`
- **Mutations**: `createRute`, `updateRute`, `deleteRute`
- **Types**: `Rute`, `Halte`, `Jadwal`

#### API Gateway Schema
- Mirip Route Service tapi sebagai aggregator

---

## �📚 Dokumentasi

### Service Documentation

Setiap service memiliki README sendiri:

#### 📖 [API Gateway](./api-gateway/README.md)
- Setup dan konfigurasi
- Dokumentasi endpoint
- Contoh request/response
- Troubleshooting

#### 🚌 [Bus Service](./bus-service/README.md)
- Model dan database
- Controller endpoints
- Business logic

#### 🛣️ [Route Service](./route-service/README.md)
- Model dan database
- Controller endpoints
- Business logic

### API Documentation

Akses dokumentasi interaktif:

```
http://localhost:8000/docs              (Custom Docs - Recommended)
http://localhost:8000/api/documentation (Swagger UI - Interactive)
```

---

## 🔗 API Endpoints

### Base URL
```
http://localhost:8000/api
```

### Buses Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/buses` | Daftar semua bus |
| `GET` | `/buses/{id}` | Detail bus |
| `POST` | `/buses` | Tambah bus |
| `PUT` | `/buses/{id}` | Ubah bus |
| `DELETE` | `/buses/{id}` | Hapus bus |

### Routes Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| `GET` | `/rute` | Daftar semua rute |
| `GET` | `/rute/{id}` | Detail rute + halte |
| `POST` | `/rute` | Tambah rute |
| `PUT` | `/rute/{id}` | Ubah rute |
| `DELETE` | `/rute/{id}` | Hapus rute |
| `GET` | `/rute/{id}/halte` | Daftar halte dalam rute |

---

## 📁 Project Structure

```
UTS_IAE_Kelompok-3/
├── 📄 README.md (ini)
├── 📄 .gitattributes
│
├── 📁 api-gateway/                    # API Gateway Service
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── GatewayController.php      (main gateway routing)
│   │   │   ├── SwaggerController.php      (API documentation)
│   │   │   └── SchemaController.php       (OpenAPI schemas)
│   │   └── Models/
│   ├── routes/
│   │   ├── api.php                        (API routes - forwarding)
│   │   └── web.php                        (web routes - UI)
│   ├── resources/views/
│   │   ├── dashboard.blade.php            (main UI)
│   │   └── swagger-docs.blade.php         (documentation page)
│   ├── config/
│   │   ├── services.php                   (microservices config)
│   │   └── l5-swagger.php                 (swagger config)
│   ├── storage/api-docs/                  (generated swagger docs)
│   ├── .env.example
│   ├── composer.json
│   ├── README.md
│   └── API_GATEWAY.postman_collection.json
│
├── 📁 bus-service/                    # Bus Management Service
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   └── BusController.php          (CRUD endpoints)
│   │   └── Models/
│   │       └── Bus.php                    (Bus model)
│   ├── database/
│   │   ├── migrations/
│   │   │   └── create_buses_table.php
│   │   └── seeders/
│   │       └── BusSeeder.php
│   ├── routes/
│   │   └── api.php                        (bus endpoints)
│   ├── .env.example
│   ├── composer.json
│   └── README.md
│
└── 📁 route-service/                  # Route Management Service
    ├── app/
    │   ├── Http/Controllers/
    │   │   └── RuteController.php         (CRUD endpoints)
    │   └── Models/
    │       ├── Rute.php                   (Route model)
    │       └── Halte.php                  (Stop model)
    ├── database/
    │   ├── migrations/
    │   │   ├── create_rutes_table.php
    │   │   └── create_haltes_table.php
    │   └── seeders/
    │       └── RuteHalteSeeder.php
    ├── routes/
    │   └── api.php                        (route endpoints)
    ├── .env.example
    ├── composer.json
    └── README.md
```

---

## 💡 Contoh Penggunaan

### 1. Ambil Semua Bus (cURL)

```bash
curl -X GET "http://localhost:8000/api/buses" \
  -H "accept: application/json"
```

### 2. Tambah Bus Baru (cURL)

```bash
curl -X POST "http://localhost:8000/api/buses" \
  -H "Content-Type: application/json" \
  -d '{
    "code": "B-03",
    "route_id": 1,
    "capacity": 45,
    "lat": -6.2100,
    "lng": 106.8470
  }'
```

### 3. Ambil Semua Rute (JavaScript)

```javascript
const API = 'http://localhost:8000/api';

fetch(`${API}/rute`)
  .then(r => r.json())
  .then(routes => console.log('Routes:', routes))
  .catch(err => console.error('Error:', err));
```

### 4. Tambah Rute (JavaScript)

```javascript
fetch(`${API}/rute`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    nama_rute: 'Koridor Baru',
    titik_awal: 'Terminal A',
    titik_akhir: 'Terminal B',
    jadwal: {
      jam_operasional: 'Senin-Minggu, 05:00-21:00',
      headway_teks: 'Setiap 15 menit'
    }
  })
})
  .then(r => r.json())
  .then(data => console.log('Created:', data))
  .catch(err => console.error('Error:', err));
```

### 5. Testing dengan Postman

Import collection: `api-gateway/API_GATEWAY.postman_collection.json`

Semua requests sudah tersedia dan siap digunakan!

---

## ⚙️ Teknologi

### Backend
- **Framework**: Laravel 12.x
- **Language**: PHP 8.2+
- **Database**: MySQL 5.7+
- **Package Management**: Composer

### API & Documentation
- **API Gateway**: Laravel
- **GraphQL**: Lighthouse (NuWave)
- **GraphQL Playground**: Laragraph Utils + MLL GraphiQL
- **Documentation**: Swagger/OpenAPI 3.0
- **UI Library**: L5-Swagger

### Containerization
- **Container Runtime**: Docker
- **Orchestration**: Docker Compose
- **Base Images**: PHP 8.2 + Composer

### Development Tools
- **Version Control**: Git
- **API Testing**: Postman, GraphQL Playground
- **Package Manager**: Composer
- **Container Management**: Docker Desktop

---

## 🔧 Konfigurasi Penting

### `.env` Files

**Untuk Setup Manual:**
Setiap service memiliki `.env` sendiri. Pastikan:

**api-gateway/.env:**
```env
ROUTE_SERVICE_URL=http://localhost:8001
BUS_SERVICE_URL=http://localhost:8002
```

**bus-service/.env:**
```env
DB_DATABASE=buses_db
DB_HOST=127.0.0.1
```

**route-service/.env:**
```env
DB_DATABASE=rute_db
DB_HOST=127.0.0.1
```

**Untuk Setup Docker:**
Konfigurasi database otomatis disesuaikan untuk container networking:

```env
# Semua service
DB_HOST=db
DB_PORT=3306
DB_DATABASE=uas_iae_db
DB_USERNAME=root
DB_PASSWORD=root

# API Gateway
ROUTE_SERVICE_URL=http://route-service:8000
BUS_SERVICE_URL=http://bus-service:8000
```

### Database Setup

**Untuk Setup Manual:**
Setiap service memiliki database terpisah:

```sql
-- Bus Service
CREATE DATABASE buses_db;

-- Route Service
CREATE DATABASE rute_db;
```

**Untuk Setup Docker:**
Database otomatis dibuat oleh container MySQL dengan nama `uas_iae_db`. Semua service menggunakan database yang sama untuk kemudahan development.

---

## 📖 Database Schema

### Bus Service (buses_db)

```sql
-- Buses Table
CREATE TABLE buses (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  code VARCHAR(50) UNIQUE NOT NULL,
  route_id BIGINT NOT NULL,
  capacity INT NOT NULL,
  lat DECIMAL(10, 6) NULL,
  lng DECIMAL(10, 6) NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Route Service (rute_db)

```sql
-- Routes Table
CREATE TABLE rute (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  nama_rute VARCHAR(255) NOT NULL,
  titik_awal VARCHAR(255) NOT NULL,
  titik_akhir VARCHAR(255) NOT NULL,
  jadwal JSON,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

-- Stops Table
CREATE TABLE halte (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  rute_id BIGINT NOT NULL,
  nama_halte VARCHAR(255) NOT NULL,
  urutan INT NOT NULL,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (rute_id) REFERENCES rute(id)
);
```

---

## 🐛 Troubleshooting

### Issue: HTTP 500 saat akses API

**Penyebab**: Salah satu service tidak berjalan

**Solusi**:
1. Pastikan ketiga services berjalan di port yang benar
   - API Gateway: 8000
   - Route Service: 8001
   - Bus Service: 8002
2. Check log: `storage/logs/laravel.log`

### Issue: Data kosong setelah setup

**Penyebab**: Seeder belum dijalankan

**Solusi**:
```bash
cd bus-service && php artisan migrate:fresh --seed
cd route-service && php artisan migrate:fresh --seed
```

### Issue: Database error pada migration

**Penyebab**: Database belum dibuat atau config salah

**Solusi**:
1. Create database manually atau check `.env` DB config
2. Jalankan: `php artisan migrate`

### Issue: Dokumentasi tidak terupdate

**Solusi**:
```bash
cd api-gateway
php artisan l5-swagger:generate
```

---

## 📊 Dashboard Features

### Views Tersedia

- **📊 Dashboard** - Overview statistik dan preview data
- **🚌 Buses** - Manajemen bus lengkap (CRUD)
- **🛣️ Routes** - Manajemen rute lengkap (CRUD)

### Features

- ✅ Real-time data loading
- ✅ Modal dialogs untuk create/edit
- ✅ Delete confirmation
- ✅ Auto-refresh every 30 seconds
- ✅ Responsive design
- ✅ Error handling dengan notifikasi

---

## 🧪 Testing API

### Dengan cURL

```bash
# List buses
curl http://localhost:8000/api/buses

# Get bus by ID
curl http://localhost:8000/api/buses/1

# Create bus
curl -X POST http://localhost:8000/api/buses \
  -H "Content-Type: application/json" \
  -d '{"code":"B-01","route_id":1,"capacity":50}'
```

### Dengan Postman

1. Import: `api-gateway/API_GATEWAY.postman_collection.json`
2. Set variable `base_url` ke `http://localhost:8000`
3. Gunakan pre-built requests

### Dengan browser

Buka: `http://localhost:8000` untuk UI dashboard

---

## 👥 Tim Development

**UTS - Kelompok 3 IAE**

Anggota:
- Desain & Frontend
- Backend Development
- Database Architecture

---

## 📄 License

MIT License - Bebas digunakan untuk project personal maupun komersial.

---

## 📞 Support & Resources

### Links Penting

- 🌐 **Dashboard**: http://localhost:8000
- 📚 **API Docs**: http://localhost:8000/docs
- 📜 **Swagger UI**: http://localhost:8000/api/documentation
- 🤝 **GitHub Repository**: https://github.com/varelsaurus/UTS_IAE_Kelompok-3

### Dokumentasi

- [API Gateway README](./api-gateway/README.md)
- [Bus Service README](./bus-service/README.md)
- [Route Service README](./route-service/README.md)

### External Resources

- [Laravel Documentation](https://laravel.com/docs)
- [OpenAPI/Swagger](https://swagger.io)
- [RESTful API Design](https://restfulapi.net)

---

## 🚀 Next Steps

1. ✅ Clone repository
2. ✅ Setup ketiga services
3. ✅ Jalankan services
4. ✅ Akses dashboard
5. ✅ Test API endpoints
6. ✅ Baca dokumentasi detail

---

## 📝 Changelog

### Version 1.0.0 (November 12, 2025)

**Features:**
- ✅ API Gateway dengan routing otomatis
- ✅ Bus Service untuk manajemen armada
- ✅ Route Service untuk manajemen rute
- ✅ Dokumentasi Swagger/OpenAPI
- ✅ Dashboard UI responsive
- ✅ Database seeder dengan data sample

---

## 🎓 Learning Resources

Untuk memahami project ini lebih dalam:

1. **Microservices Architecture**
   - Konsep service independen
   - API Gateway pattern
   - Database per service

2. **Laravel Framework**
   - MVC Pattern
   - Eloquent ORM
   - Route & Controller

3. **RESTful API Design**
   - HTTP Methods
   - Status Codes
   - JSON Response Format

4. **OpenAPI/Swagger**
   - Dokumentasi API
   - Schema definition
   - Interactive testing

---

<div align="center">

**Dibuat dengan ❤️ untuk UTS IAE Kelompok 3**

Last Updated: **November 12, 2025**

⭐ Jika project ini bermanfaat, silakan beri star di GitHub!

</div>
