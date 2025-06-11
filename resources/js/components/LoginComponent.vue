<template>
  <div class="light-spots"></div>
  <div class="container">
    <!-- Gambar kiri -->
    <div class="left-side">
      <img src="../assets/masjid.jpg" alt="Masjid" class="masjid-img" />
    </div>

    <!-- Form kanan -->
    <div class="right-side">
      <div class="form-container">
        <h2 class="login-title">Login</h2>
        
        <div class="logo-container">
          <img src="../assets/icon/mosque-logo.png" alt="iMasjidHub" class="logo-img" />
          <span class="logo-text">iMasjidHub</span>
        </div>
        
        <form @submit.prevent="login" class="login-form">
          <div class="input-container">
            <div class="input-group">
              <img src="../assets/icon/email.jpg" alt="Email" class="input-icon" />
              <input
                type="email"
                v-model="email"
                placeholder="Enter your email here"
                required
              />
            </div>
          </div>
          
          <div class="input-container">
            <div class="input-group">
              <img src="../assets/icon/lock.jpg" alt="Password" class="input-icon" />
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="password"
                placeholder="Enter your password here"
                required
              />
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="eye-button"
              >
              <img 
                  :src="eyeIcon"
                  :alt="showPassword ? 'Hide Password' : 'Show Password'"
                  class="eye-icon"
                />
              </button>
            </div>
          </div>
          
          <button type="submit" class="login-btn" :disabled="loading">
            {{ loading ? 'Logging in...' : 'Login' }}
          </button>
          <p v-if="error" class="error-message">{{ error }}</p>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import eyeOpen from '../assets/icon/eye1.png'
import eyeClosed from '../assets/icon/eye2.png'

export default {
  mounted() {
    console.log('Component mounted.')
  },
  data() {
    return {
      email: "",
      password: "",
      showPassword: false,
      loading: false,
      error: null,
    };
  },
  computed: {
    eyeIcon() {
      return this.showPassword ? eyeOpen : eyeClosed;
    }
  },
  methods: {
    async login() {
      this.loading = true;
      this.error = null;
      try {
        // Pastikan URL-nya benar, tidak perlu /api lagi
        const response = await axios.post('/login', {
          email: this.email,
          password: this.password,
        });

        window.location.href = "/dashboard";

        if (response.data.token) {
          console.log('Berhasil Match Data')
          // 2. Stores the token from the response
          localStorage.setItem('token', response.data.token);

          // 3. Sets the Authorization header for all future axios requests
          axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
          
          // 4. Redirects to the dashboard using Vue Router
          window.location.href = "/dashboard"
        } else {
            this.error = 'Login successful, but no token was received.=';
        }
      } catch (error) {
        // 5. Catches errors from the API (like 401 Invalid Credentials)
        console.error("Detail Error dari Server:", error.response);

        if (error.response) {
          // Server merespons dengan status error (4xx, 5xx)
          const status = error.response.status;
          if (status === 401) {
            this.error = 'Email atau password yang Anda masukkan salah.';
          } else if (status === 422) {
            // Ini adalah error validasi
            this.error = 'Data yang dikirim tidak valid. Pastikan email adalah email yang valid.';
          } else {
            // Ini adalah error server (500 Internal Server Error)
            this.error = 'Terjadi kesalahan pada server. Silakan cek log Laravel.';
          }
        } else if (error.request) {
          // Request dikirim tapi tidak ada respons
          this.error = 'Tidak bisa terhubung ke server. Periksa koneksi Anda.';
        } else {
          // Error lain saat membuat request
          this.error = 'Terjadi kesalahan saat mengirimkan permintaan.';
        }
      } finally {
        this.loading = false;
        console.log('Finally False')
      }
    },
  },
};
</script>


<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

.container {
  display: flex;
  width: 100%;
  height: 100vh;
  min-height: 100vh;
  max-width: 1920px;
  max-height: 1080px;
  margin: 0 auto;
  color: white;
}

/* Left side with mosque image */
.left-side {
  flex: 1;
  overflow: hidden;
  position: relative;
}

.masjid-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

/* Right side with login form */
.right-side {
  width: 40%;
  background-color: #121212;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  padding: 0 40px;
}

.form-container {
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.login-title {
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 20px;
  text-align: center;
}

.logo-container {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(255, 255, 255, 0.1);
  border-radius: 50px;
  padding: 8px 16px;
  margin-bottom: 30px;
}

.logo-img {
  width: 24px;
  height: 24px;
  margin-right: 8px;
}

.logo-text {
  font-size: 14px;
  font-weight: 500;
}

.login-form {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.input-container {
  margin-bottom: 16px;
  width: 100%;
}

.login-btn {
  width: 60%;
  background-color: #7B6F63;
  color: white;
  border: none;
  border-radius: 50px;
  padding: 4px;
  font-size: 14px;
  font-weight: 400;
  cursor: pointer;
  margin-top: 10px;
  transition: background-color 0.3s;
}

.input-group {
  display: flex;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.05);
  border-radius: 50px;
  padding: 12px 16px;
  position: relative;
  overflow: hidden;
}

.input-icon {
  width: 20px;
  height: 20px;
  margin-right: 12px;
}

.input-group input {
  flex: 1;
  background: transparent;
  border: none;
  color: white;
  font-size: 14px;
  outline: none;
}

.input-group input::placeholder {
  color: rgba(255, 255, 255, 0.5);
}

input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
  display: none !important;
}

.password-input {
  padding-right: 40px !important;
  -webkit-text-security: disc !important;
  -webkit-appearance: none !important;
  -moz-appearance: none !important;
  appearance: none !important;
}

.password-input::-ms-reveal,
.password-input::-ms-clear,
.password-input::-webkit-contacts-auto-fill-button,
.password-input::-webkit-credentials-auto-fill-button,
.password-input::-webkit-inner-spin-button,
.password-input::-webkit-outer-spin-button,
.password-input::-webkit-search-cancel-button {
  display: none !important;
  -webkit-appearance: none !important;
  margin: 0;
}

.eye-button {
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 2;
}

.eye-icon {
  width: 24px;
  height: 24px;
  opacity: 0.7;
  transition: opacity 0.3s;
  filter: invert(1);
}

.eye-button:hover .eye-icon {
  opacity: 1;
}

.login-btn {
  width: 80%;
  background-color: #7B6F63;
  color: white;
  border: none;
  border-radius: 50px;
  padding: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  margin: 10px auto 0;
  transition: background-color 0.3s;
}

.login-btn:hover {
  background-color: #8a7c6e;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
  .right-side {
    width: 50%;
  }
}

@media (max-width: 768px) {
  .container {
    flex-direction: column;
  }
  
  .left-side {
    display: none;
  }
  
  .right-side {
    width: 100%;
    height: 100vh;
    padding: 40px 20px;
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