
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
            <img class="avatar" src="../assets/icon/profile.png"/>
          </div>
        </header>
  
        <div class="inventaris-content">
          <!-- Top Section: Rekam Inventaris and Data Penyumbang -->
          <section class="top-section">
            <!-- Rekam Inventaris -->
            <div class="inventory-card">
              <div class="card-header">
                <h3>Rekam Inventaris</h3>
                <div class="card-actions">
                  <button class="add-btn-header" @click="addInventory">
                    <span class="add-icon">⊕</span>
                    Tambah Inventaris
                  </button>
                </div>
              </div>
              <div class="table-container">
                <table>
                  <thead>
                    <tr>
                      <th>Foto</th>
                      <th>Kategori <span class="sort-icon">▼</span></th>
                      <th>Nama Barang</th>
                      <th>Jumlah</th>
                      <th>Lokasi</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in inventoryList" :key="index">
                      <td class="center">
                        <img :src="getImagePath(item.photoPath)" :alt="item.namaBarang" class="item-photo" />
                      </td>
                      <td class="center kategori">{{ item.kategori }}</td>
                      <td class="center nama-barang">{{ item.namaBarang }}</td>
                      <td class="center jumlah">{{ item.jumlahBarang }}</td>
                      <td class="center lokasi">{{ item.lokasiBarang }}</td>
                      <td class="center actions">
                        <div class="action-buttons">
                          <button class="edit-btn" @click="editInventory(index)" title="Edit Inventaris">✏️</button>
                          <button class="delete-btn" @click="deleteInventory(index)" title="Hapus Inventaris">🗑️</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
  
            <!-- Data Penyumbang Inventaris -->
            <div class="inventory-card">
              <div class="card-header">
                <h3>Data Penyumbang Inventaris</h3>
                <div class="card-actions">
                  <button class="add-btn-header" @click="addDonor">
                    <span class="add-icon">⊕</span>
                    Tambah Penyumbang
                  </button>
                </div>
              </div>
              <div class="table-container">
                <table>
                  <thead>
                    <tr>
                      <th>Penyumbang</th>
                      <th>No.Telepon</th>
                      <th>Nama Barang</th>
                      <th>Jumlah</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(donor, index) in donorList" :key="index">
                      <td class="center penyumbang">{{ donor.penyumbang }}</td>
                      <td class="center no-telepon">{{ donor.noTelefon }}</td>
                      <td class="center nama-barang">{{ donor.namaBarang }}</td>
                      <td class="center jumlah">{{ donor.jumlahBarang }}</td>
                      <td class="center actions">
                        <div class="action-buttons">
                          <button class="edit-btn" @click="editDonor(index)" title="Edit Penyumbang">✏️</button>
                          <button class="delete-btn" @click="deleteDonor(index)" title="Hapus Penyumbang">🗑️</button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
  
          <!-- Bottom Section: Permintaan and Keluar Masuk -->
          <section class="bottom-section">
            <!-- Permintaan Inventaris -->
            <div class="inventory-card wide-card">
              <div class="card-header">
                <h3>Permintaan Inventaris</h3>
              </div>
              <div class="table-container">
                <table>
                  <thead>
                    <tr>
                      <th>Kategori <span class="sort-icon">▼</span></th>
                      <th>Nama Barang</th>
                      <th>Jumlah</th>
                      <th>Alasan Kebutuhan</th>
                      <th>Waktu</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(request, index) in requestList" :key="index">
                      <td class="center kategori">{{ request.kategori }}</td>
                      <td class="center nama-barang">{{ request.namaBarang }}</td>
                      <td class="center jumlah">{{ request.jumlahBarang }}</td>
                      <td class="center alasan">{{ request.alasanKebutuhan }}</td>
                      <td class="center waktu">{{ request.waktu }}</td>
                      <td class="center">
                        <div class="status-badge" :class="request.statusClass" @click="toggleStatus(index)">
                          {{ request.status }} <span class="dropdown-icon">▼</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
  
            <!-- Keluar Masuk Inventaris -->
            <div class="inventory-card">
              <div class="card-header">
                <h3>Keluar Masuk Inventaris</h3>
                <div class="card-actions">
                  <button class="add-btn-header add-btn-small" @click="addMovement">
                    <span class="add-icon">⊕</span>
                    Tambah Keluar Masuk
                  </button>
                </div>
              </div>
              <div class="movement-container">
               
  <div 
    v-for="(movement, index) in movementList" 
    :key="index" 
    class="movement-item"
  >
    <div class="movement-info">
      <span class="item-name">{{ movement.itemName }}</span>
      <span class="item-quantity" v-if="movement.quantity">• {{ movement.quantity }}</span>
      <span class="item-date">• {{ movement.date }}</span>
    </div>
    <span class="status-movement" :class="{'status-masuk': movement.status === 'masuk', 'status-keluar': movement.status === 'keluar'}">
      {{ movement.status }}
    </span>
    <div class="movement-actions">
      <button class="action-icon" @click="editMovement(index)">✏️</button>
      <button class="action-icon" @click="deleteMovement(index)">🗑️</button>
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
  // Import images properly
  import micIcon from '../assets/icon/mic.png'
  import soundIcon from '../assets/icon/sound.png'
  import profileIcon from '../assets/icon/profile.png'
  
  export default {
    props: {
        currentPath: {
          type: String,
          required: true
        }
    },

    data() {
      return {
        // Store imported images
        images: {
          mic: micIcon,
          sound: soundIcon,
          profile: profileIcon
        },
        inventoryList: [],
        donorList: [],
        movementList: [],
        requestList: [
          {
            kategori: "Alat Sholat",
            namaBarang: "Sajadah",
            jumlahBarang: "5 Buah",
            alasanKebutuhan: "Kekurangan Sajadah",
            waktu: "19.10",
            status: "Diterima",
            statusClass: "diterima"
          }
        ]
      }
    },
    mounted() {
      this.loadInventoryData()
      this.loadDonorData()
      this.loadMovementData()
    },
    methods: {
      loadMovementData() {
        const savedMovements = JSON.parse(localStorage.getItem('movementList') || '[]')
        if (Array.isArray(savedMovements)) {
          this.movementList = savedMovements
        } else {
          this.movementList = []
          localStorage.setItem('movementList', '[]')
        }
      },
      // Method to get image path
      getImagePath(imageName) {
        return this.images[imageName] || imageName || '';
      },
      loadInventoryData() {
        const savedInventory = JSON.parse(localStorage.getItem('inventarisList') || '[]');
        this.inventoryList = savedInventory.map(item => ({
          kategori: item.kategori,
          namaBarang: item.nama,
          jumlahBarang: `${item.jumlah} Buah`,
          lokasiBarang: item.lokasi,
          photoPath: item.foto
        }));
      },
      loadDonorData() {
        const savedDonors = JSON.parse(localStorage.getItem('donorList') || '[]');
        if (Array.isArray(savedDonors)) {
          this.donorList = savedDonors;
        } else {
          this.donorList = [];
          localStorage.setItem('donorList', '[]');
        }
      },
      refreshPage() {
        window.location.reload();
      },
      addInventory() {
        window.location.href = "/inventaris/tambah";
      },
      editInventory(index) {
        window.location.href = `/inventaris/edit/${index}`
        console.log('Navigating to edit page for inventory:', this.inventoryList[index]);
      },
      addDonor() {
        window.location.href = "/inventaris/penyumbang/tambah";
      },
      editDonor(index) {
        const donorList = JSON.parse(localStorage.getItem('donorList') || '[]');
        if (donorList[index]) {
          window.location.href = `/inventaris/penyumbang/edit/${index}`;
          console.log('Navigating to edit page for donor:', donorList[index]);
        } else {
          alert('Data penyumbang tidak ditemukan');
        }
      },
      addMovement() {
        window.location.href = "/inventaris/keluar-masuk/tambah";
      },
      editMovement(index) {
        window.location.href = `/inventaris/keluar-masuk/edit/${index}`;
      },
      toggleStatus(index) {
        console.log('Toggle status for request', index);
        // Implement status toggle functionality
      },
      deleteInventory(index) {
        if (confirm('Apakah Anda yakin ingin menghapus data inventaris ini?')) {
          const savedInventory = JSON.parse(localStorage.getItem('inventarisList') || '[]');
          if (savedInventory[index]) {
            savedInventory.splice(index, 1);
            localStorage.setItem('inventarisList', JSON.stringify(savedInventory));
            this.loadInventoryData();
            alert('Data inventaris berhasil dihapus!');
          } else {
            alert('Data inventaris tidak ditemukan');
          }
        }
      },
      deleteDonor(index) {
        if (confirm('Apakah Anda yakin ingin menghapus data penyumbang ini?')) {
          const donorList = JSON.parse(localStorage.getItem('donorList') || '[]');
          if (donorList[index]) {
            donorList.splice(index, 1);
            localStorage.setItem('donorList', JSON.stringify(donorList));
            this.loadDonorData();
            alert('Data penyumbang berhasil dihapus!');
          } else {
            alert('Data penyumbang tidak ditemukan');
          }
        }
      },
      deleteMovement(index) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
          this.movementList.splice(index, 1);
          localStorage.setItem('movementList', JSON.stringify(this.movementList));
        }
      },
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
    font-family: 'Poppins', sans-serif !important;
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
  
  /* Header - Same as Kegiatan Page */
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
  
  /* Inventaris Content */
  .inventaris-content {
    display: grid;
    grid-template-rows: 1fr 1fr;
    gap: 16px;
    height: 100%;
    overflow: hidden;
  }
  
  /* Top and Bottom Sections */
  .top-section, .bottom-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    overflow: hidden;
  }
  
  .bottom-section {
    grid-template-columns: 2fr 1fr;
  }
  
  /* Inventory Cards */
  .inventory-card {
    background: #1c1816;
    border-radius: 20px;
    border: 1px solid #333;
    padding: 20px !important;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  
  .wide-card {
    grid-column: 1;
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
  
  /* Updated Add Button Style - Same as Kegiatan Page */
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
  
  /* Small Button Modifier for "Tambah Keluar Masuk" */
  .add-btn-small {
    font-size: 0.75rem !important;
    padding: 6px 10px !important;
    gap: 6px !important;
  }
  
  .add-btn-small .add-icon {
    font-size: 0.9rem !important;
  }
  
  .add-icon {
    font-size: 1.1rem;
    font-weight: bold;
  }
  
  /* Tables - Following Kegiatan Page Style */
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
  
  /* Item Photo Styling */
  .item-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: 0 auto !important;
    border: 2px solid #333;
  }
  
  /* Table Cell Content Styling */
  .kategori {
    font-weight: 500;
    color: #fff;
    min-width: 100px;
  }
  
  .nama-barang {
    font-weight: 600;
    color: #fff;
    min-width: 120px;
  }
  
  .jumlah {
    font-weight: 500;
    color: #fff;
    min-width: 80px;
  }
  
  .lokasi {
    font-weight: 400;
    color: #fff;
    min-width: 100px;
  }
  
  .penyumbang {
    font-weight: 600;
    color: #fff;
    min-width: 120px;
  }
  
  .no-telepon {
    font-weight: 400;
    color: #fff;
    min-width: 100px;
  }
  
  .alasan {
    max-width: 150px;
    font-size: 0.8rem;
    line-height: 1.3;
    color: #fff;
    font-weight: 400;
  }
  
  .waktu {
    font-weight: 500;
    color: #fff;
    min-width: 60px;
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
    color: #fff;
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
  }
  
  .delete-btn:hover {
    background: #a31e1e;
    border-color: #a31e1e;
    transform: translateY(-1px);
  }
  
  .sort-icon {
    font-size: 0.7rem;
    margin-left: 5px !important;
    color: #ccc;
  }
  
  /* Status Badges */
  .status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px !important;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  
  .diterima {
    background-color: #2e2a6f;
    color: #fff;
  }
  
  .proses {
    background-color: #a87f1a;
    color: #fff;
  }
  
  .dropdown-icon {
    font-size: 0.7rem;
    margin-left: 5px !important;
  }
  
  .status-badge:hover {
    opacity: 0.8;
    transform: translateY(-1px);
  }
  
  /* Movement Container - Improved Layout */
  .movement-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    flex: 1;
    overflow-y: auto;
    padding: 8px 0 !important;
  }
  
  .movement-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px !important;
    background: #2d2d2d;
    border-radius: 8px;
    transition: all 0.2s ease;
  }
  
  .movement-item:hover {
    background: #363636;
    transform: translateY(-1px);
  }
  
  .movement-info {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 1;
  }
  
  .item-name {
    font-size: 16px;
    font-weight: 500;
    color: #ffffff;
  }
  
  .item-date {
    font-size: 14px;
    color: #9ca3af;
  }
  
  .status-movement {
    padding: 6px 12px !important;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-transform: capitalize;
    margin-right: 12px !important;
  }
  
  .status-masuk {
    background-color: #10B981;
    color: white;
  }
  
  .status-keluar {
    background-color: #EF4444;
    color: white;
  }
  
  .movement-actions {
    display: flex;
    gap: 8px;
  }
  
  .action-icon {
    background: none;
    border: 1px solid #555;
    border-radius: 4px;
    color: #9ca3af;
    cursor: pointer;
    padding: 4px 6px !important;
    transition: all 0.2s ease;
    font-size: 14px;
  }
  
  .action-icon:first-child:hover {
    color: #3b82f6;
    border-color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
  }
  
  .action-icon:last-child:hover {
    color: #ef4444;
    border-color: #ef4444;
    background: rgba(239, 68, 68, 0.1);
  }
  
  /* Responsive Design */
  @media (max-width: 1200px) {
    .top-section, .bottom-section {
      grid-template-columns: 1fr;
    }
    
    .bottom-section {
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
    .item-quantity {
    font-size: 14px;
    color: #9ca3af;
    font-weight: 500;
    }
    
    .search-notif-profile {
      justify-content: space-between;
      width: 100%;
    }
    
    .search-notif-profile input {
      width: 150px;
    }
    
    .inventaris-content {
      grid-template-rows: auto auto;
      gap: 12px;
    }
    
    .action-buttons {
      flex-direction: column;
      gap: 4px;
    }
    
    .movement-item {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
    
    .movement-actions {
      align-self: flex-end;
    }
    
    .add-btn-header {
      justify-content: center;
      font-size: 0.8rem;
      padding: 8px 12px !important;
    }
  
    /* Responsive styling for small button */
    .add-btn-small {
      font-size: 0.7rem !important;
      padding: 5px 8px !important;
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