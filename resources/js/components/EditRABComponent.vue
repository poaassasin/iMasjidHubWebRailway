<template>
  <div class="light-spots"></div>
    <div class="container">
      <form @submit.prevent="handleSubmit" @reset="handleReset" class="form-card">
        <h2 class="form-title">Edit Rencana Anggaran</h2>
        
        <div class="form-content">
          <div class="form-group">
            <label for="nama" class="form-label">Nama</label>
            <input
              id="nama"
              v-model="form.nama"
              type="text"
              placeholder="Masukan Nama Rencana Anggaran"
              class="form-input"
            />
          </div>
          
          <div class="form-group">
            <label for="targetTanggal" class="form-label">Target Tanggal</label>
            <input
              id="targetTanggal"
              v-model="form.targetTanggal"
              type="text"
              placeholder="Masukan Target Tanggal Rencana"
              class="form-input"
            />
          </div>
          
          <div class="form-group">
            <label for="targetNominal" class="form-label">Target Nominal</label>
            <input
              id="targetNominal"
              v-model="form.targetNominal"
              type="text"
              placeholder="Masukan Target Nominal Anggaran"
              class="form-input"
            />
          </div>
          
          <div class="form-group">
            <label for="nominalTerkumpul" class="form-label">Nominal Terkumpul</label>
            <input
              id="nominalTerkumpul"
              v-model="form.nominalTerkumpul"
              type="text"
              placeholder="Masukan Nominal Anggaran yang Terkumpul"
              class="form-input"
            />
          </div>
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
  import { reactive, onMounted } from 'vue'
  
  export default {
    name: 'FormEditRencanaAnggaran',
    props: {
    initialAnggaranId: { // Prop untuk menerima ID dari Blade
      type: [String, Number], // ID bisa berupa angka atau string
      required: true
      }
    },
    setup(props) {
  
      // Reactive form data
      const form = reactive({
        nama: '',
        targetTanggal: '',
        targetNominal: '',
        nominalTerkumpul: ''
      })
      
      // Load data when component mounted
      onMounted(() => {
        const id = parseInt(props.initialAnggaranId);
        const existingRencana = JSON.parse(localStorage.getItem('rencanaAnggaranList') || '[]')
        const rencana = existingRencana[id]
        
        if (rencana) {
          form.nama = rencana.nama
          form.targetTanggal = rencana.targetTanggal
          form.targetNominal = rencana.targetNominal
          form.nominalTerkumpul = rencana.nominalTerkumpul
        } else {
          window.location.href = "/keuangan";
        }
      })
      
      // Methods
      const handleSubmit = async () => {
        // Validasi form
        if (!form.nama || !form.targetTanggal || !form.targetNominal) {
          alert('Nama, target tanggal, dan target nominal harus diisi!')
          return
        }
        
        const id = parseInt(props.initialAnggaranId);
        const existingRencana = JSON.parse(localStorage.getItem('rencanaAnggaranList') || '[]')
        
        // Update data rencana anggaran
        existingRencana[id] = {
          nama: form.nama,
          targetTanggal: form.targetTanggal,
          targetNominal: form.targetNominal,
          nominalTerkumpul: form.nominalTerkumpul || '0',
          updatedAt: new Date().toISOString()
        }
  
        localStorage.setItem('rencanaAnggaranList', JSON.stringify(existingRencana))
        
        alert('Data rencana anggaran berhasil diubah!')
        
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