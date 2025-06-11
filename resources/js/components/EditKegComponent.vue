<template>
    <div class="light-spots"></div>
    <div class="container">
      <form @submit.prevent="handleSubmit" @reset="handleReset" class="form-card">
        <h2 class="form-title">Edit Kegiatan</h2>
        
        <div class="form-content">
          <!-- Left Column -->
          <div class="form-column">
            <div class="form-group">
              <label for="judul" class="form-label">Judul Kegiatan</label>
              <input
                id="judul"
                v-model="form.judul"
                type="text"
                placeholder="Masukan Judul Kegiatan"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="waktu" class="form-label">Waktu</label>
              <input
                id="waktu"
                v-model="form.waktu"
                type="text"
                placeholder="Masukan Waktu Kegiatan"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="tema" class="form-label">Tema</label>
              <input
                id="tema"
                v-model="form.tema"
                type="text"
                placeholder="Masukan Tema Kegiatan"
                class="form-input"
              />
            </div>
          </div>
          
          <!-- Divider -->
          <div class="form-divider"></div>
          
          <!-- Right Column -->
          <div class="form-column">
            <div class="form-group">
              <label for="tanggal" class="form-label">Tanggal</label>
              <input
                id="tanggal"
                v-model="form.tanggal"
                type="text"
                placeholder="Masukan Tanggal"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="lokasi" class="form-label">Lokasi</label>
              <input
                id="lokasi"
                v-model="form.lokasi"
                type="text"
                placeholder="Masukan Lokasi Kegiatan"
                class="form-input"
              />
            </div>
            
            <div class="form-group">
              <label for="upload" class="form-label">Upload Foto</label>
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
        
        <!-- Description -->
        <div class="form-group full-width">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea
            id="deskripsi"
            v-model="form.deskripsi"
            rows="4"
            placeholder="Masukan Deskripsi Acara........"
            class="form-textarea"
          ></textarea>
        </div>
        
        <!-- Action Buttons -->
        <div class="form-actions">
          <button type="reset" class="btn-cancel">
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
  import { ref, reactive, onMounted } from 'vue'
  
  export default {
    name: 'FormEditKegiatan',
    props: {
    initialKegiatanId: { // Prop untuk menerima ID dari Blade
      type: [String, Number], // ID bisa berupa angka atau string
      required: true
    }
  },
    setup(props) {  
      // Reactive form data
      const form = reactive({
        judul: '',
        waktu: '',
        tema: '',
        tanggal: '',
        lokasi: '',
        deskripsi: '',
        foto: '../assets/icon/kegiatan.png'
      })
      
      // File handling
      const selectedFile = ref(null)
      const fileInput = ref(null)
      
      // Load existing data
      onMounted(() => {
        const kegiatanId = parseInt(props.initialKegiatanId);
        const kegiatanList = JSON.parse(localStorage.getItem('kegiatanList') || '[]')
        const kegiatan = kegiatanList[kegiatanId]
        
        if (kegiatan) {
          Object.keys(form).forEach(key => {
            form[key] = kegiatan[key] || ''
          })
        } else {
          alert('Data kegiatan tidak ditemukan')
          window.location.href = "/kegiatan";
        }
      })
  
      // Methods
      const handleSubmit = async () => {
        // Validasi form
        if (!form.judul || !form.tanggal) {
          alert('Judul kegiatan dan tanggal harus diisi!')
          return
        }
        
        // Convert file to base64
        let fotoBase64 = form.foto // Keep existing photo if not changed
        if (selectedFile.value) {
          fotoBase64 = await new Promise((resolve) => {
            const reader = new FileReader()
            reader.onload = (e) => resolve(e.target.result)
            reader.readAsDataURL(selectedFile.value)
          })
        }
  
        // Prepare data kegiatan
        const updatedKegiatan = {
          foto: fotoBase64,
          judul: form.judul,
          tanggal: form.tanggal,
          waktu: form.waktu,
          lokasi: form.lokasi,
          tema: form.tema,
          deskripsi: form.deskripsi
        }
  
        // Update di local storage
        const kegiatanId = parseInt(props.initialKegiatanId);
        const kegiatanList = JSON.parse(localStorage.getItem('kegiatanList') || '[]')
        kegiatanList[kegiatanId] = updatedKegiatan
        localStorage.setItem('kegiatanList', JSON.stringify(kegiatanList))
        
        alert('Data kegiatan berhasil diperbarui!')
        
        // Redirect ke halaman kegiatan
        window.location.href = "/kegiatan";
      }
      
      const handleReset = () => {
        // Reset form data
        Object.keys(form).forEach(key => {
          form[key] = ''
        })
        selectedFile.value = null
        
        // Kembali ke halaman kegiatan
        window.location.href = "/kegiatan";
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
  
  .form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    color: white;
  }
  
  .form-input {
    width: 100%;
    background: transparent;
    border: none;
    border-bottom: 1px solid #6b7280;
    font-size: 14px;
    color: white;
    padding: 10px 0;
    outline: none;
  }
  
  .form-textarea {
    width: 100%;
    background: transparent;
    border: 1px solid #6b7280;
    border-radius: 8px;
    color: white;
    font-size: 14px;
    padding: 16px;
    resize: none;
    outline: none;
  }
  
  .upload-button {
    display: flex;
    align-items: center;
    gap: 4px;
    background: transparent;
    border: 1px solid #9ca3af;
    border-radius: 20px;
    color: #9ca3af;
    font-size: 14px;
    padding: 4px 12px;
    cursor: pointer;
    transition: color 0.2s;
  }
  
  .btn-cancel, .btn-submit {
    font-size: 14px;
    font-weight: 600;
    padding: 10px 32px;
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
  
  .full-width {
    margin-top: 24px;
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
    color: #6b7280;
  }
  
  .form-input:focus {
    border-bottom-color: #9ca3af;
  }
  
  .form-textarea {
    width: 100%;
    background: transparent;
    border: 1px solid #6b7280;
    border-radius: 8px;
    color: white;
    font-size: 12px;
    padding: 12px;
    resize: none;
    outline: none;
  }
  
  .form-textarea::placeholder {
    color: rgba(125, 124, 124, 0.6);
  }
  
  .form-textarea:focus {
    border-color: #9ca3af;
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
  
  .form-input::placeholder {
    color: rgba(117, 117, 117, 0.6);
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