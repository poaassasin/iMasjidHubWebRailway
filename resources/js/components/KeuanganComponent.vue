<template>
    <div class="light-spots"></div>
    <div class="dashboard-wrapper">
      <div class="dashboard" id="dashboard-container">
        <header class="header">
          <div class="logo-container" @click="refreshPage">
            <img src="../assets/icon/mosque-logo.png" alt="Mosque Logo" class="mosque-logo" />
            <div class="logo">iMasjidHub</div>
          </div>
          <nav class="nav">
            <a href="/dashboard" class="nav-link" :class="{ 'active': currentPath === '/dashboard' }">Dashboard</a>
            <a href="/kegiatan" class="nav-link" :class="{ 'active': currentPath === '/kegiatan' }">Kegiatan</a>
            <a href="/inventaris" class="nav-link" :class="{ 'active': currentPath === '/inventaris' }">Inventaris</a>
            <a href="/keuangan" class="nav-link" :class="{ 'active': currentPath === '/keuangan' }">Keuangan</a>
          </nav>
  
          <div class="search-notif-profile">
            <input type="text" placeholder="Search..." />
            <button class="notif">🔔</button>
            <img class="avatar" src="../assets/icon/profile.png" alt="User" />
          </div>
        </header>
  
        <div class="keuangan-content">
          <!-- Card Statistics -->
          <section class="stats-section">
            <div class="stats-row">
              <div class="stat-card saldo">
                <div class="stat-icon">💰</div>
                <div class="stat-content">
                  <div class="stat-label">Saldo Kas</div>
                  <div class="stat-value">Rp. 16.000.000</div>
                </div>
              </div>
  
              <div class="stat-card pemasukan">
                <div class="stat-icon">📈</div>
                <div class="stat-content">
                  <div class="stat-label">Pemasukan</div>
                  <div class="stat-value">Rp. 4.000.000</div>
                </div>
              </div>
  
              <div class="stat-card pengeluaran">
                <div class="stat-icon">📉</div>
                <div class="stat-content">
                  <div class="stat-label">Pengeluaran</div>
                  <div class="stat-value">Rp. 2.000.000</div>
                </div>
              </div>
  
              <div class="stat-card selisih">
                <div class="stat-icon">💳</div>
                <div class="stat-content">
                  <div class="stat-label">Selisih</div>
                  <div class="stat-value">Rp. 2.000.000</div>
                </div>
              </div>
            </div>
          </section>
  
          <!-- Tables Section -->
          <section class="tables-section">
            <div class="tables-row">
              <!-- Daftar Transaksi -->
              <div class="table-card">
                <div class="card-header">
                  <h3>Daftar Transaksi</h3>
                  <div class="card-actions">
                    <button class="add-btn-header" @click="addTransaksi">
                      <span class="add-icon">⊕</span>
                      Tambah Transaksi
                    </button>
                  </div>
                </div>
                <div class="table-container">
                  <table>
                    <thead>
                      <tr>
                        <th>Tanggal <span class="sort-icon">▼</span></th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(transaksi, index) in daftarTransaksi" :key="index">
                        <td class="center tanggal">{{ transaksi.tanggal }}</td>
                        <td class="center jenis-transaksi">
                          <span class="jenis-badge" :class="transaksi.jenis.toLowerCase()">
                            {{ transaksi.jenis }}
                          </span>
                        </td>
                        <td class="center nominal">{{ transaksi.nominal }}</td>
                        <td class="center kategori">{{ transaksi.kategori }}</td>
                        <td class="center deskripsi">{{ transaksi.keterangan }}</td>
                        <td class="center actions">
                          <div class="action-buttons">
                            <button class="edit-btn" @click="editTransaksi(index)" title="Edit Transaksi">✏️</button>
                            <button class="delete-btn" @click="deleteTransaksi(index)" title="Hapus Transaksi">🗑️</button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
  
              <!-- Rencana Anggaran -->
              <div class="table-card anggaran-card">
                <div class="card-header">
                  <h3>Rencana Anggaran</h3>
                  <div class="card-actions">
                    <button class="add-btn-header" @click="addAnggaran">
                      <span class="add-icon">⊕</span>
                      Tambah Anggaran
                    </button>
                  </div>
                </div>
                <div class="anggaran-container">
                  <div class="anggaran-item" v-for="(anggaran, index) in rencanaAnggaran" :key="index">
                    <div class="anggaran-content">
                      <div class="anggaran-info">
                        <div class="anggaran-title">{{ anggaran.nama }}</div>
                        <div class="anggaran-amount">{{ anggaran.targetNominal }}</div>
                      </div>
                      <div class="anggaran-meta">
                        <div class="anggaran-target">Target: {{ anggaran.targetTanggal }}</div>
                        <div class="anggaran-progress">Terkumpul: {{ anggaran.nominalTerkumpul }}</div>
                      </div>
                    </div>
                    <div class="anggaran-actions">
                      <button class="edit-btn" @click="editAnggaran(index)" title="Edit Anggaran">✏️</button>
                      <button class="delete-btn" @click="deleteAnggaran(index)" title="Hapus Anggaran">🗑️</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import authMixin from '../mixins/auth';

  export default {
    mixins: [authMixin],
    props: {
        currentPath: {
          type: String,
          required: true
        }
    },
    data() {
      return {
        daftarTransaksi: [],
        rencanaAnggaran: []
      };
    },
    created() {
      // Load rencana anggaran from localStorage
      const savedRencanaAnggaran = localStorage.getItem('rencanaAnggaranList');
      if (savedRencanaAnggaran) {
        this.rencanaAnggaran = JSON.parse(savedRencanaAnggaran);
      }
  
      // Load daftar transaksi from localStorage
      const savedTransaksi = localStorage.getItem('transaksiList');
      if (savedTransaksi) {
        this.daftarTransaksi = JSON.parse(savedTransaksi);
      }
    },
    methods: {
      refreshPage() {
        window.location.reload();
      },
      addTransaksi() {
        window.location.href = "/keuangan/transaksi/tambah";
      },
      editTransaksi(index) {
        window.location.href = `/keuangan/transaksi/edit/${index}`;
      },
      addAnggaran() {
        window.location.href = "/keuangan/rencana-anggaran/tambah";
      },
      editAnggaran(index) {
        window.location.href = `/keuangan/rencana-anggaran/edit/${index}`;
      },
      deleteAnggaran(index) {
        if (confirm('Apakah Anda yakin ingin menghapus rencana anggaran ini?')) {
          const existingRencana = JSON.parse(localStorage.getItem('rencanaAnggaranList') || '[]');
          existingRencana.splice(index, 1);
          localStorage.setItem('rencanaAnggaranList', JSON.stringify(existingRencana));
          this.rencanaAnggaran = existingRencana;
        }
      },
      deleteTransaksi(index) {
        if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
          const existingTransaksi = JSON.parse(localStorage.getItem('transaksiList') || '[]');
          existingTransaksi.splice(index, 1);
          localStorage.setItem('transaksiList', JSON.stringify(existingTransaksi));
          this.daftarTransaksi = existingTransaksi;
        }
      }
    }
  };
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
  
  /* Global Reset */
  *, *::before, *::after {
    margin: 0 !important;
    padding: 0 !important;
    box-sizing: border-box !important;
    font-family: 'Poppins', sans-serif;
  }
  
  .router-view {
    margin: 0 !important;
    padding: 0 !important;
    background-color: #121212 !important;
    height: 100vh !important;
    overflow: hidden !important;
  }
  
  .dashboard-wrapper {
    background-color: #121212;
    height: 100vh !important;
    width: 100vw !important;
    margin: 0 !important;
    padding: 0 !important;
    position: fixed;
    top: 0;
    left: 0;
    overflow: hidden !important;
  }
  
  .dashboard {
    color: #fff;
    padding: 12px !important;
    display: grid;
    grid-template-rows: auto 1fr;
    gap: 16px;
    height: 100vh !important;
    max-width: 1400px;
    margin: 0 auto !important;
    width: 100%;
    box-sizing: border-box;
    position: relative;
    overflow: hidden !important;
  }
  
  /* Header - Same as Inventaris */
  .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #333;
    padding-bottom: 8px !important;
    height: 50px;
    flex-shrink: 0;
  }
  
  .logo-container {
    display: flex;
    align-items: center;
    background: #1c1816;
    border-radius: 8px;
    padding: 6px 12px !important;
    cursor: pointer;
  }
  
  .mosque-logo {
    width: 20px;
    height: 20px;
    margin-right: 8px !important;
  }
  
  .logo {
    font-weight: 600;
    font-size: 0.9rem;
    color: #fff;
  }
  
  .nav {
    display: flex;
    gap: 30px;
    font-weight: 500;
    font-size: 0.85rem;
  }
  
  .nav-link {
    color: #ccc;
    text-decoration: none;
    padding: 6px 0 !important;
    border-bottom: 2px solid transparent;
    transition: all 0.3s ease;
  }
  
  .nav-link.active, .nav-link:hover {
    color: #fff;
    border-color: #fff;
  }
  
  .search-notif-profile {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .search-notif-profile input {
    background: #1c1816;
    border: 1px solid #333;
    border-radius: 20px;
    padding: 6px 12px !important;
    width: 250px;
    color: white;
    font-size: 0.8rem;
  }
  
  .search-notif-profile input::placeholder {
    color: #888;
  }
  
  .notif {
    background: transparent;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    color: #ccc;
    transition: color 0.3s ease;
    padding: 3px !important;
  }
  
  .notif:hover {
    color: #fff;
  }
  
  .avatar {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    cursor: pointer;
    border: 2px solid #333;
  }
  
  /* Keuangan Content */
  .keuangan-content {
    display: grid;
    grid-template-rows: auto 1fr;
    gap: 20px;
    height: 100%;
    overflow: hidden;
  }
  
  /* Stats Section */
  .stats-section {
    flex-shrink: 0;
  }
  
  .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }
  
  .stat-card {
    background: #1c1816;
    border: 1px solid #333;
    border-radius: 20px;
    padding: 20px !important;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: transform 0.2s ease;
  }
  
  .stat-card:hover {
    transform: translateY(-2px);
  }
  
  .stat-icon {
    font-size: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.1);
  }
  
  .stat-content {
    flex: 1;
  }
  
  .stat-label {
    font-size: 0.85rem;
    color: #ccc;
    margin-bottom: 4px !important;
  }
  
  .stat-value {
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
  }
  
  .saldo .stat-icon { background: rgba(76, 175, 80, 0.2); }
  .pemasukan .stat-icon { background: rgba(33, 150, 243, 0.2); }
  .pengeluaran .stat-icon { background: rgba(255, 152, 0, 0.2); }
  .selisih .stat-icon { background: rgba(156, 39, 176, 0.2); }
  
  /* Tables Section */
  .tables-section {
    overflow: hidden;
    flex: 1;
  }
  
  .tables-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    height: 100%;
  }
  
  .table-card {
    background: #1c1816;
    border: 1px solid #333;
    border-radius: 20px;
    padding: 20px !important;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  
  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px !important;
    flex-shrink: 0;
  }
  
  .card-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
  }
  
  .card-actions {
    display: flex;
    gap: 8px;
  }
  
  /* Updated Add Button Style - Same as Inventaris Page */
  .add-btn-header {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #0a6446, #0d7a52);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    padding: 10px 16px !important;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(10, 100, 70, 0.3);
  }
  
  .add-btn-header:hover {
    background: linear-gradient(135deg, #0d7a52, #0f8b5f);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(10, 100, 70, 0.4);
  }
  
  .add-icon {
    font-size: 1.1rem;
    font-weight: bold;
  }
  
  /* Tables - Following Inventaris Page Style */
  .table-container {
    overflow: auto;
    flex: 1;
    border-radius: 12px;
  }
  
  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.85rem;
  }
  
  th, td {
    padding: 12px 8px !important;
    text-align: center;
    border-bottom: 1px solid #333;
    vertical-align: middle;
  }
  
  th {
    background: #2a2320;
    font-weight: 600;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 10;
  }
  
  .center {
    text-align: center !important;
  }
  
  /* Table Cell Content Styling - Same as Inventaris */
  .tanggal {
    font-weight: 500;
    color: #fff;
    min-width: 100px;
  }
  
  .jenis-transaksi {
    min-width: 100px;
  }
  
  .jenis-badge {
    padding: 4px 8px !important;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: capitalize;
  }
  
  .jenis-badge.pemasukan {
    background-color: #10B981;
    color: white;
  }
  
  .jenis-badge.pengeluaran {
    background-color: #EF4444;
    color: white;
  }
  
  .nominal {
    font-weight: 600;
    color: #fff;
    min-width: 120px;
  }
  
  .kategori {
    font-weight: 500;
    color: #fff;
    min-width: 100px;
  }
  
  .deskripsi {
    max-width: 150px;
    font-size: 0.8rem;
    line-height: 1.3;
    color: #fff;
    font-weight: 400;
  }
  
  .actions {
    text-align: center;
    min-width: 80px;
  }
  
  .action-buttons {
    display: flex;
    justify-content: center;
    gap: 6px;
  }
  
  .edit-btn, .delete-btn {
    background: transparent;
    border: 1px solid #555;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    color: #9ca3af;
    padding: 6px 8px !important;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
  }
  
  .edit-btn:hover {
    background: #1e5aa3;
    border-color: #1e5aa3;
    transform: translateY(-1px);
    color: #fff;
  }
  
  .delete-btn:hover {
    background: #a31e1e;
    border-color: #a31e1e;
    transform: translateY(-1px);
    color: #fff;
  }
  
  .sort-icon {
    font-size: 0.7rem;
    margin-left: 5px !important;
    color: #ccc;
  }
  
  /* Anggaran Card - Improved Layout */
  .anggaran-card {
    background: #1c1816;
  }
  
  .anggaran-container {
    flex: 1;
    overflow: auto;
    padding: 8px 0 !important;
  }
  
  .anggaran-item {
    background: #2d2d2d;
    border-radius: 12px;
    padding: 20px !important;
    margin-bottom: 16px !important;
    border: 1px solid #333;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    transition: all 0.2s ease;
  }
  
  .anggaran-content {
    flex: 1;
    margin-right: 16px !important;
  }
  
  .anggaran-info {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px !important;
  }
  
  .anggaran-title {
    font-weight: 600;
    color: #fff;
    font-size: 1rem;
    margin-right: 16px !important;
  }
  
  .anggaran-amount {
    font-weight: 600;
    color: #fff;
    font-size: 0.9rem;
  }
  
  .anggaran-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    gap: 16px;
  }
  
  .anggaran-target {
    color: #ccc;
    margin-right: 16px !important;
  }
  
  .anggaran-progress {
    color: #10B981;
    font-weight: 500;
  }
  
  .anggaran-actions {
    display: flex;
    gap: 8px;
    margin-top: 8px !important;
  }
  
  .edit-btn, .delete-btn {
    background: transparent;
    border: 1px solid #555;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    color: #9ca3af;
    padding: 6px 8px !important;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
  }
  
  .edit-btn:hover {
    background: #1e5aa3;
    border-color: #1e5aa3;
    transform: translateY(-1px);
    color: #fff;
  }
  
  .delete-btn:hover {
    background: #a31e1e;
    border-color: #a31e1e;
    transform: translateY(-1px);
    color: #fff;
  }
  
  .sort-icon {
    font-size: 0.7rem;
    margin-left: 5px !important;
    color: #ccc;
  }
  
  /* Responsive Design */
  @media (max-width: 1200px) {
    .stats-row {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .tables-row {
      grid-template-columns: 1fr;
    }
    
    .search-notif-profile input {
      width: 200px;
    }
  }
  
  @media (max-width: 768px) {
    .dashboard {
      padding: 8px !important;
      gap: 12px;
    }
    
    .header {
      flex-direction: column;
      gap: 10px;
      height: auto;
    }
    
    .nav {
      justify-content: space-around;
      font-size: 0.8rem;
      gap: 15px;
    }
    
    .search-notif-profile {
      justify-content: space-between;
      width: 100%;
    }
    
    .search-notif-profile input {
      width: 150px;
    }
    
    .stats-row {
      grid-template-columns: 1fr;
    }
    
    .tables-row {
      grid-template-columns: 1fr;
    }
    
    .action-buttons {
      flex-direction: column;
      gap: 4px;
    }
    
    .anggaran-item {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
    
    .anggaran-actions {
      align-self: flex-end;
    }
    
    .add-btn-header {
      justify-content: center;
      font-size: 0.8rem;
      padding: 8px 12px !important;
    }
  }

  .light-spots {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.light-spots::before,
.light-spots::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle at center, rgba(255, 248, 240, 0.25) 0%, rgba(210, 180, 140, 0.2) 30%, transparent 70%);
  animation: float 8s infinite ease-in-out;
}

.light-spots::before {
  width: 280px;
  height: 280px;
  top: 15%;
  left: 25%;
  filter: blur(30px);
  animation-delay: -2s;
}

.light-spots::after {
  width: 220px;
  height: 220px;
  top: 65%;
  right: 20%;
  filter: blur(25px);
  animation-delay: -4s;
}

.light-spots::before {
  box-shadow:
    500px -200px 250px rgba(210, 180, 140, 0.15),
    -400px 300px 200px rgba(255, 248, 240, 0.18),
    250px 400px 180px rgba(210, 180, 140, 0.12),
    -300px -250px 220px rgba(255, 248, 240, 0.15),
    150px -450px 180px rgba(210, 180, 140, 0.1),
    -200px 500px 160px rgba(255, 248, 240, 0.13),
    400px 100px 200px rgba(210, 180, 140, 0.14),
    -450px -100px 230px rgba(255, 248, 240, 0.16);
}

.light-spots::after {
  box-shadow:
    -300px -400px 240px rgba(210, 180, 140, 0.12),
    350px 200px 180px rgba(255, 248, 240, 0.15),
    -150px 350px 220px rgba(210, 180, 140, 0.13),
    250px -300px 190px rgba(255, 248, 240, 0.14);
}

@keyframes float {
  0%, 100% {
    transform: translateY(0) scale(1);
  }
  25% {
    transform: translateY(-15px) scale(1.02) translateX(5px);
  }
  50% {
    transform: translateY(-25px) scale(1.05);
  }
  75% {
    transform: translateY(-10px) scale(1.02) translateX(-5px);
  }
}
  </style>