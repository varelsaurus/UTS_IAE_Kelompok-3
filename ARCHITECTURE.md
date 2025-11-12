# 🏗️ ARCHITECTURE DOCUMENTATION

Dokumentasi detail arsitektur **Public Transportation Tracker** menggunakan Microservices Pattern.

---

## 📊 System Architecture Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                        CLIENT LAYER                              │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Browser (Dashboard)  │  Mobile App  │  External API     │  │
│  └──────────────────────────────────────────────────────────┘  │
└────────────────────────────┬─────────────────────────────────────┘
                             │ HTTP/REST
                             ▼
┌─────────────────────────────────────────────────────────────────┐
│                    API GATEWAY LAYER (Port 8000)                │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  • Request Routing                                       │  │
│  │  • Request Validation                                    │  │
│  │  • Response Transformation                               │  │
│  │  • Error Handling                                        │  │
│  │  • Swagger/OpenAPI Documentation                         │  │
│  │  • Dashboard UI                                          │  │
│  └──────────────────────────────────────────────────────────┘  │
└─────────────┬──────────────────────────────────┬─────────────────┘
              │                                  │
              │ HTTP/REST                       │ HTTP/REST
              ▼                                  ▼
    ┌──────────────────────┐          ┌──────────────────────┐
    │   BUS SERVICE        │          │  ROUTE SERVICE       │
    │   (Port 8002)        │          │  (Port 8001)         │
    │                      │          │                      │
    │ ┌────────────────┐   │          │ ┌────────────────┐   │
    │ │ BusController  │   │          │ │ RuteController │   │
    │ │ • GET /buses   │   │          │ │ • GET /rute    │   │
    │ │ • POST /buses  │   │          │ │ • POST /rute   │   │
    │ │ • PUT /buses   │   │          │ │ • PUT /rute    │   │
    │ │ • DELETE /buses│   │          │ │ • DELETE /rute │   │
    │ └────────────────┘   │          │ └────────────────┘   │
    │                      │          │                      │
    │ ┌────────────────┐   │          │ ┌────────────────┐   │
    │ │ Bus Model      │   │          │ │ Rute Model     │   │
    │ │ Bus Migration  │   │          │ │ Rute Migration │   │
    │ │ BusSeeder      │   │          │ │ Halte Model    │   │
    │ └────────────────┘   │          │ │ Halte Migration│   │
    └──────────┬───────────┘          │ │ RuteSeeder     │   │
               │                       │ └────────────────┘   │
               │ Database Connection   └──────────┬────────────┘
               ▼                                  ▼
    ┌──────────────────┐                ┌──────────────────┐
    │   MySQL (buses)  │                │   MySQL (rute)   │
    │                  │                │                  │
    │ • buses table    │                │ • rute table     │
    │                  │                │ • halte table    │
    └──────────────────┘                └──────────────────┘
```

---

## 🏛️ Architectural Patterns

### 1. Microservices Pattern

Setiap domain bisnis memiliki service terpisah dengan database independen.

**Keuntungan:**
- ✅ Independent deployment
- ✅ Technology diversity
- ✅ Team autonomy
- ✅ Easier scaling

**Tantangan:**
- ⚠️ Increased complexity
- ⚠️ Network latency
- ⚠️ Data consistency

### 2. API Gateway Pattern

Central entry point untuk semua request client.

**Fungsi:**
- Request routing ke microservice yang sesuai
- Authentication/Authorization
- Rate limiting
- Response transformation

**Implementasi:**
```
/api/buses/*    → Bus Service (8002)
/api/rute/*     → Route Service (8001)
```

### 3. Database per Service

Setiap service memiliki database own, mencegah tight coupling.

```
Bus Service     → buses_db (MySQL)
Route Service   → rute_db (MySQL)
API Gateway     → (optional session storage)
```

### 4. RESTful API Design

Menggunakan HTTP methods dan status codes secara proper.

```
GET    /api/buses        → Read all
GET    /api/buses/1      → Read one
POST   /api/buses        → Create
PUT    /api/buses/1      → Update
DELETE /api/buses/1      → Delete
```

---

## 📡 Communication Flow

### 1. Dashboard Request Flow

```
1. User Access: http://localhost:8000
                ↓
2. Gateway serves dashboard.blade.php
                ↓
3. JavaScript fetch request: GET /api/buses
                ↓
4. Gateway GatewayController::buses()
                ↓
5. Forward to Bus Service: GET http://localhost:8002/api/buses
                ↓
6. Bus Service response (JSON)
                ↓
7. Gateway return response to client
                ↓
8. Dashboard render data
```

### 2. API Gateway Request Forwarding

```
Client Request
    ↓
GatewayController::forward()
    ↓
Parse URL & method
    ↓
Determine target service
    (services.php config)
    ↓
HTTP::send() to microservice
    ↓
Microservice response
    ↓
Return to client
```

**Kode Reference:**
```php
// api-gateway/app/Http/Controllers/GatewayController.php
protected function forward(Request $request, string $targetBase)
{
    $path = '/' . trim(preg_replace('#^/api#', '', $request->getRequestUri()), '/');
    $url  = rtrim($targetBase, '/') . $path;

    $resp = Http::withHeaders($request->headers->all())
        ->send($request->method(), $url, [
            'query' => $request->query(),
            'json' => $request->isJson() ? $request->json()->all() : null,
        ]);

    return response($resp->body(), $resp->status())
        ->withHeaders($resp->headers());
}
```

---

## 🔐 Security Architecture

### Request Validation

```
Client Request
    ↓
Gateway validates input
    ↓
Route to service
    ↓
Service validates again
    ↓
Response
```

### Error Handling

```
Exception occurred
    ↓
Service handles & logs
    ↓
Return error response
    ↓
Gateway forwards to client
    ↓
Client handles error
```

---

## 🗄️ Database Schema Architecture

### Bus Service (buses_db)

```sql
buses
├── id (PK)
├── code (UNIQUE) - "B-01", "B-02"
├── route_id - Reference to route
├── capacity - 50, 40, 45
├── lat - GPS latitude
├── lng - GPS longitude
├── created_at
└── updated_at

Relationships:
- Bus can have many: (dari route-service)
```

### Route Service (rute_db)

```sql
rute (Routes)
├── id (PK)
├── nama_rute - "Koridor 1D"
├── titik_awal - "Terminal A"
├── titik_akhir - "Terminal B"
├── jadwal - JSON (hours, frequency)
├── created_at
└── updated_at

halte (Stops)
├── id (PK)
├── rute_id (FK) - Reference to rute
├── nama_halte - Stop name
├── urutan - Order in route (1, 2, 3...)
├── created_at
└── updated_at

Relationships:
- Route has many Stops (1-to-Many)
```

---

## 🔄 Data Flow Examples

### Example 1: Create New Bus

```
1. User input form di dashboard
              ↓
2. POST /api/buses
   {
     "code": "B-03",
     "route_id": 1,
     "capacity": 45
   }
              ↓
3. API Gateway receives
              ↓
4. GatewayController::buses()
              ↓
5. Forward to Bus Service (8002)
              ↓
6. BusController::store()
   - Validate data
   - Create Bus model
   - Save to database
              ↓
7. Return response (201 Created)
   {
     "id": 3,
     "code": "B-03",
     "route_id": 1,
     "capacity": 45
   }
              ↓
8. Gateway forward response
              ↓
9. Dashboard refresh & show new bus
```

### Example 2: Get Route with Stops

```
1. User click route detail
              ↓
2. GET /api/rute/1
              ↓
3. API Gateway forward to Route Service
              ↓
4. RuteController::tampil()
   - Find Rute by ID
   - Load related halte (with relationship)
              ↓
5. Return response
   {
     "id": 1,
     "nama_rute": "Koridor 1D",
     "titik_awal": "Terminal A",
     "titik_akhir": "Terminal B",
     "jadwal": {...},
     "halte": [
       {"id": 1, "nama_halte": "Stop 1", "urutan": 1},
       {"id": 2, "nama_halte": "Stop 2", "urutan": 2},
       ...
     ]
   }
              ↓
6. Dashboard display route & stops
```

---

## 📚 Deployment Architecture

### Development (Current Setup)

```
Local Machine
├── API Gateway :8000
├── Bus Service :8002
└── Route Service :8001

Each service runs as separate PHP process
Database: Local MySQL
```

### Production (Recommended)

```
Load Balancer (Nginx/HAProxy)
    ↓
┌─────────────────────────────────┐
│  API Gateway Cluster (3 copies) │ (Auto-scale)
└─────────────────────────────────┘
    ↓
┌──────────────────┬──────────────────┐
│ Bus Service      │ Route Service    │
│ Cluster (2+)     │ Cluster (2+)     │
└──────────────────┴──────────────────┘
    ↓
┌──────────────────┬──────────────────┐
│ MySQL (buses)    │ MySQL (rute)     │
│ Replicated       │ Replicated       │
└──────────────────┴──────────────────┘
```

---

## 🔗 Service Dependencies

### API Gateway Dependencies

```yaml
api-gateway:
  depends_on:
    - bus-service
    - route-service
  config:
    - ROUTE_SERVICE_URL
    - BUS_SERVICE_URL
```

### Bus Service Dependencies

```yaml
bus-service:
  depends_on:
    - mysql (buses_db)
  no dependencies to other services
```

### Route Service Dependencies

```yaml
route-service:
  depends_on:
    - mysql (rute_db)
  no dependencies to other services
```

**Note:** Services tidak punya direct dependency ke service lain. Mereka hanya diakses melalui API Gateway.

---

## 📋 API Contract

### Request/Response Format

**Request:**
```json
{
  "nama_rute": "string",
  "titik_awal": "string",
  "titik_akhir": "string"
}
```

**Response Success (200/201):**
```json
{
  "id": 1,
  "nama_rute": "string",
  "created_at": "2025-11-12T10:00:00Z"
}
```

**Response Error (400/500):**
```json
{
  "message": "Error message",
  "errors": {
    "field": ["error message"]
  }
}
```

---

## 🧪 Testing Architecture

### Unit Testing (Per Service)

```
tests/
├── Unit/
│   ├── BusModelTest.php
│   └── RuteModelTest.php
├── Feature/
│   ├── BusControllerTest.php
│   └── RuteControllerTest.php
```

### Integration Testing (API Gateway)

```
Test flow:
1. Start all services
2. Send request to gateway
3. Verify response
4. Check database
```

### E2E Testing (Full Flow)

```
1. Dashboard interaction
2. Create/Update/Delete operations
3. Verify all services synchronized
```

---

## 📊 Performance Considerations

### Caching Strategy

```
API Gateway
├── Response caching (Redis optional)
└── Database query caching

Microservices
├── Database query caching
└── File caching
```

### Load Distribution

```
High Volume
├── Scale API Gateway (multiple instances)
├── Scale Bus Service independently
├── Scale Route Service independently
└── Database replication
```

### Monitoring Points

```
API Gateway
├── Request/Response time
├── Error rate
└── Service availability

Microservices
├── Database query performance
├── Memory usage
└── Disk I/O
```

---

## 🔄 Future Architecture Enhancements

### Potential Improvements

1. **Message Queue (RabbitMQ/Kafka)**
   - Async operations
   - Event-driven architecture
   - Better scalability

2. **Service Discovery (Consul/Eureka)**
   - Dynamic service registration
   - Automatic failover

3. **Containerization (Docker)**
   - Consistent environments
   - Easy deployment

4. **Orchestration (Kubernetes)**
   - Container orchestration
   - Auto-scaling

5. **Distributed Tracing (Jaeger/Zipkin)**
   - Request tracing across services
   - Performance monitoring

6. **API Versioning**
   - `/api/v1/buses`
   - `/api/v2/buses`

---

## 📖 References

- [Microservices Pattern - Chris Richardson](https://microservices.io)
- [API Gateway Pattern](https://www.nginx.com/learn/api-gateway)
- [Laravel Architecture](https://laravel.com/docs/architecture)
- [RESTful API Design Best Practices](https://restfulapi.net)

---

<div align="center">

**Architecture Documentation v1.0**

Last Updated: November 12, 2025

</div>
