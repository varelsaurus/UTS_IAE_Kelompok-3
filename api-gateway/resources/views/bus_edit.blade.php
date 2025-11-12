<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Edit Bus</title>
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
    <h1>Edit Bus</h1>

    <form id="formEditBus" class="card">
      <div class="field">
        <label>Kode Bus</label>
        <input name="code" required>
      </div>

      <div class="field">
        <label>ID Rute</label>
        <input name="route_id" type="number" required>
      </div>

      <div class="field">
        <label>Kapasitas</label>
        <input name="capacity" type="number" required>
      </div>

      <div class="field">
        <label>Latitude</label>
        <input name="lat">
      </div>

      <div class="field">
        <label>Longitude</label>
        <input name="lng">
      </div>

      <button type="submit" class="btn">Simpan Perubahan</button>
      <span id="statusEdit" class="note"></span>
    </form>
  </div>

<script>
const API = location.origin + '/api';
const params = new URLSearchParams(location.search);
const id = params.get('id');
const form = document.getElementById('formEditBus');
const status = document.getElementById('statusEdit');

if (!id) {
  alert('ID bus tidak ditemukan.');
  location.href = '/bus';
}

async function loadBus() {
  status.textContent = 'Memuat data...';
  try {
    const res = await fetch(`${API}/bus/${id}`);
    if (!res.ok) throw new Error('HTTP ' + res.status);
    const b = await res.json();
    form.code.value = b.code ?? '';
    form.route_id.value = b.route_id ?? '';
    form.capacity.value = b.capacity ?? '';
    form.lat.value = b.lat ?? '';
    form.lng.value = b.lng ?? '';
    status.textContent = '';
  } catch (err) {
    status.textContent = '❌ Gagal memuat data';
    console.error(err);
  }
}

form.addEventListener('submit', async e => {
  e.preventDefault();
  status.textContent = 'Menyimpan...';

  const fd = new FormData(form);
  const body = Object.fromEntries(fd.entries());

  try {
    const res = await fetch(`${API}/bus/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });

    if (!res.ok) throw new Error('HTTP ' + res.status);
    status.textContent = '✅ Berhasil diperbarui';
    setTimeout(() => location.href = '/bus', 700);
  } catch (err) {
    console.error(err);
    status.textContent = '❌ Gagal menyimpan';
    alert('Gagal memperbarui bus: ' + err.message);
  }
});

loadBus();
</script>
</body>
</html>
