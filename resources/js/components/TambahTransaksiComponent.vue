<template>
    <div class="light-spots"></div>
    <div class="container">
      <form @submit.prevent="handleSubmit" @reset="handleReset" class="form-card">
        <h2 class="form-title">Tambah Daftar Transaksi</h2>
        
        <div class="form-content">
          <div class="form-column">
            <div class="form-group">
              <label for="kategori" class="form-label">Kategori</label>
              <div class="select-container">
                <select
                  id="kategori"
                  v-model="form.kategori"
                  class="form-select"
                >
                  <option value="" disabled>Pilih Kategori Transaksi</option>
                  <option value="pemasukan">Pemasukan</option>
                  <option value="pengeluaran">Pengeluaran</option>
                  <option value="transfer">Transfer</option>
                  <option value="investasi">Investasi</option>
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>
            
            <div class="form-group">
              <label for="jenis" class="form-label">Jenis</label>
              <div class="select-container">
                <select
                  id="jenis"
                  v-model="form.jenis"
                  class="form-select"
                >
                  <option value="" disabled>Pilih Jenis Transaksi</option>
                  <option value="tunai">Tunai</option>
                  <option value="transfer">Transfer Bank</option>
                  <option value="kartu_kredit">Kartu Kredit</option>
                  <option value="e_wallet">E-Wallet</option>
                </select>
                <span class="select-arrow">▼</span>
              </div>
            </div>
            
            <div class="form-group">
              <label for="tanggal" class="form-label">Tanggal</label>
              <input
                id="tanggal"
                v-model="form.tanggal"
                type="text"
                placeholder="Masukan Tanggal Transaksi"
                class="form-input"
              />
            </div>
  
            <div class="form-group">
              <label for="nominal" class="form-label">Nominal</label>
              <input
                id="nominal"
                v-model="form.nominal"
                type="text"
                placeholder="Masukan Nominal Transaksi"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="keterangan" class="form-label">Keterangan</label>
              <input
                id="keterangan"
                v-model="form.keterangan"
                type="text"
                placeholder="Masukan Keterangan Transaksi"
                class="form-input"
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
  
  export default {
    name: 'FormTambahTransaksi',
    setup() {
  
      // Reactive form data
      const form = reactive({
        kategori: '',
        jenis: '',
        tanggal: '',
        nominal: '',
        keterangan: ''
      })
      
      // Methods
      const handleSubmit = async () => {
        // Validasi form
        if (!form.kategori || !form.jenis || !form.tanggal || !form.nominal) {
          alert('Kategori, jenis, tanggal, dan nominal harus diisi!')
          return
        }
        
        // Prepare data transaksi
        const newTransaksi = {
          kategori: form.kategori,
          jenis: form.jenis,
          tanggal: form.tanggal,
          nominal: form.nominal,
          keterangan: form.keterangan,
          createdAt: new Date().toISOString()
        }
  
        // Simpan ke local storage untuk sementara
        const existingTransaksi = JSON.parse(localStorage.getItem('transaksiList') || '[]')
        existingTransaksi.push(newTransaksi)
        localStorage.setItem('transaksiList', JSON.stringify(existingTransaksi))
        
        alert('Data transaksi berhasil ditambahkan!')
        
        // Redirect ke halaman keuangan
        window.location.href = "/keuangan";
      }
      
      const handleReset = () => {
        // Reset form data
        Object.keys(form).forEach(key => {
          form[key] = ''
        })
        // Navigate back to inventory page
        window.location.href = "/keuangan";
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
    display: flex;
    flex-direction: column;
    gap: 24px;
  }
  
  @media (min-width: 640px) {
    .form-content {
      flex-direction: row;
      gap: 32px;
    }
  }
  
  .form-column {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 24px;
  }
  
  .form-divider {
    border-left: 1px solid #6b7280;
    margin: 0 32px;
  }
  
  @media (max-width: 639px) {
    .form-divider {
      display: none;
    }
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
  
  .select-container {
    position: relative;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #6b7280;
  }
  
  .form-select {
    width: 100%;
    background: transparent;
    border: none;
    font-size: 12px;
    color: white;
    padding: 8px 0;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
  }
  
  .form-select option {
    background-color: #121212;
    color: white;
  }
  
  .form-select option:disabled {
    color: rgba(117, 117, 117, 0.6);
  }
  
  .select-arrow {
    position: absolute;
    right: 0;
    color: #9ca3af;
    font-size: 10px;
    pointer-events: none;
  }
  
  .select-container:focus-within {
    border-bottom-color: #9ca3af;
  }
  
  .form-select:focus {
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