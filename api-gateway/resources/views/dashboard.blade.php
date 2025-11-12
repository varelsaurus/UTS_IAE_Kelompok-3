<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Public Transportation Tracker</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family: system-ui, Arial; margin:24px; color:#111; background:#fafafa; }
    .grid { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
    .card { border:1px solid #e5e7eb; border-radius:12px; padding:16px; background:#fff; }
    h1 { margin:0 0 16px; }
    ul { padding-left:18px; }
    .btn { padding:8px 12px; border:1px solid #e5e7eb; border-radius:10px; background:#fff; cursor:pointer; }
    .note { color:#666; }
    .badge { display:inline-block; padding:2px 8px; border-radius:999px; border:1px solid #e5e7eb; font-size:12px; }
  </style>
</head>
<body>
  <h1>Public Transportation Tracker</h1>
  <div class="grid">
    <div class="card">
      <h2>🚌 Buses</h2>
      <ul id="buses"></ul>
    </div>
    <div class="card">
      <h2>🛣️ Routes</h2>
      <ul id="routes"></ul>
    </div>
  </div>

<script>
const API = location.origin + '/api';

function $(id){ return document.getElementById(id); }
async function fetchJSON(url){
  const r = await fetch(url);
  if(!r.ok) throw new Error('HTTP '+r.status);
  return r.json();
}

// BUSES
async function loadBuses(){
  const box = $('buses');
  box.innerHTML = '<li class="note">Memuat bus…</li>';
  try{
    const data = await fetchJSON(API + '/buses');
    if(!Array.isArray(data) || !data.length){ box.innerHTML = '<li class="note">Belum ada data</li>'; return; }
    box.innerHTML = data.map(b => `
      <li style="margin-bottom:10px;">
        <button class="btn btn-bus" data-id="${b.id}">${b.code ?? ('Bus #'+b.id)}</button>
        <span class="note">routeId: ${b.route_id ?? '-'}</span>
        <div id="bus-detail-${b.id}" style="margin-top:8px; display:none;"></div>
      </li>
    `).join('');
  }catch(e){
    box.innerHTML = `<li style="color:#c00">Gagal memuat (${e.message})</li>`;
  }
}

// ROUTES
async function loadRoutes(){
  const box = $('routes');
  box.innerHTML = '<li class="note">Memuat rute…</li>';
  try{
    const data = await fetchJSON(API + '/routes'); // alias ke GatewayController@routes
    if(!Array.isArray(data) || !data.length){ box.innerHTML = '<li class="note">Belum ada data</li>'; return; }
    box.innerHTML = data.map(r => `
      <li style="margin-bottom:10px;">
        <button class="btn btn-route" data-id="${r.id}">${r.nama_rute ?? r.name}</button>
        <div id="route-detail-${r.id}" style="margin-top:8px; display:none;"></div>
      </li>
    `).join('');
  }catch(e){
    box.innerHTML = `<li style="color:#c00">Gagal memuat (${e.message})</li>`;
  }
}

// Interaksi klik (delegasi)
document.addEventListener('click', async (ev) => {
  // klik BUS
  const b = ev.target.closest('.btn-bus');
  if(b){
    const id = b.dataset.id;
    const box = $('bus-detail-'+id);
    const open = box.style.display !== 'none';
    if(open){ box.style.display='none'; return; }
    box.style.display='block'; box.innerHTML='<div class="note">Memuat…</div>';
    try{
      const detail = await fetchJSON(API + '/bus/' + id);
      box.innerHTML = `
        <div><strong>Code:</strong> ${detail.code ?? '-'}</div>
        <div><strong>Capacity:</strong> ${detail.capacity ?? '-'}</div>
        <div><strong>Location:</strong> ${(detail.lat ?? '-')}, ${(detail.lng ?? '-')} </div>
      `;
    }catch(e){ box.innerHTML = `<div style="color:#c00">Gagal memuat (${e.message})</div>`; }
  }

  // klik ROUTE
  const r = ev.target.closest('.btn-route');
  if(r){
    const id = r.dataset.id;
    const box = $('route-detail-'+id);
    const open = box.style.display !== 'none';
    if(open){ box.style.display='none'; return; }
    box.style.display='block'; box.innerHTML='<div class="note">Memuat detail…</div>';
    try{
      const [detail, halte] = await Promise.all([
        fetchJSON(API + '/rute/' + id),
        fetchJSON(API + '/rute/' + id + '/halte')
      ]);
      const j = detail?.jadwal || {};
      const head = j.headway_teks ? `<span class="badge">${j.headway_teks}</span>` : '';
      const halteList = Array.isArray(halte) && halte.length
        ? '<ol style="margin:6px 0 0 18px;">' + halte.map(h => `<li>${h.nama_halte}</li>`).join('') + '</ol>'
        : '<div class="note">Belum ada halte.</div>';

      box.innerHTML = `
        <div style="color:#444;">
          <div><strong>Asal:</strong> ${detail.titik_awal ?? '-'}</div>
          <div><strong>Tujuan:</strong> ${detail.titik_akhir ?? '-'}</div>
          <div><strong>Jam operasional:</strong> ${j.jam_operasional ?? '-'}</div>
          ${head}
        </div>
        ${halteList}
      `;
    }catch(e){ box.innerHTML = `<div style="color:#c00">Gagal memuat (${e.message})</div>`; }
  }
});

// init
window.addEventListener('DOMContentLoaded', () => {
  loadBuses();
  loadRoutes();
});
</script>
</body>
</html>
