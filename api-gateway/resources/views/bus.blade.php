<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Daftar Bus</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body { font-family:system-ui,sans-serif; margin:24px; }
    h1 { margin-bottom:16px; }
    .btn { padding:6px 10px; border:1px solid #ccc; border-radius:6px; background:#fff; cursor:pointer; }
    .btn:hover { background:#f8fafc; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:8px; border-bottom:1px solid #ddd; }
    th { background:#f8fafc; }
  </style>
</head>
<body>
  <h1>Daftar Bus</h1>
  <p><a href="/" class="btn">← Kembali ke Dashboard</a> <a href="/bus/tambah" class="btn">+ Tambah Bus</a></p>

  <table id="tbl">
    <thead><tr><th>Kode</th><th>Rute ID</th><th>Kapasitas</th><th>Lokasi</th><th>Aksi</th></tr></thead>
    <tbody><tr><td colspan="5">Memuat data…</td></tr></tbody>
  </table>

<script>
const API = location.origin + '/api';
const body = document.querySelector('tbody');

async function loadBus() {
  try {
    const res = await fetch(API + '/buses');
    if (!res.ok) throw new Error(res.status);
    const data = await res.json();
    body.innerHTML = data.map(b => `
      <tr>
        <td>${b.code}</td>
        <td>${b.route_id}</td>
        <td>${b.capacity}</td>
        <td>${b.lat ?? '-'}, ${b.lng ?? '-'}</td>
        <td>
          <a href="/bus/edit?id=${b.id}" class="btn">✏️</a>
          <button class="btn" onclick="hapus(${b.id})">🗑</button>
        </td>
      </tr>
    `).join('');
  } catch (err) {
    body.innerHTML = `<tr><td colspan="5">Gagal memuat (${err.message})</td></tr>`;
  }
}

async function hapus(id) {
  if (!confirm('Hapus bus ini?')) return;
  const res = await fetch(`${API}/bus/${id}`, { method: 'DELETE' });
  if (res.ok) loadBus();
}

loadBus();
</script>
</body>
</html>
