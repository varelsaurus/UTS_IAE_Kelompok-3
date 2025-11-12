<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Public Transportation Tracker – Rute</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    :root { --bg:#fafafa; --fg:#111; --muted:#666; --line:#e5e7eb; }
    * { box-sizing:border-box; }
    body { font-family:system-ui, Arial, sans-serif; margin:24px; background:var(--bg); color:var(--fg); }
    h1 { margin:0 0 16px; }
    .toolbar { display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap; }
    input[type="search"] { padding:8px 12px; border:1px solid var(--line); border-radius:8px; width:280px; }
    .table-wrap { overflow:auto; border:1px solid var(--line); border-radius:12px; background:#fff; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:12px; border-bottom:1px solid var(--line); vertical-align:top; }
    th { text-align:left; background:#f8fafc; font-weight:600; }
    .note { color:var(--muted); font-size:13px; }
    .right { text-align:right; white-space:nowrap; }
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
    details { margin-top:6px; }
    .badge { display:inline-block; padding:2px 8px; border-radius:999px; border:1px solid var(--line); font-size:12px; }
    footer { margin-top:24px; color:var(--muted); font-size:13px; }
  </style>
</head>
<body>
  <h1>Daftar Rute</h1>

  <div class="toolbar">
    <input id="cari" type="search" placeholder="Cari rute / asal / tujuan…" />
    <button class="btn" id="muatUlang">Muat ulang</button>
  </div>

  <p style="margin:8px 0">
    <a class="btn" href="/rute/tambah">Tambah Rute</a>
  </p>

  <div class="table-wrap">
    <table id="tabel">
      <thead>
        <tr>
            <th style="width:40%">Rute</th>
            <th style="width:20%">Jadwal</th>
            <th style="width:20%">Headway</th>
            <th style="width:20%">Aksi</th>
        </tr>
      </thead>
      <tbody id="body">
        <tr><td colspan="4" class="note">Memuat data…</td></tr>
      </tbody>
    </table>
  </div>

  <footer>Sumber data: API lokal /api/rute</footer>

<script>
const API = location.origin + '/api';

const elBody = document.getElementById('body');
const elCari = document.getElementById('cari');
const elReload = document.getElementById('muatUlang');

let dataRute = [];

async function fetchJSON(url) {
  const r = await fetch(url);
  if (!r.ok) throw new Error('HTTP '+r.status);
  return r.json();
}

function formatRow(r) {
  const jamOperasional = r.jam_operasional
      ?? ((r.keberangkatan_pertama && r.keberangkatan_terakhir)
          ? `${r.keberangkatan_pertama} – ${r.keberangkatan_terakhir}`
          : '-');
  const headway = r.headway || '-';

  return `
    <tr id="rute-${r.id}">
      <td>
        <div><strong>${r.nama_rute}</strong></div>
        <div class="note">${r.titik_awal ?? ''} → ${r.titik_akhir ?? ''}</div>
        <details>
        <summary class="btn lihat-halte" data-rute="${r.id}">▶ Lihat halte</summary>
        <div id="halte-${r.id}" class="note" style="padding-top:8px;">(klik untuk memuat halte)</div>
        <div style="margin-top:8px">

        </div>
        </details>
      </td>

      <td>
        <div><strong>Jam operasional:</strong><br>${jamOperasional}</div>
      </td>

      <td style="text-align:right;">
        <span class="badge">${headway}</span>
      </td>

      <td style="text-align:right;">
        <a href="/rute/edit?id=${r.id}" class="btn">✏️ Edit</a>
        <button class="btn btn-danger btn-delete" data-id="${r.id}">🗑 Hapus</button>
      </td>
    </tr>
  `;
}

function render(rows) {
  if (!rows.length) {
    elBody.innerHTML = `<tr><td colspan="4" class="note">Tidak ada data</td></tr>`;
    return;
  }
  elBody.innerHTML = rows.map(formatRow).join('');
  // Pasang listener untuk setiap summary "Lihat halte"
  style="width:20%; text-align:right;"
}

function filterData(q) {
  const s = (q || '').toLowerCase().trim();
  if (!s) return dataRute;
  return dataRute.filter(r =>
    (r.nama_rute||'').toLowerCase().includes(s) ||
    (r.titik_awal||'').toLowerCase().includes(s) ||
    (r.titik_akhir||'').toLowerCase().includes(s)
  );
}

async function loadRute() {
  elBody.innerHTML = `<tr><td colspan="3" class="note">Memuat data…</td></tr>`;
  try {
    const json = await fetchJSON(`${API}/rute`);
    // API index() kita sudah bentuk ringkasan, tapi tambahkan fallback properti
    dataRute = json.map(x => ({
      id: x.id,
      nama_rute: x.nama_rute,
      titik_awal: x.titik_awal ?? '',
      titik_akhir: x.titik_akhir ?? '',
      jam_operasional: x.jam_operasional ?? null,
      headway: x.headway ?? null,
      keberangkatan_pertama: x.keberangkatan_pertama ?? null,
      keberangkatan_terakhir: x.keberangkatan_terakhir ?? null,
    }));
    render(filterData(elCari.value));
  } catch (e) {
    elBody.innerHTML = `<tr><td colspan="3" class="note">Gagal memuat data (${e.message})</td></tr>`;
  }
}

// Delegasi klik tombol hapus
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.btn-delete');
  if (!btn) return;

  const id = btn.dataset.id;
  if (!confirm('Yakin ingin menghapus rute ini?')) return;

  try {
    const res = await fetch(`${API}/rute/${id}`, { method: 'DELETE' });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);

    // Hapus baris langsung dari tabel tanpa reload
    const row = document.getElementById(`rute-${id}`);
    if (row) row.remove();

    alert('✅ Rute berhasil dihapus');
  } catch (err) {
    console.error(err);
    alert('❌ Gagal menghapus rute\n' + err.message);
  }
});

// Tambah/replace halte
document.addEventListener('submit', async (e) => {
  const form = e.target.closest('form.form-add-halte');
  if (!form) return;
  e.preventDefault();

  const ruteId = form.getAttribute('data-rute');
  const fd = new FormData(form);
  const body = { nama_halte: (fd.get('nama_halte')||'').trim(), urutan: Number(fd.get('urutan')||0) };
  const status = form.querySelector('.add-status');
  status.textContent = 'Mengirim…';

  try {
    const res = await fetch(`${API}/rute/${ruteId}/halte`, {
      method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body)
    });
    if (!res.ok) throw new Error('HTTP '+res.status);
    status.textContent = '✅ Tersimpan';
    // reload daftar halte kecilnya
    const sum = document.querySelector(`summary[data-rute="${ruteId}"]`);
    const box = document.getElementById('halte-'+ruteId);
    box.removeAttribute('data-loaded'); sum.click(); sum.click(); // tutup-buka untuk refresh
    form.reset();
  } catch(err) {
    status.textContent = '❌ Gagal';
    alert('Gagal menambah/replace halte: '+err.message);
  }
});

// Klik Edit → inline edit (ubah jadi input)
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.hlt-edit');
  if (!btn) return;
  const li = btn.closest('li');
  const id = btn.getAttribute('data-halte');
  const ruteId = btn.getAttribute('data-rute');
  const nama = li.querySelector('.hlt-nama').textContent.trim();
  const urutan = li.querySelector('.hlt-urutan').textContent.trim();
  li.innerHTML = `
    <input class="in-nama" value="${nama}" style="width:55%">
    <input class="in-urutan" type="number" min="1" value="${urutan}" style="width:100px">
    <button class="btn hlt-simpan" data-halte="${id}" data-rute="${ruteId}">💾 Simpan</button>
    <button class="btn hlt-batal"  data-rute="${ruteId}">Batal</button>
  `;
});

// Simpan hasil edit
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.hlt-simpan');
  if (!btn) return;
  const li = btn.closest('li');
  const id = btn.getAttribute('data-halte');
  const ruteId = btn.getAttribute('data-rute');
  const body = {
    nama_halte: li.querySelector('.in-nama').value.trim(),
    urutan:     Number(li.querySelector('.in-urutan').value||0)
  };

  try {
    const res = await fetch(`${API}/halte/${id}`, {
      method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body)
    });
    if (!res.ok) throw new Error('HTTP '+res.status);
    // refresh daftar halte
    const sum = document.querySelector(`summary[data-rute="${ruteId}"]`);
    const box = document.getElementById('halte-'+ruteId);
    box.removeAttribute('data-loaded'); sum.click(); sum.click();
  } catch(err) {
    alert('Gagal menyimpan perubahan halte: '+err.message);
  }
});

// Batal edit → reload daftar
document.addEventListener('click', (e) => {
  const btn = e.target.closest('.hlt-batal');
  if (!btn) return;
  const ruteId = btn.getAttribute('data-rute');
  const sum = document.querySelector(`summary[data-rute="${ruteId}"]`);
  const box = document.getElementById('halte-'+ruteId);
  box.removeAttribute('data-loaded'); sum.click(); sum.click();
});

// Hapus halte
document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.hlt-hapus');
  if (!btn) return;
  const id = btn.getAttribute('data-halte');
  if (!confirm('Hapus halte ini?')) return;
  try {
    const res = await fetch(`${API}/halte/${id}`, { method:'DELETE' });
    if (!res.ok) throw new Error('HTTP '+res.status);
    // hapus li langsung
    btn.closest('li')?.remove();
  } catch(err) {
    alert('Gagal menghapus halte: '+err.message);
  }
});

async function loadHalte(ruteId) {
  const box = document.getElementById('halte-' + ruteId);
  if (!box || box.getAttribute('data-loaded')) return;

  box.textContent = 'Memuat halte…';
  try {
    const halte = await fetchJSON(`${API}/rute/${ruteId}/halte`);
    if (!Array.isArray(halte)) throw new Error('Format tidak valid');

    halte.sort((a,b)=>(a.urutan??0)-(b.urutan??0));
    const html = halte.length
    ? '<ol style="margin:0; padding-left:16px;">'
        + halte.map(h => `
          <li style="margin-bottom:4px;">
            ${h.nama_halte}
          </li>
        `).join('')
        + '</ol>'
    : '<div class="note">Belum ada halte untuk rute ini.</div>';


    box.innerHTML = html + `
      <div style="margin-top:8px">
      </div>
    `;
    
    box.setAttribute('data-loaded','1');
  } catch (e) {
    console.error(e);
    box.innerHTML = `
      <div class="note">Gagal memuat halte (${e.message}).</div>
      <div style="margin-top:8px">
      </div>
    `;
    // jangan set data-loaded, biar bisa dicoba lagi
  }
}

// 1) Klik "Lihat halte" → muat jika belum
document.addEventListener('click', (e) => {
  const sum = e.target.closest('summary.lihat-halte');
  if (!sum) return;
  loadHalte(sum.getAttribute('data-rute'));
});


// events
elCari.addEventListener('input', () => render(filterData(elCari.value)));
elReload.addEventListener('click', loadRute);

// init
loadRute();
</script>
</body>
</html>
