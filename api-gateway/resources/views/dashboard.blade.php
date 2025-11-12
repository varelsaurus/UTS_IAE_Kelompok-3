<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Public Transportation Tracker</title>
  <style>
    :root {
      --bg: #f9fafb;
      --line: #e5e7eb;
      --blue: #2563eb;
      --danger: #dc2626;
      --gray: #6b7280;
    }
    body {
      font-family: system-ui, Arial, sans-serif;
      margin: 24px;
      background: var(--bg);
    }
    h1 { margin-bottom: 24px; font-size: 28px; }
    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 24px;
    }
    .card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }
    button {
      padding: 8px 12px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: 500;
    }
    .btn-primary { background: var(--blue); color: white; }
    .btn-danger { background: var(--danger); color: white; }
    .btn-secondary { background: #e5e7eb; }
    button:hover { opacity: 0.9; }
    ul { list-style: none; padding-left: 0; margin: 0; }
    li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid var(--line);
      gap: 8px;
    }
    li > div:first-child {
      flex: 1;
      min-width: 0;
    }
    small { color: var(--gray); display: block; }
    .actions {
      display: flex;
      flex-shrink: 0;
      gap: 6px;
      align-items: center;
    }

    /* Modal */
    .modal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 50;
    }
    .modal.active { display: flex; }
    .modal-content {
      background: white;
      border-radius: 12px;
      padding: 20px;
      width: 400px;
    }
    .modal-content h3 { margin-top: 0; }
    input {
      padding: 8px;
      border: 1px solid var(--line);
      border-radius: 6px;
      width: 100%;
      margin-bottom: 8px;
    }
  </style>
</head>
<body>
  <h1>Public Transportation Tracker</h1>

  <div class="grid">
    <!-- BUSES -->
    <div class="card">
      <div class="toolbar">
        <h2>Buses</h2>
        <button class="btn-primary" id="btnShowAddBus">+ Add Bus</button>
      </div>
      <ul id="buses"></ul>
    </div>

    <!-- ROUTES -->
    <div class="card">
      <div class="toolbar">
        <h2>Routes</h2>
        <button class="btn-primary" id="btnShowAddRoute">+ Add Route</button>
      </div>
      <ul id="routes"></ul>
    </div>
  </div>

  <!-- MODAL TAMBAH BUS -->
  <div class="modal" id="modalAddBus">
    <div class="modal-content">
      <h3>Tambah Bus</h3>
      <form id="formAddBus">
        <input name="code" placeholder="Kode Bus (mis. B-01)" required>
        <input name="route_id" placeholder="Route ID (mis. 1)" required>
        <input name="capacity" placeholder="Kapasitas (mis. 40)" required>
        <div style="display:flex;justify-content:end;gap:8px;margin-top:12px">
          <button type="button" id="btnCancelBus" class="btn-secondary">Batal</button>
          <button type="submit" class="btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDIT BUS -->
  <div class="modal" id="modalEditBus">
    <div class="modal-content">
      <h3>Edit Bus</h3>
      <form id="formEditBus">
        <input name="code" placeholder="Kode Bus" required>
        <input name="route_id" placeholder="Route ID" required>
        <input name="capacity" placeholder="Kapasitas" required>
        <div style="display:flex;justify-content:end;gap:8px;margin-top:12px">
          <button type="button" id="btnCancelEditBus" class="btn-secondary">Batal</button>
          <button type="submit" class="btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL TAMBAH ROUTE -->
  <div class="modal" id="modalAddRoute">
    <div class="modal-content">
      <h3>Tambah Rute</h3>
      <form id="formAddRoute">
        <input name="nama_rute" placeholder="Nama Rute" required>
        <input name="titik_awal" placeholder="Titik Awal" required>
        <input name="titik_akhir" placeholder="Titik Akhir" required>
        <div style="display:flex;justify-content:end;gap:8px;margin-top:12px">
          <button type="button" id="btnCancelRoute" class="btn-secondary">Batal</button>
          <button type="submit" class="btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>

<!-- MODAL EDIT ROUTE -->
<div class="modal" id="modalEditRoute">
  <div class="modal-content">
    <h3>Edit Rute</h3>
    <form id="formEditRoute">
      <input name="nama_rute" placeholder="Nama Rute" required>
      <input name="titik_awal" placeholder="Titik Awal" required>
      <input name="titik_akhir" placeholder="Titik Akhir" required>
      <div style="display:flex;justify-content:end;gap:8px;margin-top:12px">
        <button type="button" id="btnCancelEditRoute" class="btn-secondary">Batal</button>
        <button type="submit" class="btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
const API = 'http://127.0.0.1:8000/api';
let editBusId = null;
let editRouteId = null;

async function fetchJSON(url) {
  const res = await fetch(url);
  if (!res.ok) throw new Error('HTTP ' + res.status);
  return res.json();
}

async function load(path, el) {
  try {
    const data = await fetchJSON(API + path);
    document.getElementById(el).innerHTML = data.map(x => `
      <li>
        <div>
          <strong>${x.code || x.nama_rute}</strong>
          ${x.route_id ? `<small>Route ID: ${x.route_id}</small>` : ''}
          ${x.titik_awal ? `<small>${x.titik_awal} → ${x.titik_akhir}</small>` : ''}
          ${x.capacity ? `<small>Cap: ${x.capacity}</small>` : ''}
        </div>
        <div class="actions">
          ${path.includes('buses')
            ? `<button class="btn-secondary" onclick="showEditBus(${x.id})">✏️</button>
               <button class="btn-danger" onclick="deleteBus(${x.id})">🗑</button>`
            : `<button class="btn-secondary" onclick="showEditRoute(${x.id})">✏️</button>
               <button class="btn-danger" onclick="deleteRoute(${x.id})">🗑</button>`}
        </div>
      </li>
    `).join('');
  } catch (err) {
    console.error('Load error', path, err);
    document.getElementById(el).innerHTML = `<li style="color:#c00">Gagal memuat: ${err.message}</li>`;
  }
}

/* ----------------- BUS ----------------- */
async function deleteBus(id) {
  if (!confirm('Hapus bus ini?')) return;
  try {
    const res = await fetch(`${API}/buses/${id}`, { method: 'DELETE' });
    if (!res.ok) {
      const txt = await res.text().catch(()=> '');
      throw new Error(`HTTP ${res.status} ${txt}`);
    }
    await load('/buses','buses');
  } catch (err) {
    alert('Gagal menghapus bus: ' + err.message);
    console.error(err);
  }
}

function showEditBus(id) {
  editBusId = id;
  fetch(`${API}/buses/${id}`).then(r=>r.json()).then(bus=>{
    const form = document.getElementById('formEditBus');
    form.code.value = bus.code || '';
    form.route_id.value = bus.route_id || '';
    form.capacity.value = bus.capacity || '';
    document.getElementById('modalEditBus').classList.add('active');
  }).catch(err => {
    alert('Gagal memuat data bus: ' + err.message);
    console.error(err);
  });
}

document.getElementById('formEditBus').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = Object.fromEntries(fd.entries());
  try {
    const res = await fetch(`${API}/buses/${editBusId}`, {
      method: 'PUT',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(data)
    });
    if (!res.ok) {
      const txt = await res.text().catch(()=> '');
      throw new Error(`HTTP ${res.status} ${txt}`);
    }
    document.getElementById('modalEditBus').classList.remove('active');
    await load('/buses','buses');
  } catch (err) {
    alert('Gagal menyimpan perubahan bus: ' + err.message);
    console.error(err);
  }
});

document.getElementById('formAddBus').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = Object.fromEntries(fd.entries());
  try {
    const res = await fetch(`${API}/buses`, {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(data)
    });
    if (!res.ok) {
      const txt = await res.text().catch(()=> '');
      throw new Error(`HTTP ${res.status} ${txt}`);
    }
    e.target.reset();
    document.getElementById('modalAddBus').classList.remove('active');
    // minor delay to ensure backend ready
    setTimeout(()=> load('/buses','buses'), 150);
  } catch (err) {
    alert('Gagal menambah bus: ' + err.message);
    console.error(err);
  }
});

/* ----------------- ROUTE ----------------- */
async function deleteRoute(id) {
  if (!confirm('Hapus rute ini?')) return;
  try {
    const res = await fetch(`${API}/rute/${id}`, { method: 'DELETE' });
    if (!res.ok) {
      const txt = await res.text().catch(()=> '');
      throw new Error(`HTTP ${res.status} ${txt}`);
    }
    await load('/rute','routes');
  } catch (err) {
    alert('Gagal menghapus rute: ' + err.message);
    console.error(err);
  }
}

function showEditRoute(id) {
  editRouteId = id;
  fetch(`${API}/rute/${id}`).then(r=>r.json()).then(route=>{
    const form = document.getElementById('formEditRoute');
    form.nama_rute.value = route.nama_rute || '';
    form.titik_awal.value = route.titik_awal || '';
    form.titik_akhir.value = route.titik_akhir || '';
    document.getElementById('modalEditRoute').classList.add('active');
  }).catch(err => {
    alert('Gagal memuat data rute: ' + err.message);
    console.error(err);
  });
}

document.getElementById('formEditRoute').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = Object.fromEntries(fd.entries());
  try {
    const res = await fetch(`${API}/rute/${editRouteId}`, {
      method: 'PUT',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(data)
    });
    if (!res.ok) {
      const txt = await res.text().catch(()=> '');
      throw new Error(`HTTP ${res.status} ${txt}`);
    }
    document.getElementById('modalEditRoute').classList.remove('active');
    setTimeout(()=> load('/rute','routes'), 150);
  } catch (err) {
    alert('Gagal menyimpan perubahan rute: ' + err.message);
    console.error(err);
  }
});

document.getElementById('formAddRoute').addEventListener('submit', async e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  const data = Object.fromEntries(fd.entries());
  try {
    const res = await fetch(`${API}/rute`, {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify(data)
    });
    if (!res.ok) {
      const txt = await res.text().catch(()=> '');
      throw new Error(`HTTP ${res.status} ${txt}`);
    }
    e.target.reset();
    document.getElementById('modalAddRoute').classList.remove('active');
    setTimeout(()=> load('/rute','routes'), 150);
  } catch (err) {
    alert('Gagal menambah rute: ' + err.message);
    console.error(err);
  }
});

// modal open/close handlers (assume buttons exist)
document.getElementById('btnShowAddBus').onclick = () => document.getElementById('modalAddBus').classList.add('active');
document.getElementById('btnCancelBus').onclick = () => document.getElementById('modalAddBus').classList.remove('active');
document.getElementById('btnShowAddRoute').onclick = () => document.getElementById('modalAddRoute').classList.add('active');
document.getElementById('btnCancelRoute').onclick = () => document.getElementById('modalAddRoute').classList.remove('active');
document.getElementById('btnCancelEditBus').onclick = () => document.getElementById('modalEditBus').classList.remove('active');
document.getElementById('btnCancelEditRoute').onclick = () => document.getElementById('modalEditRoute').classList.remove('active');

// init
load('/buses','buses');
load('/rute','routes');
</script>
</body>
</html>
