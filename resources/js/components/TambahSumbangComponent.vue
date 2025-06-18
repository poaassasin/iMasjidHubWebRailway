<template>
  <div class="light-spots"></div>
    <div class="container">
      <form @submit.prevent="handleSubmit" @reset="handleReset" class="form-card">
        <h2 class="form-title">Tambah Penyumbang Inventaris</h2>
        
        <div class="form-content">
          <div class="form-row">
            <div class="form-group full-width">
              <label for="penyumbang" class="form-label">Nama Penyumbang</label>
              <input
                id="penyumbang"
                v-model="form.penyumbang"
                type="text"
                placeholder="Masukan Nama Penyumbang"
                class="form-input"
              />
            </div>
          </div>
  
          <div class="form-row">
            <div class="form-group full-width">
              <label for="noTelefon" class="form-label">No. Telepon</label>
              <input
                id="noTelefon"
                v-model="form.noTelefon"
                type="tel"
                placeholder="Masukan Nomor Telepon"
                class="form-input"
              />
            </div>
          </div>
  
          <div class="form-row">
            <div class="form-group full-width">
              <label for="namaBarang" class="form-label">Nama Barang</label>
              <input
                id="namaBarang"
                v-model="form.namaBarang"
                type="text"
                placeholder="Masukan Nama Barang"
                class="form-input"
              />
            </div>
          </div>
  
          <div class="form-row">
            <div class="form-group full-width">
              <label for="jumlahBarang" class="form-label">Jumlah Barang</label>
              <input
                id="jumlahBarang"
                v-model="form.jumlahBarang"
                type="number"
                placeholder="Masukan Jumlah Barang"
                class="form-input"
                min="1"
              />
            </div>
          </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="form-actions">
          <button type="reset" class="btn-cancel">
            Batalkan
          </button>
          <button type="submit" class="btn-submit">
            Tambah
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import { reactive } from 'vue'
  import authMixin from '../mixins/auth';

  export default {
    mixins: [authMixin],
    name: 'FormTambahPenyumbangInventaris',
    setup() {
  
      // Reactive form data
      const form = reactive({
        penyumbang: '',
        noTelefon: '',
        namaBarang: '',
        jumlahBarang: ''
      })
      
      // Methods
      const handleSubmit = async () => {
        // Validasi form
        if (!form.penyumbang || !form.namaBarang) {
          alert('Nama penyumbang dan nama barang harus diisi!')
          return
        }
        
        // Validasi nomor telepon
        if (form.nomorTelepon && !/^[0-9+\-\s()]+$/.test(form.nomorTelepon)) {
          alert('Format nomor telepon tidak valid!')
          return
        }
        
        // Validasi jumlah
        if (form.jumlah && (isNaN(form.jumlah) || parseInt(form.jumlah) <= 0)) {
          alert('Jumlah harus berupa angka positif!')
          return
        }
  
        // Prepare data penyumbang
        const newPenyumbang = {
          penyumbang: form.penyumbang,
          noTelefon: form.noTelefon,
          namaBarang: form.namaBarang,
          jumlahBarang: `${form.jumlahBarang} Buah`,
          tanggal_sumbangan: new Date().toISOString().split('T')[0]
        }
  
        // Simpan ke local storage
        const donorList = JSON.parse(localStorage.getItem('donorList') || '[]')
        donorList.push(newPenyumbang)
        localStorage.setItem('donorList', JSON.stringify(donorList))
        
        alert('Data penyumbang inventaris berhasil ditambahkan!')
        
        // Redirect ke halaman inventaris
        window.location.href = "/inventaris";
      }
      
      const handleReset = () => {
        // Reset form data
        Object.keys(form).forEach(key => {
          form[key] = ''
        })
        // Navigate back to inventory page
        window.location.href = "/inventaris";
      }
      
      return {
        form,
        handleSubmit,
        handleReset
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
    max-width: 800px;
    width: 100%;
    background: transparent;
    border: 1px solid #6b7280;
    border-radius: 8px;
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
    margin-bottom: 2rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .form-row {
    display: flex;
    gap: 1rem;
    width: 100%;
  }
  
  .full-width {
    width: 100%;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    width: 100%;
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