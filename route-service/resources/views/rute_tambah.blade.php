<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Rute</title>
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
    <h1>Tambah Rute</h1>

    <form id="formTambahRute" class="card">
      <div class="row">
        <input name="nama_rute" placeholder="Nama rute (mis. Koridor 1D Leuwipanjang – Soreang)" required>
        <input name="jam_operasional" placeholder="Jam operasional (mis. Senin–Minggu, 04.40–20.30)" required>
        <input name="titik_awal" placeholder="Titik awal (mis. Terminal Leuwipanjang)" required>
        <input name="titik_akhir" placeholder="Titik akhir (mis. Pengendapan Bus Soreang)" required>
        <input name="headway_teks" placeholder="Headway (mis. Setiap 15–20 menit)">
        <input name="keberangkatan_pertama" placeholder="(Opsional) Keberangkatan pertama (mis. 04:40)">
        <input name="keberangkatan_terakhir" placeholder="(Opsional) Keberangkatan terakhir (mis. 20:30)">
      </div>
      <div class="row-1">
        <textarea name="catatan" placeholder="(Opsional) Catatan jadwal" rows="3"></textarea>
      </div>
      <div class="actions">
        <button class="btn" type="submit">+ Tambah</button>
        <span id="statusTambah" class="note"></span>
      </div>
    </form>

    <p class="note" style="margin-top:8px">
      Data akan dikirim ke <code>/api/rute</code> sebagai JSON.
    </p>
  </div>

<script>
const API = location.origin + '/api';
const elForm = document.getElementById('formTambahRute');
const elStatus = document.getElementById('statusTambah');

elForm.addEventListener('submit', async (e) => {
  e.preventDefault();
  elStatus.textContent = 'Mengirim…';

  const fd = new FormData(elForm);
  const body = {
    nama_rute:   (fd.get('nama_rute') || '').trim(),
    titik_awal:  (fd.get('titik_awal') || '').trim(),
    titik_akhir: (fd.get('titik_akhir') || '').trim(),
    jadwal: {
      jam_operasional:        (fd.get('jam_operasional') || '').trim(),
      headway_teks:           (fd.get('headway_teks') || '').trim() || null,
      keberangkatan_pertama:  (fd.get('keberangkatan_pertama') || '').trim() || null,
      keberangkatan_terakhir: (fd.get('keberangkatan_terakhir') || '').trim() || null,
      catatan:                (fd.get('catatan') || '').trim() || null,
    }
  };

  try {
    const res = await fetch(`${API}/rute`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    if (!res.ok) {
      const txt = await res.text().catch(()=> '');
      throw new Error(`Gagal (${res.status}) ${txt.slice(0,200)}`);
    }
    elStatus.textContent = 'Berhasil ditambah ✅';
    elForm.reset();
    // opsional: langsung kembali ke daftar
    setTimeout(()=> { window.location.href = '/'; }, 800);
  } catch (err) {
    console.error(err);
    elStatus.textContent = 'Gagal menambah rute ❌';
    alert('Gagal menambah rute.\nPastikan semua field wajib terisi.\nDetail: ' + err.message);
  }
});
</script>
</body>
</html>
