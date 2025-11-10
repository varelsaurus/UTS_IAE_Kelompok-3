<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Tambah Rute</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    :root { --line:#e5e7eb; --muted:#666; --bg:#fff; }
  * { box-sizing: border-box; }
  body { font-family: system-ui, Arial, sans-serif; margin: 24px; }
  .wrap { max-width: 880px; margin: 0 auto; }
  .card { border:1px solid var(--line); border-radius:16px; padding:20px; background:var(--bg); }

  /* Grid responsif: mobile 1 kolom, >=768px 2 kolom */
  .grid { display:grid; gap:16px; }
  @media (min-width: 768px) {
    .grid.cols-2 { grid-template-columns: 1fr 1fr; }
  }

  .field { display:flex; flex-direction:column; gap:6px; }
  .label { font-size:14px; font-weight:600; color:#111; }
  .hint  { font-size:12px; color:var(--muted); }
  input, textarea {
    width:100%; padding:12px 14px; border:1px solid var(--line);
    border-radius:10px; outline:none; background:#fff;
  }
  input:focus, textarea:focus { border-color:#94a3b8; box-shadow:0 0 0 3px #e2e8f0; }

  .actions { display:flex; gap:10px; align-items:center; margin-top:8px; }
  .btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 12px; border:1px solid #e5e7eb; border-radius:10px;
    background:#fff; cursor:pointer; text-decoration:none; color:#111;
    }
    .btn:hover { background:#f8fafc; }
    .btn-ghost { border-color:transparent; }
    .btn-danger { border-color:#fecaca; }
    .btn-danger:hover { background:#fef2f2; }
    .badge { display:inline-block; padding:2px 8px; border-radius:999px; border:1px solid #e5e7eb; font-size:12px; }
  .note { color:var(--muted); font-size:13px; }
  </style>
</head>
<body>
  <div class="wrap">
    <p><a class="btn" href="/">← Kembali ke Daftar Rute</a></p>
    <h1>Tambah Rute</h1>

    <form id="formTambahRute" class="card" novalidate>
    <div class="grid cols-2">
      <div class="field">
        <label class="label">Nama rute <span class="hint">(mis. Koridor 1D Leuwipanjang – Soreang)</span></label>
        <input name="nama_rute" required>
      </div>

      <div class="field">
        <label class="label">Jam operasional <span class="hint">(mis. Senin–Minggu, 04.40–20.30)</span></label>
        <input name="jam_operasional" required>
      </div>

      <div class="field">
        <label class="label">Titik awal <span class="hint">(mis. Terminal Leuwipanjang)</span></label>
        <input name="titik_awal" required>
      </div>

      <div class="field">
        <label class="label">Titik akhir <span class="hint">(mis. Pengendapan Bus Soreang)</span></label>
        <input name="titik_akhir" required>
      </div>

      <div class="field">
        <label class="label">Headway <span class="hint">(mis. Setiap 15–20 menit)</span></label>
        <input name="headway_teks" placeholder="">
      </div>

      <div class="field">
        <label class="label">Keberangkatan pertama <span class="hint">(opsional, mis. 04:40)</span></label>
        <input name="keberangkatan_pertama" placeholder="">
      </div>

      <div class="field">
        <label class="label">Keberangkatan terakhir <span class="hint">(opsional, mis. 20:30)</span></label>
        <input name="keberangkatan_terakhir" placeholder="">
      </div>
    </div>

  <div class="field" style="margin-top:12px;">
    <label class="label">Catatan jadwal <span class="hint">(opsional)</span></label>
    <textarea name="catatan" rows="3" placeholder=""></textarea>
  </div>

  <div class="actions">
    <button class="btn" type="submit">Tambah</button>
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

document.getElementById('formTambahRute').addEventListener('submit', (e) => {
  const form = e.currentTarget;
  if (!form.checkValidity()) {
    e.preventDefault(); e.stopPropagation();
    [...form.querySelectorAll(':invalid')].forEach(el => {
      el.style.borderColor = '#ef4444';
      el.style.boxShadow = '0 0 0 3px #fee2e2';
    });
    return false;
  }
});
</script>
</body>
</html>
