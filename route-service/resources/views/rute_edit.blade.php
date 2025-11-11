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

      <div class="card" style="margin-top:16px">
        <h3 style="margin:0 0 10px">Kelola Halte</h3>
        <div class="label">Daftar halte (urutan angka)</div>
        <div id="halteWrap"></div>
        <div class="actions" style="margin-top:8px">
          <button type="button" class="btn" id="btnAddHalteEdit">+ Tambah baris halte</button>
          <button type="button" class="btn" id="btnSaveHalte">💾 Simpan Halte</button>
          <span id="statusHalte" class="note"></span>
        </div>
      </div>

      <div class="field" style="margin-top:12px;">
        <label class="label">Catatan jadwal (opsional)</label>
        <textarea name="catatan" rows="3"></textarea>
      </div>

      <div class="actions">
        <button class="btn" type="submit" id="btnSimpan">Simpan Perubahan</button>
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

function halteRow(h=null, idx=null) {
  const nama = h?.nama_halte ?? '';
  const urut = h?.urutan ?? (idx ?? 0) + 1;
  return `
    <div class="grid cols-2 halte-row" style="align-items:center; margin-bottom:8px">
      <input class="halte-nama" value="${nama}" placeholder="Nama halte">
      <input class="halte-urutan" type="number" min="1" value="${urut}" placeholder="Urutan" style="width:140px">
    </div>
  `;
}

async function loadHalteEdit() {
  const wrap = document.getElementById('halteWrap');
  wrap.innerHTML = '<div class="note">Memuat halte…</div>';
  try {
    const list = await (await fetch(`${API}/rute/${id}/halte`)).json();
    list.sort((a,b)=>(a.urutan??0)-(b.urutan??0));
    wrap.innerHTML = list.map((h,i)=>halteRow(h,i)).join('') || halteRow();
  } catch(e) {
    wrap.innerHTML = '<div class="note">Gagal memuat halte</div>';
  }
}

document.getElementById('btnAddHalteEdit').addEventListener('click', () => {
  const wrap = document.getElementById('halteWrap');
  wrap.insertAdjacentHTML('beforeend', halteRow(null, wrap.querySelectorAll('.halte-row').length));
});

document.getElementById('btnSaveHalte').addEventListener('click', async ()=>{
  const status = document.getElementById('statusHalte');
  status.textContent = 'Menyimpan…';

  // kumpulkan & sort by urutan
  const rows = [...document.querySelectorAll('.halte-row')];
  const data = rows.map(row => ({
    nama_halte: row.querySelector('.halte-nama').value.trim(),
    urutan: Number(row.querySelector('.halte-urutan').value || 0),
  })).filter(x => x.nama_halte);

  data.sort((a,b)=>a.urutan-b.urutan);

  try {
    const res = await fetch(`${API}/rute/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ halte_daftar: data.map(x=>x.nama_halte) }) // kirim sebagai array nama, urutan = index+1
    });
    if (!res.ok) throw new Error('HTTP '+res.status);
    status.textContent = '✅ Halte tersimpan';
    await loadHalteEdit(); // reload
  } catch(err){
    console.error(err);
    status.textContent = '❌ Gagal menyimpan halte';
  }
});


await loadData();
await loadHalteEdit();

</script>
</body>
</html>
