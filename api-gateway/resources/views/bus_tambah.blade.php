<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Tambah Bus</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family:system-ui,sans-serif; margin:24px; }
    .wrap { max-width:640px; margin:0 auto; }
    .card { border:1px solid #ddd; border-radius:12px; padding:20px; }
    .field { margin-bottom:12px; display:flex; flex-direction:column; gap:6px; }
    label { font-weight:600; }
    input { padding:10px; border:1px solid #ccc; border-radius:8px; }
    input:focus { border-color:#60a5fa; outline:none; box-shadow:0 0 0 3px #bfdbfe; }
    .btn { padding:8px 14px; border:1px solid #ccc; border-radius:8px; background:#fff; cursor:pointer; }
    .btn:hover { background:#f8fafc; }
    .note { color:#666; font-size:13px; }
  </style>
</head>
<body>
  <div class="wrap">
    <p><a href="/bus" class="btn">← Kembali ke Daftar Bus</a></p>
    <h1>Tambah Bus</h1>

    <form id="formTambahBus" class="card">
      <div class="field">
        <label>Kode Bus</label>
        <input name="code" placeholder="mis. B-01" required>
      </div>

      <div class="field">
        <label>ID Rute</label>
        <input name="route_id" type="number" placeholder="mis. 1" required>
      </div>

      <div class="field">
        <label>Kapasitas</label>
        <input name="capacity" type="number" placeholder="mis. 40" required>
      </div>

      <div class="field">
        <label>Latitude (opsional)</label>
        <input name="lat" placeholder="-6.921000">
      </div>

      <div class="field">
        <label>Longitude (opsional)</label>
        <input name="lng" placeholder="107.607000">
      </div>

      <button type="submit" class="btn">Tambah</button>
      <span id="statusTambah" class="note"></span>
    </form>
  </div>

<script>
const API = location.origin + '/api';
const form = document.getElementById('formTambahBus');
const status = document.getElementById('statusTambah');

form.addEventListener('submit', async e => {
  e.preventDefault();
  status.textContent = 'Mengirim...';

  const fd = new FormData(form);
  const body = Object.fromEntries(fd.entries());

  try {
    const res = await fetch(`${API}/bus`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);

    status.textContent = '✅ Berhasil menambah bus';
    form.reset();
    setTimeout(() => location.href = '/bus', 700);
  } catch (err) {
    console.error(err);
    status.textContent = '❌ Gagal menambah bus';
    alert('Gagal menambah bus: ' + err.message);
  }
});
</script>
</body>
</html>
