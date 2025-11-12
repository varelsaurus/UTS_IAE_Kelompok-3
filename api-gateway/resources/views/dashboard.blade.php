<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Public Transportation Tracker</title>
  <style>
    body { font-family: system-ui, Arial; margin: 24px; }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .card { border: 1px solid #ddd; border-radius: 12px; padding: 16px; }
  </style>
</head>
<body>
  <h1>Public Transportation Tracker</h1>
  <div class="grid">
    <div class="card"><h2>Buses</h2><ul id="buses"></ul></div>
    <div class="card"><h2>Routes</h2><ul id="routes"></ul></div>
  </div>

  <script>
    // Use a relative API base so the gateway host/port is implicit
    const API = '/api';

    function safe(v) { return v === undefined || v === null ? '' : v; }

    async function load(path, el) {
      try {
        const r = await fetch(API + path);
        if (!r.ok) throw new Error(`${r.status} ${r.statusText}`);
        const d = await r.json();

        document.getElementById(el).innerHTML = (d || []).map(x => {
          // Bus objects contain 'code' (unique bus code)
          if (x.code) {
            return `<li><strong>${safe(x.code)}</strong> — route: ${safe(x.route_id)} — cap: ${safe(x.capacity)} — loc: ${safe(x.lat)},${safe(x.lng)}</li>`;
          }

          // Route objects from route-service
          return `<li><strong>${safe(x.nama_rute)}</strong> — ${safe(x.titik_awal)} → ${safe(x.titik_akhir)} ` +
                 `(operasi: ${safe(x.jam_operasional) || '-'}; headway: ${safe(x.headway)})</li>`;
        }).join('');
      } catch (err) {
        document.getElementById(el).innerHTML = `<li style="color:crimson">Error: ${err.message}</li>`;
      }
    }

    // Load both collections from the API Gateway
    load('/buses', 'buses');
    load('/rute', 'routes');
  </script>
</body>
</html>
