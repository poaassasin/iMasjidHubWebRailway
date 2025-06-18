<template>
    <div class="light-spots"></div>
    <div class="container">
      <form @submit.prevent="handleSubmit" @reset="handleReset" class="form-card">
        <h2 class="form-title">Tambah Inventaris</h2>
        
        <div class="form-content">
          <div class="form-column full-width">
            <div class="form-group">
              <label for="kategori" class="form-label">Kategori</label>
              <select
                id="kategori"
                v-model="form.kategori"
                class="form-select"
              >
                <option value="" disabled>Pilih Kategori Barang</option>
                <option value="elektronik">Elektronik</option>
                <option value="furniture">Furniture</option>
                <option value="alat_tulis">Alat Tulis</option>
                <option value="kendaraan">Kendaraan</option>
                <option value="lainnya">Lainnya</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="nama" class="form-label">Nama</label>
              <input
                id="nama"
                v-model="form.nama"
                type="text"
                placeholder="Masukan Nama Barang"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="jumlah" class="form-label">Jumlah</label>
              <input
                id="jumlah"
                v-model="form.jumlah"
                type="number"
                placeholder="Masukan Jumlah Barang"
                class="form-input"
                min="1"
              />
            </div>
  
            <div class="form-group">
              <label for="lokasi" class="form-label">Lokasi</label>
              <input
                id="lokasi"
                v-model="form.lokasi"
                type="text"
                placeholder="Masukan Lokasi Barang"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="upload" class="form-label">Foto</label>
              <div class="upload-container">
                <button
                  @click="handleUpload"
                  type="button"
                  class="upload-button"
                >
                  <span class="upload-icon">📁</span>
                  <span>Tambah</span>
                </button>
                <input
                  ref="fileInput"
                  type="file"
                  @change="handleFileChange"
                  accept="image/*"
                  class="file-input"
                />
                <span v-if="selectedFile" class="file-name">
                  {{ selectedFile.name }}
                </span>
              </div>
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
  import { ref, reactive } from 'vue'
  import authMixin from '../mixins/auth';

  export default {
    mixins: [authMixin],
    name: 'FormTambahInventaris',
    setup() {
  
      // Reactive form data
      const form = reactive({
        kategori: '',
        nama: '',
        jumlah: '',
        lokasi: ''
      })
      
      // File handling
      const selectedFile = ref(null)
      const fileInput = ref(null)
      
      // Methods
      const handleSubmit = async () => {
        // Validasi form
        if (!form.kategori || !form.nama || !form.jumlah) {
          alert('Kategori, nama, dan jumlah barang harus diisi!')
          return
        }
        
        // Convert file to base64
        let fotoBase64 = "../assets/icon/inventaris.png"
        if (selectedFile.value) {
          fotoBase64 = await new Promise((resolve) => {
            const reader = new FileReader()
            reader.onload = (e) => resolve(e.target.result)
            reader.readAsDataURL(selectedFile.value)
          })
        }
  
        // Prepare data inventaris
        const newInventaris = {
          foto: fotoBase64,
          kategori: form.kategori,
          nama: form.nama,
          jumlah: parseInt(form.jumlah),
          lokasi: form.lokasi,
          tanggal_ditambahkan: new Date().toISOString().split('T')[0]
        }
  
        // Simpan ke local storage untuk sementara
        const existingInventaris = JSON.parse(localStorage.getItem('inventarisList') || '[]')
        existingInventaris.push(newInventaris)
        localStorage.setItem('inventarisList', JSON.stringify(existingInventaris))
        
        alert('Data inventaris berhasil ditambahkan!')
        
        // Redirect ke halaman inventaris
        window.location.href = "/inventaris";
      }
      
      const handleReset = () => {
        // Reset form data
        Object.keys(form).forEach(key => {
          form[key] = ''
        })
        selectedFile.value = null
        // Navigate back to inventory page
        window.location.href = "/inventaris";
      }
      
      const handleUpload = () => {
        fileInput.value?.click()
      }
      
      const handleFileChange = (event) => {
        const file = event.target.files[0]
        if (file) {
          // Validasi ukuran file (max 2MB)
          if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB')
            event.target.value = ''
            return
          }
  
          // Validasi tipe file
          if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!')
            event.target.value = ''
            return
          }
  
          selectedFile.value = file
          console.log('File selected:', file.name)
        }
      }
      
      return {
        form,
        selectedFile,
        fileInput,
        handleSubmit,
        handleReset,
        handleUpload,
        handleFileChange
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
  
  .form-select {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 1px solid #6b7280;
    font-size: 12px;
    color: white;
    padding: 8px 0;
    outline: none;
    cursor: pointer;
  }
  
  .form-select:focus {
    border-bottom-color: #9ca3af;
  }
  
  .form-select option {
    background-color: #121212;
    color: white;
  }
  
  .form-select option:disabled {
    color: rgba(117, 117, 117, 0.6);
  }
  
  .upload-container {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #6b7280;
    padding: 8px 0;
    gap: 8px;
  }
  
  .upload-button {
    display: flex;
    align-items: center;
    gap: 4px;
    background: transparent;
    border: 1px solid #9ca3af;
    border-radius: 20px;
    color: #9ca3af;
    font-size: 12px;
    padding: 2px 8px;
    cursor: pointer;
    transition: color 0.2s;
  }
  
  .upload-button:hover {
    color: #d1d5db;
  }
  
  .upload-icon {
    font-size: 10px;
  }
  
  .file-input {
    display: none;
  }
  
  .file-name {
    font-size: 12px;
    color: #9ca3af;
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