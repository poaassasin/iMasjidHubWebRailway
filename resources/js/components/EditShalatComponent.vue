<template>
  <div class="light-spots"></div>
    <div class="container">
      <form @submit.prevent="handleSubmit" @reset="handleReset" class="form-card">
        <h2 class="form-title">Edit Jadwal dan Imam</h2>
        
        <div class="form-content">
          <div class="form-group">
            <label for="sholat" class="form-label">Sholat</label>
            <input
              id="sholat"
              v-model="form.nama"
              type="text"
              placeholder="Masukan Jenis Sholat"
              class="form-input"
              readonly
            />
          </div>
          
          <div class="form-group">
            <label for="waktu" class="form-label">Waktu</label>
            <input
              id="waktu"
              v-model="form.jam"
              type="text"
              placeholder="Masukan Waktu"
              class="form-input"
            />
          </div>
          
          <div class="form-group">
            <label for="imam" class="form-label">Imam</label>
            <input
              id="imam"
              v-model="form.imam"
              type="text"
              placeholder="Masukan Nama Imam"
              class="form-input"
            />
          </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="form-actions">
          <button type="reset" class="btn-cancel" @click="goBack">
            Batalkan
          </button>
          <button type="submit" class="btn-submit">
            Ubah
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  export default {
    name: 'EditJadwalImam',
    data() {
      return {
        form: {
          nama: '',
          jam: '',
          imam: '',
          index: null
        }
      }
    },
    created() {
      const queryParams = new URLSearchParams(window.location.search);
      if (queryParams.has('index')) {
      const indexParam = queryParams.get('index');
      // Coba parse ke integer, berikan nilai null jika gagal atau bukan angka
      this.form.index = !isNaN(parseInt(indexParam)) ? parseInt(indexParam) : null;
    }

    if (queryParams.has('nama')) {
      this.form.nama = queryParams.get('nama');
    } else {
      this.form.nama = ''; // Default jika tidak ada parameter nama
    }

    if (queryParams.has('jam')) {
      this.form.jam = queryParams.get('jam');
    } else {
      this.form.jam = ''; // Default jika tidak ada parameter jam
    }

    if (queryParams.has('imam')) {
      this.form.imam = queryParams.get('imam');
    } else {
      this.form.imam = ''; // Default jika tidak ada parameter imam
    }
    },
    methods: {
      handleSubmit() {
        // Validasi form
        if (!this.form.nama || !this.form.jam || !this.form.imam) {
          alert('Semua field harus diisi!')
          return
        }
  
        // Load existing data from localStorage
        let jadwalSholat = JSON.parse(localStorage.getItem('jadwalSholat')) || [
          { icon: "🌤️", nama: "Subuh", jam: "04.14", imam: "Ust. Jalaluddin" },
          { icon: "☀️", nama: "Dzuhur", jam: "11.29", imam: "Ust. Jalaluddin" },
          { icon: "🌥️", nama: "Ashar", jam: "14.50", imam: "Ust. Jalaluddin" },
          { icon: "🌇", nama: "Maghrib", jam: "17.22", imam: "Ust. Jalaluddin" },
          { icon: "🌙", nama: "Isya", jam: "18.34", imam: "Ust. Jalaluddin" }
        ];
  
        // Update the specific jadwal
        if (this.form.index !== null && this.form.index >= 0 && this.form.index < jadwalSholat.length) {
          jadwalSholat[this.form.index].jam = this.form.jam
          jadwalSholat[this.form.index].imam = this.form.imam
        }
  
        // Save back to localStorage
        localStorage.setItem('jadwalSholat', JSON.stringify(jadwalSholat))
        
        alert('Data jadwal berhasil diubah!')
        
        // Redirect back to kegiatan page
        window.location.href = "/kegiatan";
      },
      
      handleReset() {
        // Reset form data to original values
        this.form.jam = this.$route.query.jam || ''
        this.form.imam = this.$route.query.imam || ''
      },
      
      goBack() {
        window.location.href = "/kegiatan";
      }
    }
  }
  </script>
  
  <style scoped>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
  
  * {
    font-family: 'Poppins', sans-serif;
    box-sizing: border-box;
  }
  
  .container {
    background-color: #121212;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
  }
  
  .form-card {
    max-width: 700px;
    width: 100%;
    background: transparent;
    border: 1px solid #6b7280;
    border-radius: 12px;
    padding: 32px;
    color: white;
  }
  
  .form-title {
    text-align: center;
    font-weight: 600;
    font-size: 20px;
    margin-bottom: 32px;
    margin-top: 0;
  }
  
  .form-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
  }
  
  .form-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 4px;
    color: white;
  }
  
  .form-input {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 1px solid #6b7280;
    font-size: 12px;
    color: white;
    padding: 8px 0;
    outline: none;
  }
  
  .form-input[readonly] {
    color: #888;
    border-bottom: 1px dashed #6b7280;
  }
  
  .form-input::placeholder {
    color: rgba(117, 117, 117, 0.6);
  }
  
  .form-input:focus {
    border-bottom-color: #9ca3af;
  }
  
  .form-actions {
    margin-top: 32px;
    display: flex;
    justify-content: space-between;
  }
  
  .btn-cancel {
    font-size: 12px;
    font-weight: 600;
    background: transparent;
    border: 1px solid white;
    border-radius: 20px;
    color: white;
    padding: 8px 24px;
    cursor: pointer;
    transition: all 0.2s;
  }
  
  .btn-cancel:hover {
    background: white;
    color: black;
  }
  
  .btn-submit {
    font-size: 12px;
    font-weight: 600;
    background: #5A4B43;
    border: none;
    border-radius: 20px;
    color: white;
    padding: 8px 24px;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  
  .btn-submit:hover {
    background: #7a665d;
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