<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <title>Edit Rute</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root { --line:#e5e7eb; --muted:#666; --bg:#fff; }
    * { box-sizing:border-box; }
    body { font-family:system-ui, Arial, sans-serif; margin:24px; }
    .wrap { max-width:880px; margin:0 auto; }
    .card { border:1px solid var(--line); border-radius:16px; padding:20px; background:var(--bg); }

    .grid { display:grid; gap:16px; }
    @media (min-width:768px) { .grid.cols-2 { grid-template-columns:1fr 1fr; } }

    .field { display:flex; flex-direction:column; gap:6px; }
    .label { font-size:14px; font-weight:600; color:#111; }
    .hint { font-size:12px; color:var(--muted); }

    input, textarea {
      width:100%; padding:12px 14px; border:1px solid var(--line);
      border-radius:10px; outline:none; background:#fff;
    }
    input:focus, textarea:focus { border-color:#94a3b8; box-shadow:0 0 0 3px #e2e8f0; }

    .actions { display:flex; gap:10px; align-items:center; margin-top:12px; }
    .btn { padding:10px 14px; border:1px solid var(--line); border-radius:10px; background:#fff; cursor:pointer; }
    .btn:hover { background:#f8fafc; }
    .note { color:var(--muted); font-size:13px; }
  </style>
</head>
<body>
  <div class="wrap">
    <p><a class="btn" href="/">← Kembali ke Daftar Rute</a></p>
    <h1>Edit Rute</h1>

    <form id="formEditRute" class="card" novalidate>
      <div class="grid cols-2">
        <div class="field">
          <label class="label">Nama rute</label>
          <input name="nama_rute" required>
          <div class="hint">Contoh: Koridor 1D Leuwipanjang – Soreang</div>
        </div>

        <div class="field">
          <label class="label">Jam operasional</label>
          <input name="jam_operasional" required>
          <div class="hint">Contoh: Senin–Minggu, 04.40–20.30</div>
        </div>

        <div class="field">
          <label class="label">Titik awal</label>
          <input name="titik_awal" required>
        </div>

        <div class="field">
          <label class="label">Titik akhir</label>
          <input name="titik_akhir" required>
        </div>

        <div class="field">
          <label class="label">Headway</label>
          <input name="headway_teks" placeholder="Contoh: Setiap 15–20 menit">
        </div>

        <div class="field">
          <label class="label">Keberangkatan pertama (opsional)</label>
          <input name="keberangkatan_pertama" placeholder="04:40">
        </div>

        <div class="field">
          <label class="label">Keberangkatan terakhir (opsional)</label>
          <input name="keberangkatan_terakhir" placeholder="20:30">
        </div>
      </div>

      <div class="field" style="margin-top:12px;">
        <label class="label">Catatan jadwal (opsional)</label>
        <textarea name="catatan" rows="3"></textarea>
      </div>

      <div class="actions">
        <button class="btn" type="submit" id="btnSimpan">💾 Simpan Perubahan</button>
        <span id="statusEdit" class="note"></span>
      </div>
    </form>
  </div>

<script>
const API = location.origin + '/api';
const elForm = document.getElementById('formEditRute');
const elStatus = document.getElementById('statusEdit');
const btnSimpan = document.getElementById('btnSimpan');

// Ambil ID dari query ?id=...
const params = new URLSearchParams(location.search);
const id = params.get('id');
if (!id) { alert('ID rute tidak ditemukan'); location.href='/'; }

function markInvalid(form) {
  const invalids = [...form.querySelectorAll(':invalid')];
  invalids.forEach(el => { el.style.borderColor = '#ef4444'; el.style.boxShadow = '0 0 0 3px #fee2e2'; });
  return invalids.length === 0;
}

async function loadData() {
  elStatus.textContent = 'Memuat data...';
  try {
    const r = await (await fetch(`${API}/rute/${id}`)).json();
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
  if (!markInvalid(elForm)) return;

  elStatus.textContent = 'Menyimpan...';
  btnSimpan.disabled = true;

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
    const res = await fetch(`${API}/rute/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    elStatus.textContent = '✅ Rute diperbarui';
    setTimeout(() => location.href = '/', 800);
  } catch (err) {
    console.error(err);
    elStatus.textContent = '❌ Gagal memperbarui';
    alert('Gagal memperbarui rute.\n' + err.message);
  } finally {
    btnSimpan.disabled = false;
  }
});

loadData();
</script>
</body>
</html>
