<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Public Transportation Tracker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@3/swagger-ui.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f5f5f5;
        }

        .top-bar {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .top-bar-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .top-bar h1 {
            font-size: 24px;
            font-weight: 700;
        }

        .top-bar p {
            font-size: 14px;
            opacity: 0.9;
            max-width: 500px;
        }

        .nav-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-links a {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s ease;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .nav-links a.active {
            background: rgba(255, 255, 255, 0.9);
            color: #1e40af;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .swagger-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        #swagger-ui {
            padding: 20px;
        }

        .info-section {
            background: white;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .info-section h2 {
            color: #1e40af;
            margin-bottom: 16px;
            font-size: 20px;
        }

        .info-section p {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .info-section ul {
            margin-left: 20px;
            color: #6b7280;
            line-height: 1.8;
        }

        .info-section li {
            margin-bottom: 8px;
        }

        .info-section code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #d97706;
        }

        .endpoint-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .endpoint-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
        }

        .endpoint-card .method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .method.get { background: #dbeafe; color: #0c4a6e; }
        .method.post { background: #dcfce7; color: #166534; }
        .method.put { background: #fed7aa; color: #92400e; }
        .method.delete { background: #fee2e2; color: #991b1b; }

        .endpoint-card h4 {
            color: #111827;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .endpoint-card p {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.5;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6b7280;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .top-bar-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="top-bar">
        <div class="top-bar-content">
            <div>
                <h1>🚌 Public Transportation Tracker</h1>
                <p>API Gateway Documentation - Kelola Bus dan Rute Perjalanan</p>
            </div>
            <div class="nav-links">
                <a href="/">📊 Dashboard</a>
                <a href="/api/documentation" class="active">📚 API Docs</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="info-section">
            <h2>ℹ️ Tentang API Gateway</h2>
            <p>
                <strong>API Gateway</strong> adalah central point untuk mengakses semua layanan transportasi publik. 
                Gateway ini meneruskan (forward) request Anda ke microservices yang sesuai dan mengembalikan response secara transparan.
            </p>
            <p><strong>Base URL:</strong> <code>http://localhost:8000/api</code></p>
        </div>

        <div class="info-section">
            <h2>🎯 Endpoint Overview</h2>
            <p><strong>Buses (Bus Service - Port 8002)</strong></p>
            <div class="endpoint-grid">
                <div class="endpoint-card">
                    <span class="method get">GET</span>
                    <h4>/buses</h4>
                    <p>Daftar semua bus</p>
                </div>
                <div class="endpoint-card">
                    <span class="method get">GET</span>
                    <h4>/buses/{id}</h4>
                    <p>Detail bus berdasarkan ID</p>
                </div>
                <div class="endpoint-card">
                    <span class="method post">POST</span>
                    <h4>/buses</h4>
                    <p>Tambah bus baru</p>
                </div>
                <div class="endpoint-card">
                    <span class="method put">PUT</span>
                    <h4>/buses/{id}</h4>
                    <p>Perbarui data bus</p>
                </div>
                <div class="endpoint-card">
                    <span class="method delete">DELETE</span>
                    <h4>/buses/{id}</h4>
                    <p>Hapus bus</p>
                </div>
            </div>

            <p style="margin-top: 24px;"><strong>Routes (Route Service - Port 8001)</strong></p>
            <div class="endpoint-grid">
                <div class="endpoint-card">
                    <span class="method get">GET</span>
                    <h4>/rute</h4>
                    <p>Daftar semua rute</p>
                </div>
                <div class="endpoint-card">
                    <span class="method get">GET</span>
                    <h4>/rute/{id}</h4>
                    <p>Detail rute + halte</p>
                </div>
                <div class="endpoint-card">
                    <span class="method post">POST</span>
                    <h4>/rute</h4>
                    <p>Tambah rute baru</p>
                </div>
                <div class="endpoint-card">
                    <span class="method put">PUT</span>
                    <h4>/rute/{id}</h4>
                    <p>Perbarui data rute</p>
                </div>
                <div class="endpoint-card">
                    <span class="method delete">DELETE</span>
                    <h4>/rute/{id}</h4>
                    <p>Hapus rute</p>
                </div>
                <div class="endpoint-card">
                    <span class="method get">GET</span>
                    <h4>/rute/{id}/halte</h4>
                    <p>Daftar halte dalam rute</p>
                </div>
            </div>
        </div>

        <div class="info-section">
            <h2>📝 Contoh Request</h2>
            <p><strong>GET - Ambil semua bus:</strong></p>
            <code style="display: block; background: #f3f4f6; padding: 12px; border-radius: 6px; font-size: 13px; overflow-x: auto; margin: 8px 0;">
                curl -X GET "http://localhost:8000/api/buses" -H "accept: application/json"
            </code>

            <p style="margin-top: 16px;"><strong>POST - Tambah bus baru:</strong></p>
            <code style="display: block; background: #f3f4f6; padding: 12px; border-radius: 6px; font-size: 13px; overflow-x: auto; margin: 8px 0;">
                curl -X POST "http://localhost:8000/api/buses" \<br/>
                &nbsp;&nbsp;-H "Content-Type: application/json" \<br/>
                &nbsp;&nbsp;-d '{"code":"B-03","route_id":1,"capacity":45}'
            </code>
        </div>

        <div class="swagger-wrapper">
            <div id="swagger-ui"></div>
        </div>

        <div class="footer">
            <p>📚 Dokumentasi API Generated with Swagger/OpenAPI</p>
            <p>Last Updated: {{ date('Y-m-d H:i:s') }}</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@3/swagger-ui-bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@3/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                url: "/storage/api-docs/api-docs.json",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                defaultModelsExpandDepth: 1,
                docExpansion: "list"
            });
            window.ui = ui;
        };
    </script>
</body>
</html>
