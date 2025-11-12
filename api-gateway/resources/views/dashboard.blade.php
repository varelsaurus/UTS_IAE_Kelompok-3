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
    const API = 'http://localhost:8000/api';
    async function load(path, el) {
      const r = await fetch(API + path);
      const d = await r.json();
      document.getElementById(el).innerHTML = d.map(x => {
        if (x.code) return `<li><strong>${x.code}</strong> – routeId: ${x.route_id}, cap: ${x.capacity}, loc: ${x.lat ?? ''},${x.lng ?? ''}</li>`;
        return `<li><strong>${x.name}</strong> – ${x.origin} → ${x.destination} (jam: ${(x.schedule || []).join(', ')})</li>`;
      }).join('');
    }
    load('/buses', 'buses');
    load('/routes', 'routes');
  </script>
</body>
</html>
