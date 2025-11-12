<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Public Transportation Tracker</title>
  <style>
    * { box-sizing: border-box; }
    
    :root {
      --bg: #f0f4f8;
      --bg-card: #ffffff;
      --line: #e2e8f0;
      --blue: #2563eb;
      --blue-light: #dbeafe;
      --blue-dark: #1e40af;
      --green: #10b981;
      --danger: #ef4444;
      --danger-light: #fee2e2;
      --gray: #6b7280;
      --gray-light: #f3f4f6;
      --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background: var(--bg);
      color: #1f2937;
    }

    /* Header */
    header {
      background: linear-gradient(135deg, var(--blue) 0%, var(--blue-dark) 100%);
      color: white;
      padding: 32px 24px;
      border-radius: 16px;
      margin-bottom: 32px;
      box-shadow: var(--shadow);
    }

    header h1 {
      margin: 0 0 8px 0;
      font-size: 32px;
      font-weight: 700;
    }

    header p {
      margin: 0;
      opacity: 0.9;
      font-size: 14px;
    }

    /* Navigation Tabs */
    .nav-tabs {
      display: flex;
      gap: 16px;
      margin-bottom: 24px;
      border-bottom: 2px solid var(--line);
      padding-bottom: 0;
      flex-wrap: wrap;
    }

    .nav-tab {
      padding: 12px 16px;
      background: none;
      border: none;
      border-bottom: 3px solid transparent;
      cursor: pointer;
      font-size: 15px;
      font-weight: 500;
      color: var(--gray);
      transition: all 0.2s ease;
      margin-bottom: -2px;
    }

    .nav-tab:hover {
      color: var(--blue);
    }

    .nav-tab.active {
      color: var(--blue);
      border-bottom-color: var(--blue);
    }

    /* View Sections */
    .view-section {
      display: none;
    }

    .view-section.active {
      display: block;
    }

    /* Grid Layout */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
      gap: 24px;
      margin-bottom: 32px;
    }

    @media (max-width: 1024px) {
      .grid {
        grid-template-columns: 1fr;
      }
    }

    .card {
      background: var(--bg-card);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 24px;
      box-shadow: var(--shadow-sm);
      transition: box-shadow 0.2s ease;
    }

    .card:hover {
      box-shadow: var(--shadow);
    }

    .card h2 {
      margin-top: 0;
      margin-bottom: 20px;
      font-size: 20px;
      color: #111827;
    }

    /* Toolbar */
    .toolbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      gap: 12px;
      flex-wrap: wrap;
    }

    /* Buttons */
    button {
      padding: 10px 16px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: 500;
      font-size: 14px;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-primary {
      background: var(--blue);
      color: white;
    }

    .btn-primary:hover {
      background: var(--blue-dark);
      transform: translateY(-1px);
    }

    .btn-secondary {
      background: var(--gray-light);
      color: #374151;
    }

    .btn-secondary:hover {
      background: var(--line);
    }

    .btn-danger {
      background: var(--danger-light);
      color: var(--danger);
    }

    .btn-danger:hover {
      background: var(--danger);
      color: white;
    }

    .btn-small {
      padding: 6px 10px;
      font-size: 12px;
    }

    /* Lists */
    ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px 0;
      border-bottom: 1px solid var(--line);
      gap: 16px;
    }

    li:last-child {
      border-bottom: none;
    }

    li > div:first-child {
      flex: 1;
      min-width: 0;
    }

    li strong {
      display: block;
      font-size: 15px;
      margin-bottom: 6px;
      color: #111827;
    }

    li small {
      color: var(--gray);
      font-size: 13px;
      display: block;
      line-height: 1.4;
    }

    .actions {
      display: flex;
      gap: 8px;
      align-items: center;
      flex-shrink: 0;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: var(--gray);
    }

    .empty-state svg {
      width: 64px;
      height: 64px;
      margin-bottom: 16px;
      opacity: 0.3;
    }

    /* Loading */
    .loading {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid var(--line);
      border-top-color: var(--blue);
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Modal */
    .modal {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 100;
      padding: 20px;
    }

    .modal.active {
      display: flex;
    }

    .modal-content {
      background: white;
      border-radius: 12px;
      padding: 32px;
      width: 100%;
      max-width: 500px;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-content h3 {
      margin: 0 0 24px 0;
      font-size: 22px;
      color: #111827;
    }

    .form-group {
      margin-bottom: 16px;
    }

    label {
      display: block;
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 8px;
      color: #374151;
    }

    input, textarea, select {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid var(--line);
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      transition: border-color 0.2s ease;
    }

    input:focus, textarea:focus, select:focus {
      outline: none;
      border-color: var(--blue);
      box-shadow: 0 0 0 3px var(--blue-light);
    }

    textarea {
      resize: vertical;
      min-height: 100px;
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 24px;
    }

    /* Status Badge */
    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 16px;
      font-size: 12px;
      font-weight: 600;
    }

    .badge-success {
      background: #dcfce7;
      color: #166534;
    }

    .badge-info {
      background: #dbeafe;
      color: #0c4a6e;
    }

    /* Stats */
    .stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 24px;
    }

    .stat-box {
      background: var(--bg-card);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 20px;
      text-align: center;
    }

    .stat-number {
      font-size: 32px;
      font-weight: 700;
      color: var(--blue);
      margin-bottom: 8px;
    }

    .stat-label {
      font-size: 14px;
      color: var(--gray);
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <h1>🚌 Public Transportation Tracker</h1>
    <p>Kelola Bus dan Rute Perjalanan Transportasi Umum</p>
  </header>

  <!-- Navigation -->
  <div class="nav-tabs">
    <button class="nav-tab active" data-view="dashboard">📊 Dashboard</button>
    <button class="nav-tab" data-view="buses">🚌 Buses</button>
    <button class="nav-tab" data-view="routes">🛣️ Routes</button>
  </div>

  <!-- DASHBOARD VIEW -->
  <div id="dashboard" class="view-section active">
    <div class="stats">
      <div class="stat-box">
        <div class="stat-number" id="totalBuses">-</div>
        <div class="stat-label">Total Bus</div>
      </div>
      <div class="stat-box">
        <div class="stat-number" id="totalRoutes">-</div>
        <div class="stat-label">Total Rute</div>
      </div>
      <div class="stat-box">
        <div class="stat-number" id="activeRoutes">-</div>
        <div class="stat-label">Rute Aktif</div>
      </div>
    </div>

    <div class="grid">
      <!-- BUSES PREVIEW -->
      <div class="card">
        <div class="toolbar">
          <h2>🚌 Bus Terbaru</h2>
          <button class="btn-primary btn-small" id="btnShowAddBusDash">+ Tambah</button>
        </div>
        <ul id="busesPreview"></ul>
      </div>

      <!-- ROUTES PREVIEW -->
      <div class="card">
        <div class="toolbar">
          <h2>🛣️ Rute Terbaru</h2>
          <button class="btn-primary btn-small" id="btnShowAddRouteDash">+ Tambah</button>
        </div>
        <ul id="routesPreview"></ul>
      </div>
    </div>
  </div>

  <!-- BUSES VIEW -->
  <div id="buses" class="view-section">
    <div class="card">
      <div class="toolbar">
        <h2>🚌 Daftar Bus</h2>
        <button class="btn-primary" id="btnShowAddBus">+ Tambah Bus Baru</button>
      </div>
      <ul id="busesList"></ul>
    </div>
  </div>

  <!-- ROUTES VIEW -->
  <div id="routes" class="view-section">
    <div class="card">
      <div class="toolbar">
        <h2>🛣️ Daftar Rute</h2>
        <button class="btn-primary" id="btnShowAddRoute">+ Tambah Rute Baru</button>
      </div>
      <ul id="routesList"></ul>
    </div>
  </div>

  <!-- MODAL TAMBAH BUS -->
  <div class="modal" id="modalAddBus">
    <div class="modal-content">
      <h3>Tambah Bus Baru</h3>
      <form id="formAddBus">
        <div class="form-group">
          <label for="buscode">Kode Bus *</label>
          <input id="buscode" name="code" placeholder="mis. B-01, B-002" required>
        </div>
        <div class="form-group">
          <label for="busroute">Route ID *</label>
          <input id="busroute" name="route_id" placeholder="mis. 1, 2" type="number" required>
        </div>
        <div class="form-group">
          <label for="buscapacity">Kapasitas *</label>
          <input id="buscapacity" name="capacity" placeholder="mis. 40" type="number" required>
        </div>
        <div class="form-actions">
          <button type="button" id="btnCancelBus" class="btn-secondary">Batal</button>
          <button type="submit" class="btn-primary">Tambah Bus</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDIT BUS -->
  <div class="modal" id="modalEditBus">
    <div class="modal-content">
      <h3>Edit Bus</h3>
      <form id="formEditBus">
        <div class="form-group">
          <label for="editbuscode">Kode Bus *</label>
          <input id="editbuscode" name="code" placeholder="Kode Bus" required>
        </div>
        <div class="form-group">
          <label for="editbusroute">Route ID *</label>
          <input id="editbusroute" name="route_id" placeholder="Route ID" type="number" required>
        </div>
        <div class="form-group">
          <label for="editbuscapacity">Kapasitas *</label>
          <input id="editbuscapacity" name="capacity" placeholder="Kapasitas" type="number" required>
        </div>
        <div class="form-actions">
          <button type="button" id="btnCancelEditBus" class="btn-secondary">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL TAMBAH ROUTE -->
  <div class="modal" id="modalAddRoute">
    <div class="modal-content">
      <h3>Tambah Rute Baru</h3>
      <form id="formAddRoute">
        <div class="form-group">
          <label for="routename">Nama Rute *</label>
          <input id="routename" name="nama_rute" placeholder="mis. Terminal A - Terminal B" required>
        </div>
        <div class="form-group">
          <label for="routestart">Titik Awal *</label>
          <input id="routestart" name="titik_awal" placeholder="mis. Terminal Pusat" required>
        </div>
        <div class="form-group">
          <label for="routeend">Titik Akhir *</label>
          <input id="routeend" name="titik_akhir" placeholder="mis. Terminal Cabang" required>
        </div>
        <div class="form-actions">
          <button type="button" id="btnCancelRoute" class="btn-secondary">Batal</button>
          <button type="submit" class="btn-primary">Tambah Rute</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDIT ROUTE -->
  <div class="modal" id="modalEditRoute">
    <div class="modal-content">
      <h3>Edit Rute</h3>
      <form id="formEditRoute">
        <div class="form-group">
          <label for="editroutename">Nama Rute *</label>
          <input id="editroutename" name="nama_rute" placeholder="Nama Rute" required>
        </div>
        <div class="form-group">
          <label for="editroutestart">Titik Awal *</label>
          <input id="editroutestart" name="titik_awal" placeholder="Titik Awal" required>
        </div>
        <div class="form-group">
          <label for="editrouteend">Titik Akhir *</label>
          <input id="editrouteend" name="titik_akhir" placeholder="Titik Akhir" required>
        </div>
        <div class="form-actions">
          <button type="button" id="btnCancelEditRoute" class="btn-secondary">Batal</button>
          <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

<script>
const API = '/api';
let editBusId = null;
let editRouteId = null;
let busList = [];
let routeList = [];

// ============= Helper Functions =============
async function fetchJSON(url) {
  const res = await fetch(url);
  if (!res.ok) throw new Error('HTTP ' + res.status);
  return res.json();
}

function safe(v) {
  return v === undefined || v === null ? '' : v;
}

// ============= View Navigation =============
document.querySelectorAll('.nav-tab').forEach(btn => {
  btn.addEventListener('click', () => {
    const viewName = btn.dataset.view;
    
    // Update active tab
    document.querySelectorAll('.nav-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Update active view
    document.querySelectorAll('.view-section').forEach(v => v.classList.remove('active'));
    document.getElementById(viewName).classList.add('active');
  });
});

// ============= Dashboard Stats =============
function updateStats() {
  document.getElementById('totalBuses').textContent = busList.length;
  document.getElementById('totalRoutes').textContent = routeList.length;
  document.getElementById('activeRoutes').textContent = routeList.length;
  
  // Preview items (show max 3)
  const busPreview = busList.slice(0, 3).map(x => renderBusItem(x)).join('');
  const routePreview = routeList.slice(0, 3).map(x => renderRouteItem(x)).join('');
  
  document.getElementById('busesPreview').innerHTML = busPreview || '<li class="empty-state">Belum ada bus</li>';
  document.getElementById('routesPreview').innerHTML = routePreview || '<li class="empty-state">Belum ada rute</li>';
}

// ============= Render Functions =============
function renderBusItem(x) {
  return `
    <li>
      <div>
        <strong>${safe(x.code)}</strong>
        <small>Route: ${safe(x.route_id)} | Cap: ${safe(x.capacity)}</small>
        ${x.lat && x.lng ? `<small>📍 ${x.lat}, ${x.lng}</small>` : ''}
      </div>
      <div class="actions">
        <button class="btn-secondary btn-small" onclick="showEditBus(${x.id})" title="Edit">✏️</button>
        <button class="btn-danger btn-small" onclick="deleteBus(${x.id})" title="Delete">🗑</button>
      </div>
    </li>
  `;
}

function renderRouteItem(x) {
  return `
    <li>
      <div>
        <strong>${safe(x.nama_rute)}</strong>
        <small>${safe(x.titik_awal)} → ${safe(x.titik_akhir)}</small>
        ${x.jam_operasional ? `<small>⏰ ${x.jam_operasional}</small>` : ''}
      </div>
      <div class="actions">
        <button class="btn-secondary btn-small" onclick="showEditRoute(${x.id})" title="Edit">✏️</button>
        <button class="btn-danger btn-small" onclick="deleteRoute(${x.id})" title="Delete">🗑</button>
      </div>
    </li>
  `;
}

// ============= Load Data =============
async function loadBuses() {
  try {
    busList = await fetchJSON(API + '/buses');
    const html = busList.length ? busList.map(renderBusItem).join('') : '<li class="empty-state">Belum ada bus</li>';
    document.getElementById('busesList').innerHTML = html;
    updateStats();
  } catch (err) {
    console.error('Error loading buses:', err);
    document.getElementById('busesList').innerHTML = `<li style="color:var(--danger)">❌ Gagal memuat bus: ${err.message}</li>`;
  }
}

async function loadRoutes() {
  try {
    routeList = await fetchJSON(API + '/rute');
    const html = routeList.length ? routeList.map(renderRouteItem).join('') : '<li class="empty-state">Belum ada rute</li>';
    document.getElementById('routesList').innerHTML = html;
    updateStats();
  } catch (err) {
    console.error('Error loading routes:', err);
    document.getElementById('routesList').innerHTML = `<li style="color:var(--danger)">❌ Gagal memuat rute: ${err.message}</li>`;
  }
}

// ============= BUS Operations =============
async function deleteBus(id) {
  if (!confirm('Hapus bus ini? Aksi tidak bisa dibatalkan.')) return;
  try {
    const res = await fetch(`${API}/buses/${id}`, { method: 'DELETE' });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    await loadBuses();
  } catch (err) {
    alert('❌ Gagal menghapus bus: ' + err.message);
    console.error(err);
  }
}

function showEditBus(id) {
  editBusId = id;
  fetch(`${API}/buses/${id}`)
    .then(r => r.json())
    .then(bus => {
      document.getElementById('editbuscode').value = safe(bus.code);
      document.getElementById('editbusroute').value = safe(bus.route_id);
      document.getElementById('editbuscapacity').value = safe(bus.capacity);
      document.getElementById('modalEditBus').classList.add('active');
    })
    .catch(err => {
      alert('❌ Gagal memuat data bus: ' + err.message);
      console.error(err);
    });
}

document.getElementById('formAddBus').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  try {
    const res = await fetch(`${API}/buses`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    e.target.reset();
    document.getElementById('modalAddBus').classList.remove('active');
    setTimeout(() => loadBuses(), 200);
  } catch (err) {
    alert('❌ Gagal menambah bus: ' + err.message);
    console.error(err);
  }
});

document.getElementById('formEditBus').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  try {
    const res = await fetch(`${API}/buses/${editBusId}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    document.getElementById('modalEditBus').classList.remove('active');
    await loadBuses();
  } catch (err) {
    alert('❌ Gagal menyimpan perubahan: ' + err.message);
    console.error(err);
  }
});

// ============= ROUTE Operations =============
async function deleteRoute(id) {
  if (!confirm('Hapus rute ini? Aksi tidak bisa dibatalkan.')) return;
  try {
    const res = await fetch(`${API}/rute/${id}`, { method: 'DELETE' });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    await loadRoutes();
  } catch (err) {
    alert('❌ Gagal menghapus rute: ' + err.message);
    console.error(err);
  }
}

function showEditRoute(id) {
  editRouteId = id;
  fetch(`${API}/rute/${id}`)
    .then(r => r.json())
    .then(route => {
      document.getElementById('editroutename').value = safe(route.nama_rute);
      document.getElementById('editroutestart').value = safe(route.titik_awal);
      document.getElementById('editrouteend').value = safe(route.titik_akhir);
      document.getElementById('modalEditRoute').classList.add('active');
    })
    .catch(err => {
      alert('❌ Gagal memuat data rute: ' + err.message);
      console.error(err);
    });
}

document.getElementById('formAddRoute').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  try {
    const res = await fetch(`${API}/rute`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    e.target.reset();
    document.getElementById('modalAddRoute').classList.remove('active');
    setTimeout(() => loadRoutes(), 200);
  } catch (err) {
    alert('❌ Gagal menambah rute: ' + err.message);
    console.error(err);
  }
});

document.getElementById('formEditRoute').addEventListener('submit', async e => {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(e.target).entries());
  try {
    const res = await fetch(`${API}/rute/${editRouteId}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    document.getElementById('modalEditRoute').classList.remove('active');
    await loadRoutes();
  } catch (err) {
    alert('❌ Gagal menyimpan perubahan: ' + err.message);
    console.error(err);
  }
});

// ============= Modal Controls =============
// Add Bus Modal
document.getElementById('btnShowAddBus').onclick = () => document.getElementById('modalAddBus').classList.add('active');
document.getElementById('btnShowAddBusDash').onclick = () => document.getElementById('modalAddBus').classList.add('active');
document.getElementById('btnCancelBus').onclick = () => document.getElementById('modalAddBus').classList.remove('active');
document.getElementById('btnCancelEditBus').onclick = () => document.getElementById('modalEditBus').classList.remove('active');

// Add Route Modal
document.getElementById('btnShowAddRoute').onclick = () => document.getElementById('modalAddRoute').classList.add('active');
document.getElementById('btnShowAddRouteDash').onclick = () => document.getElementById('modalAddRoute').classList.add('active');
document.getElementById('btnCancelRoute').onclick = () => document.getElementById('modalAddRoute').classList.remove('active');
document.getElementById('btnCancelEditRoute').onclick = () => document.getElementById('modalEditRoute').classList.remove('active');

// Close modals on background click
document.querySelectorAll('.modal').forEach(modal => {
  modal.addEventListener('click', e => {
    if (e.target === modal) modal.classList.remove('active');
  });
});

// ============= Initialize =============
window.addEventListener('load', () => {
  loadBuses();
  loadRoutes();
  // Auto-refresh every 30 seconds
  setInterval(() => {
    loadBuses();
    loadRoutes();
  }, 30000);
});
</script>
</body>
</html>
