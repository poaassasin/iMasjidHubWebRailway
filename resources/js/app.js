import './bootstrap';
import { createApp } from 'vue';
import axios from 'axios'; // 1. PASTIKAN AXIOS DI-IMPORT

// =======================================================
// === KONFIGURASI AXIOS GLOBAL DITEMPATKAN DI SINI ===
// =======================================================

// 2. Ambil token dari localStorage saat aplikasi dimuat.
//    Ganti 'auth_token' jika Anda menggunakan nama kunci yang berbeda.
const token = localStorage.getItem('auth_token');

// 3. Jika token ada, atur sebagai header default untuk semua permintaan Axios.
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// 4. Atur base URL agar lebih ringkas saat memanggil API
axios.defaults.baseURL = 'http://127.0.0.1:8000';

// =======================================================
// === AKHIR DARI KONFIGURASI AXIOS ===
// =======================================================


const app = createApp({});

import DashboardComponent from './components/DashboardComponent.vue';
import LoginComponent from './components/LoginComponent.vue';

//inventaris
import InventarisComponent from './components/InventarisComponent.vue';
import TbhInventarisComponent from './components/TambahInvComponent.vue';
import TbhSumbangComponent from './components/TambahSumbangComponent.vue';
import TbhKeluarMasukComponent from './components/TambahKeluarMasukInvComponent.vue';
import EditInventarisComponent from './components/EditInvComponent.vue';
import EditPenyumbangComponent from './components/EditPenyumbangComponent.vue';
import EditMovementComponent from './components/EditMovementComponent.vue';

//kegiatan
import KegiatanComponent from './components/KegiatanComponent.vue';
import TbhKegiatanComponent from './components/TambahKegiatanComp.vue';
import EditShalatComponent from './components/EditShalatComponent.vue';
import EditKegiatanComponent from './components/EditKegComponent.vue';

//keuangan
import KeuanganComponent from './components/KeuanganComponent.vue';
import TbhTransaksiComponent from './components/TambahTransaksiComponent.vue';
import TbhAnggaranComponent from './components/TambahRABComponent.vue';
import EditTransaksiComponent from './components/EditTransaksiComponent.vue';
import EditRABComponent from './components/EditRABComponent.vue';

app.component('dashboard-component', DashboardComponent);
app.component('login-component', LoginComponent);

//inventaris
app.component('inventaris-component', InventarisComponent);
app.component('tbhinventaris-component', TbhInventarisComponent);
app.component('tbhsumbang-component', TbhSumbangComponent);
app.component('tbhkeluarmasuk-component', TbhKeluarMasukComponent);
app.component('editinventaris-component', EditInventarisComponent);
app.component('editpenyumbang-component', EditPenyumbangComponent);
app.component('editmovement-component', EditMovementComponent);

//kegiatan
app.component('kegiatan-component', KegiatanComponent);
app.component('tbhkegiatan-component', TbhKegiatanComponent);
app.component('editshalat-component', EditShalatComponent);
app.component('editkegiatan-component', EditKegiatanComponent);

//keuangan
app.component('keuangan-component', KeuanganComponent);
app.component('tbhtransaksi-component', TbhTransaksiComponent);
app.component('tbhanggaran-component', TbhAnggaranComponent);
app.component('edittransaksi-component', EditTransaksiComponent);
app.component('editanggaran-component', EditRABComponent);

app.mount('#app');