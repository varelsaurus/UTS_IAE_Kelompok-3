<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Edit Rute</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    :root { --line:#e5e7eb; --muted:#666; }
    body { font-family: system-ui, Arial, sans-serif; margin:24px; }
    .wrap { max-width: 880px; margin: 0 auto; }
    .card { border:1px solid var(--line); border-radius:12px; padding:16px; background:#fff; }
    .row { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .row-1 { display:grid; gap:10px; }
    input, textarea { width:100%; padding:10px; border:1px solid var(--line); border-radius:8px; }
    .btn { padding:8px 12px; border:1px solid var(--line); border-radius:8px; background:#fff; cursor:pointer; }
    .btn:hover { background:#f9fafb; }
    .note { color:var(--muted); font-size:13px; }
    .actions { display:flex; gap:8px; align-items:center; margin-top:10px; }
  </style>
</head>
<body>
  <div class="wrap">
    <p><a class="btn" href="/">← Kembali ke Daftar Rute</a></p>
    <h1>Edit Rute</h1>

    <form id="formEditRute" class="card">
      <div class="row">
        <input name="nama_rute" placeholder="Nama rute" required>
        <input name="jam_operasional" placeholder="Jam operasional" required>
        <input name="titik_awal" placeholder="Titik awal" required>
        <input name="titik_akhir" placeholder="Titik akhir" required>
        <input name="headway_teks" placeholder="Headway (mis. Setiap 15–20 menit)">
        <input name="keberangkatan_pertama" placeholder="Keberangkatan pertama">
        <input name="keberangkatan_terakhir" placeholder="Keberangkatan terakhir">
      </div>
      <div class="row-1">
        <textarea name="catatan" placeholder="Catatan jadwal" rows="3"></textarea>
      </div>
      <div class="actions">
        <button class="btn" type="submit">💾 Simpan Perubahan</button>
        <span id="statusEdit" class="note"></span>
      </div>
    </form>
  </div>

<script>
const API = location.origin + '/api';
const elForm = document.getElementById('formEditRute');
const elStatus = document.getElementById('statusEdit');

// Ambil ID dari URL: /rute/edit?id=1
const params = new URLSearchParams(location.search);
const id = params.get('id');
if (!id) {
  alert('ID rute tidak ditemukan');
  window.location.href = '/';
}

async function loadData() {
  elStatus.textContent = 'Memuat data...';
  try {
    const res = await fetch(`${API}/rute/${id}`);
    if (!res.ok) throw new Error('Gagal memuat data');
    const r = await res.json();

    elForm.nama_rute.value = r.nama_rute ?? '';
    elForm.titik_awal.value = r.titik_awal ?? '';
    elForm.titik_akhir.value = r.titik_akhir ?? '';

    const j = r.jadwal || {};
    elForm.jam_operasional.value = j.jam_operasional ?? '';
    elForm.headway_teks.value = j.headway_teks ?? '';
    elForm.keberangkatan_pertama.value = j.keberangkatan_pertama ?? '';
    elForm.keberangkatan_terakhir.value = j.keberangkatan_terakhir ?? '';
    elForm.catatan.value = j.catatan ?? '';

    elStatus.textContent = '';
  } catch (e) {
    console.error(e);
    elStatus.textContent = '❌ Gagal memuat data';
  }
}

elForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  elStatus.textContent = 'Menyimpan...';

  const fd = new FormData(elForm);
  const body = {
    nama_rute:   fd.get('nama_rute'),
    titik_awal:  fd.get('titik_awal'),
    titik_akhir: fd.get('titik_akhir'),
    jadwal: {
      jam_operasional:        fd.get('jam_operasional'),
      headway_teks:           fd.get('headway_teks') || null,
      keberangkatan_pertama:  fd.get('keberangkatan_pertama') || null,
      keberangkatan_terakhir: fd.get('keberangkatan_terakhir') || null,
      catatan:                fd.get('catatan') || null,
    }
  };

  try {
    const res = await fetch(`${API}/rute/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    elStatus.textContent = '✅ Rute diperbarui!';
    setTimeout(()=> window.location.href='/', 800);
  } catch (err) {
    console.error(err);
    elStatus.textContent = '❌ Gagal memperbarui';
    alert('Gagal memperbarui rute.\n' + err.message);
  }
});

loadData();
</script>
</body>
</html>
