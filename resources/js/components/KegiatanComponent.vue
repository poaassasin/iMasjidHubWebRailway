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
  
        <div class="kegiatan-content">
          <!-- Tabel Kegiatan -->
          <section class="kegiatan-table">
            <div class="card-header">
              <h3>Daftar Kegiatan Masjid</h3>
              <div class="card-actions">
                <button class="add-btn-header" @click="addKegiatan">
                  <span class="add-icon">⊕</span>
                  Tambah Kegiatan
                </button>
              </div>
            </div>
            <div class="table-container">
              <table>
                <thead>
                  <tr>
                    <th>Foto</th>
                    <th>Judul <span class="sort-icon">▼</span></th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Lokasi</th>
                    <th>Tema</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(kegiatan, index) in kegiatanList" :key="index">
                    <td class="center">
                      <img class="activity-photo" :src="kegiatan.foto.startsWith('data:') ? kegiatan.foto : require('../assets/icon/kegiatan.png')" alt="Foto Kegiatan" />
                    </td>
                    <td class="center judul">{{ kegiatan.judul }}</td>
                    <td class="center tanggal">{{ kegiatan.tanggal }}</td>
                    <td class="center waktu">{{ kegiatan.waktu }}</td>
                    <td class="center lokasi">{{ kegiatan.lokasi }}</td>
                    <td class="center tema">{{ kegiatan.tema }}</td>
                    <td class="center deskripsi">{{ kegiatan.deskripsi }}</td>
                    <td class="center actions">
                      <div class="action-buttons">
                        <button class="edit-btn" @click="editKegiatan(index)" title="Edit Kegiatan">✏️</button>
                        <button class="delete-btn" @click="deleteKegiatan(index)" title="Hapus Kegiatan">🗑️</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </section>
  
          <!-- Bottom Section -->
          <section class="bottom-section">
            <!-- Jadwal Sholat -->
            <div class="jadwal-box">
              <div class="section-header">
                <h3>Jadwal Sholat dan Imam</h3>
              </div>
              <ul class="jadwal-list">
                <li v-for="(j, i) in jadwalSholat" :key="i">
                  <span class="prayer-icon">{{ j.icon }}</span>
                  <span class="prayer-name">{{ j.nama }}</span>
                  <span class="prayer-time">{{ j.jam }}</span>
                  <span class="prayer-imam">{{ j.imam }}</span>
                  <span class="prayer-actions">
                    <button class="edit-btn" @click="goToEditJadwal(i)">✏️</button>
                  </span>
                </li>
              </ul>
            </div>
  
            <!-- Kalender -->
            <div class="calendar-box">
              <div class="calendar-header">
                <button @click="previousMonth" class="nav-btn">‹</button>
                <div class="month-badge">{{ currentMonthName }} {{ currentYear }}</div>
                <button @click="nextMonth" class="nav-btn">›</button>
              </div>
              <div class="calendar-container">
                <div class="calendar-grid">
                  <div class="weekdays">
                    <div class="weekday">Sen</div>
                    <div class="weekday">Sel</div>
                    <div class="weekday">Rab</div>
                    <div class="weekday">Kam</div>
                    <div class="weekday">Jum</div>
                    <div class="weekday weekend">Sab</div>
                    <div class="weekday weekend">Min</div>
                  </div>
                  <div class="calendar-days">
                    <div 
                      v-for="(day, index) in calendarDays" 
                      :key="`${currentMonth}-${currentYear}-${index}`"
                      :class="['calendar-day', {
                        'disabled': day.isDisabled,
                        'weekend': day.isWeekend,
                        'selected': day.isSelected,
                        'today': day.isToday,
                        'other-month': day.isOtherMonth
                      }]"
                      @click="selectDate(day)"
                    >
                      {{ day.date }}
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
        jadwalSholat: [],
        currentDate: new Date(),
        selectedDate: null,
        kegiatanList: [],
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        monthNames: [
          'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ]
      };
    },
    created() {
      // Load kegiatan from localStorage
      const savedKegiatan = localStorage.getItem('kegiatanList')
      if (savedKegiatan) {
        this.kegiatanList = JSON.parse(savedKegiatan)
      }
      
      // Load jadwal sholat from localStorage if exists
      const savedJadwal = localStorage.getItem('jadwalSholat')
      if (savedJadwal) {
        this.jadwalSholat = JSON.parse(savedJadwal)
      }
    },
    computed: {
      currentMonthName() {
        return this.monthNames[this.currentMonth];
      },
      calendarDays() {
        const days = [];
        const firstDay = new Date(this.currentYear, this.currentMonth, 1);
        const startDate = new Date(firstDay);
        
        // Adjust to Monday start (0 = Sunday, 1 = Monday, etc.)
        const dayOfWeek = (firstDay.getDay() + 6) % 7;
        startDate.setDate(startDate.getDate() - dayOfWeek);
        
        // Generate 42 days (6 weeks)
        for (let i = 0; i < 42; i++) {
          const date = new Date(startDate);
          date.setDate(startDate.getDate() + i);
          
          const isCurrentMonth = date.getMonth() === this.currentMonth;
          const isToday = this.isSameDate(date, this.currentDate);
          const isSelected = this.selectedDate && this.isSameDate(date, this.selectedDate);
          const isWeekend = date.getDay() === 0 || date.getDay() === 6;
          
          days.push({
            date: date.getDate(),
            fullDate: new Date(date),
            isCurrentMonth,
            isOtherMonth: !isCurrentMonth,
            isToday,
            isSelected,
            isWeekend,
            isDisabled: false
          });
        }
        
        return days;
      }
    },
    methods: {
      updateJadwalSholat(index, updatedJadwal) {
        this.jadwalSholat[index] = updatedJadwal;
        localStorage.setItem('jadwalSholat', JSON.stringify(this.jadwalSholat));
      },
      refreshPage() {
        window.location.reload();
      },
      addKegiatan() {
        window.location.href = "/kegiatan/tambah";
      },
      editKegiatan(index) {
        window.location.href = `/kegiatan/edit/${index}`;
      },
      deleteKegiatan(index) {
        if (confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')) {
          this.kegiatanList.splice(index, 1);
          localStorage.setItem('kegiatanList', JSON.stringify(this.kegiatanList));
          alert('Data kegiatan berhasil dihapus!');
        }
      },
      goToEditJadwal(index) {
        const baseUrl = '/edit-jadwal'; 
        const queryParams = new URLSearchParams({
          index: index,
            nama: this.jadwalSholat[index].nama,
            jam: this.jadwalSholat[index].jam,
            imam: this.jadwalSholat[index].imam
        });
        window.location.href = `${baseUrl}?${queryParams.toString()}`;
      },
      previousMonth() {
        if (this.currentMonth === 0) {
          this.currentMonth = 11;
          this.currentYear--;
        } else {
          this.currentMonth--;
        }
      },
      nextMonth() {
        if (this.currentMonth === 11) {
          this.currentMonth = 0;
          this.currentYear++;
        } else {
          this.currentMonth++;
        }
      },
      selectDate(day) {
        if (!day.isDisabled) {
          this.selectedDate = day.fullDate;
        }
      },
      isSameDate(date1, date2) {
        return date1.getDate() === date2.getDate() &&
               date1.getMonth() === date2.getMonth() &&
               date1.getFullYear() === date2.getFullYear();
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
  
  /* Header - Same as Dashboard */
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
  
  /* Kegiatan Content */
  .kegiatan-content {
    display: grid;
    grid-template-rows: 1fr auto;
    gap: 16px;
    height: 100%;
    overflow: hidden;
  }
  
  /* Tabel Kegiatan - Updated to match Inventaris style */
  .kegiatan-table {
    background: #1c1816;
    border-radius: 20px;
    border: 1px solid #333;
    padding: 20px !important;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  
  /* Card Header - Same as Inventaris */
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
  
  /* Add Button - Same as Inventaris style */
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
  
  /* Activity Photo - Same as Inventaris item-photo */
  .activity-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: 0 auto !important;
    border: 2px solid #333;
  }
  
  /* Table Cell Content Styling - Following Inventaris pattern */
  .judul {
    font-weight: 600;
    color: #fff;
    min-width: 140px;
  }
  
  .tanggal {
    font-weight: 500;
    color: #fff;
    min-width: 100px;
  }
  
  .waktu {
    font-weight: 500;
    color: #fff;
    min-width: 80px;
  }
  
  .lokasi {
    font-weight: 400;
    color: #fff;
    min-width: 100px;
  }
  
  .tema {
    max-width: 180px;
    font-size: 0.8rem;
    line-height: 1.3;
    color: #fff;
    font-weight: 500;
  }
  
  .deskripsi {
    max-width: 200px;
    font-size: 0.8rem;
    line-height: 1.3;
    color: #fff;
    font-weight: 400;
  }
  
  .actions {
    text-align: center;
    min-width: 80px;
  }
  
  /* Action Buttons - Same as Inventaris */
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
  
  /* Sort Icon - Same as Inventaris */
  .sort-icon {
    font-size: 0.7rem;
    margin-left: 5px !important;
    color: #ccc;
  }
  
  /* Bottom Section */
  .bottom-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    height: 280px;
    flex-shrink: 0;
  }
  
  /* Jadwal Box */
  .jadwal-box {
    background: #1c1816;
    border-radius: 20px;
    border: 1px solid #333;
    padding: 20px !important;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  
  .section-header {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin-bottom: 16px !important;
    flex-shrink: 0;
  }
  
  .section-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #fff;
  }
  
  .jadwal-list {
    list-style: none;
    flex: 1;
    overflow-y: auto;
  }
  
  .jadwal-list li {
    display: grid;
    grid-template-columns: 30px 1fr auto auto 40px;
    align-items: center;
    gap: 12px;
    padding: 8px 0 !important;
    border-bottom: 1px solid #333;
    font-size: 0.85rem;
  }
  
  .jadwal-list li:last-child {
    border-bottom: none;
  }
  
  .prayer-icon {
    font-size: 1rem;
    text-align: center;
  }
  
  .prayer-name {
    font-weight: 500;
    color: #fff;
  }
  
  .prayer-time {
    font-weight: 600;
    color: #fff;
    text-align: center;
    min-width: 50px;
  }
  
  .prayer-imam {
    font-size: 0.75rem;
    color: #aaa;
    text-align: right;
    min-width: 100px;
  }
  
  .prayer-actions {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .prayer-actions .edit-btn {
    background: transparent;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    color: #fff;
    padding: 4px !important;
    border-radius: 4px;
    transition: background 0.2s ease;
  }
  
  .prayer-actions .edit-btn:hover {
    background: #1e5aa3;
  }
  
  /* Calendar Box */
  .calendar-box {
    background: #1c1816;
    border-radius: 20px;
    border: 1px solid #333;
    padding: 20px !important;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  
  .calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px !important;
    flex-shrink: 0;
  }
  
  .nav-btn {
    background: transparent;
    border: 1px solid #666;
    border-radius: 6px;
    color: #fff;
    cursor: pointer;
    padding: 4px 8px !important;
    font-size: 1.2rem;
    transition: all 0.2s ease;
  }
  
  .nav-btn:hover {
    background: #333;
    border-color: #fff;
  }
  
  .month-badge {
    background: #000;
    border: 1px solid #fff;
    border-radius: 15px;
    padding: 6px 16px !important;
    font-size: 0.85rem;
    font-weight: 500;
    color: #fff;
  }
  
  .calendar-container {
    flex: 1;
    overflow: hidden;
  }
  
  .calendar-grid {
    display: flex;
    flex-direction: column;
    height: 100%;
  }
  
  .weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
    margin-bottom: 8px !important;
  }
  
  .weekday {
    text-align: center;
    font-size: 0.75rem;
    font-weight: 600;
    color: #888;
    padding: 6px 2px !important;
  }
  
  .weekday.weekend {
    color: #4a9eff;
  }
  
  .calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    grid-template-rows: repeat(6, 1fr);
    gap: 2px;
    flex: 1;
  }
  
  .calendar-day {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    border-radius: 4px;
    cursor: pointer;
    color: #ddd;
    transition: all 0.2s ease;
    min-height: 28px;
    position: relative;
  }
  
  .calendar-day:hover:not(.disabled) {
    background: #333;
  }
  
  .calendar-day.disabled {
    color: #444;
    cursor: default;
  }
  
  .calendar-day.other-month {
    color: #555;
  }
  
  .calendar-day.weekend {
    color: #4a9eff;
  }
  
  .calendar-day.weekend.other-month {
    color: #2d5d99;
  }
  
  .calendar-day.selected {
    background: #000;
    color: #fff;
    border: 1px solid #fff;
  }
  
  .calendar-day.today {
    background: #444;
    color: #fff;
    font-weight: 600;
  }
  
  .calendar-day.today.selected {
    background: #000;
    border: 2px solid #4a9eff;
  }
  
  /* Responsive Design */
  @media (max-width: 1200px) {
    .bottom-section {
      grid-template-columns: 1fr;
      height: auto;
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
    
    .bottom-section {
      height: auto;
    }
    
    .action-buttons {
      flex-direction: column;
      gap: 4px;
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