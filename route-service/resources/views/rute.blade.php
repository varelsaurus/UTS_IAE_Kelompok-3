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
    .btn { padding:6px 10px; border:1px solid var(--line); background:#fff; border-radius:8px; cursor:pointer; }
    .btn:hover { background:#f9fafb; }
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

  <div class="table-wrap">
    <table id="tabel">
      <thead>
        <tr>
          <th style="width:35%">Rute</th>
          <th>Jadwal</th>
          <th class="right" style="width:20%">Selang (Headway)</th>
        </tr>
      </thead>
      <tbody id="body">
        <tr><td colspan="3" class="note">Memuat data…</td></tr>
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
    <tr>
      <td>
        <div><strong>${r.nama_rute}</strong></div>
        <div class="note">${r.titik_awal ?? ''} → ${r.titik_akhir ?? ''}</div>
        <details>
          <summary class="btn" data-rute="${r.id}">Lihat halte</summary>
          <div id="halte-${r.id}" class="note" style="padding-top:8px;">(klik untuk memuat halte)</div>
        </details>
      </td>
      <td>
        <div><strong>Jam operasional:</strong><br>${jamOperasional}</div>
      </td>
      <td class="right">
        <span class="badge">${headway}</span>
      </td>
    </tr>
  `;
}

function render(rows) {
  if (!rows.length) {
    elBody.innerHTML = `<tr><td colspan="3" class="note">Tidak ada data</td></tr>`;
    return;
  }
  elBody.innerHTML = rows.map(formatRow).join('');
  // Pasang listener untuk setiap summary "Lihat halte"
  document.querySelectorAll('summary[data-rute]').forEach(sum => {
    sum.addEventListener('click', async (e) => {
      const id = sum.getAttribute('data-rute');
      const box = document.getElementById('halte-'+id);
      if (box.getAttribute('data-loaded')) return; // sudah pernah muat
      box.textContent = 'Memuat halte…';
      try {
        const halte = await fetchJSON(`${API}/rute/${id}/halte`);
        if (!halte.length) {
          box.textContent = 'Belum ada halte';
        } else {
          halte.sort((a,b) => (a.urutan ?? 0) - (b.urutan ?? 0));

            box.innerHTML = '<ol style="margin:0; padding-left:16px;">' +
            halte.map(h => `<li>${h.nama_halte}</li>`).join('') +
            '</ol>';
        }
        box.setAttribute('data-loaded','1');
      } catch(err) {
        box.textContent = 'Gagal memuat halte';
      }
    });
  });
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

// events
elCari.addEventListener('input', () => render(filterData(elCari.value)));
elReload.addEventListener('click', loadRute);

// init
loadRute();
</script>
</body>
</html>
