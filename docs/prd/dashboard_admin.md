# Product Requirement Document (PRD) - Dashboard Admin
## Fitur: Dashboard Statistik & Grafik Analitik Pertumbuhan Pengguna dan Event

---

## 1. Deskripsi Fitur
Untuk mempermudah pihak manajemen (`superadmin`) dan penyelenggara acara (`organizer`) dalam memantau kinerja platform, fitur **Dashboard Admin** akan dikembangkan dengan visualisasi berbasis grafik interaktif. Dashboard ini menyajikan metrik bisnis utama, seperti tren pertumbuhan jumlah pengguna baru (registrasi) dan perkembangan jumlah event yang diselenggarakan dari waktu ke waktu secara real-time.

---

## 2. Aturan Bisnis & Alur Pengguna (Business Rules & User Flow)

### 2.1 Hak Akses Halaman (Authorization Rules)
1. **Superadmin (Manajemen Global):**
   * Memiliki akses penuh ke semua data agregat di platform secara global.
   * Metrik yang ditampilkan: Total pengguna terdaftar seluruh platform, total seluruh event (terbagi berdasarkan kategori/status), total volume transaksi sukses, serta tren pendapatan platform.
2. **Organizer (Penyelenggara Event):**
   * Hanya memiliki akses terbatas pada metrik dari event yang mereka kelola sendiri.
   * Metrik yang ditampilkan: Jumlah event yang dibuat oleh organizer tersebut, total tiket yang terjual pada event mereka, serta grafik tren pendapatan dari penjualan tiket mereka.
3. **User Biasa (Pembeli Tiket):**
   * Tidak diperbolehkan mengakses halaman dashboard. Sistem harus mengembalikan respon `403 Forbidden` atau mengalihkan user secara otomatis ke beranda utama.

### 2.2 Alur Pengguna (User Flow)
1. Pihak `superadmin` atau `organizer` melakukan login ke sistem.
2. Pengguna bernavigasi ke halaman dashboard utama (`/admin/dashboard` untuk superadmin, `/organizer/dashboard` untuk organizer).
3. Halaman melakukan inisialisasi query backend secara asinkron (AJAX) untuk mengambil data ringkasan statistik dan data koordinat grafik tren (bulanan/mingguan).
4. Halaman menampilkan:
   * **Kartu Ringkasan (Summary Cards):** Angka total beserta persentase kenaikan dibanding periode sebelumnya.
   * **Grafik Garis (Line Chart):** Menunjukkan pertumbuhan akumulatif pengguna terdaftar baru dari bulan ke bulan.
   * **Grafik Batang (Bar Chart):** Menunjukkan distribusi kategori event yang diselenggarakan atau volume tiket terjual per event.
5. Pengguna dapat memilih filter rentang waktu (misalnya: *7 Hari Terakhir*, *30 Hari Terakhir*, *1 Tahun Terakhir*), yang akan memicu render ulang grafik menggunakan data baru yang diambil secara dinamis.

---

## 3. Desain Skema Database & Kueri Data

Fitur dashboard ini tidak membutuhkan penambahan tabel baru di database karena memanfaatkan data yang sudah tercatat pada tabel yang ada (`users`, `events`, `transactions`). Namun, query optimasi sangat disarankan untuk menjaga performa loading dashboard.

### 3.1 Contoh Kueri Agregasi Data (Laravel Eloquent)

* **Tren Pertumbuhan Pengguna Baru (Bulanan):**
  ```php
  $userGrowth = User::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(id) as count")
      ->where('role', 'user')
      ->whereYear('created_at', now()->year)
      ->groupBy('month')
      ->orderBy('month', 'asc')
      ->get();
  ```

* **Tren Pertumbuhan Penyelenggaraan Event (Bulanan):**
  ```php
  $eventGrowth = Event::selectRaw("DATE_FORMAT(date, '%Y-%m') as month, COUNT(id) as count")
      ->whereYear('date', now()->year)
      ->groupBy('month')
      ->orderBy('month', 'asc')
      ->get();
  ```

---

## 4. Alir Backend & API Endpoint (Backend Logic & Endpoints)

### 4.1 Registrasi Rute di `routes/web.php`
Akses harus dilindungi oleh middleware autentikasi dan middleware pengecekan role:
```php
Route::middleware(['auth', 'role:superadmin,organizer'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/api/stats-data', [DashboardController::class, 'getStatsData'])->name('admin.dashboard.api');
});
```

### 4.2 Struktur Respon API (`/admin/dashboard/api/stats-data`)
Mengembalikan struktur data JSON yang siap dikonsumsi oleh pustaka grafik frontend (seperti Chart.js):
```json
{
  "status": "success",
  "data": {
    "cards": {
      "total_users": 1240,
      "total_events": 45,
      "total_tickets_sold": 890,
      "total_revenue": 45000000
    },
    "charts": {
      "user_growth": {
        "labels": ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
        "datasets": [120, 150, 210, 310, 480, 600]
      },
      "event_growth": {
        "labels": ["Jan", "Feb", "Mar", "Apr", "May", "Jun"],
        "datasets": [2, 5, 8, 12, 10, 15]
      }
    }
  }
}
```

---

## 5. Persyaratan UI/UX (Mengikuti design.md)

### 5.1 Tata Letak Halaman (Dashboard Layout)
* **Sidebar Navigasi:** Memiliki status aktif pada menu "Dashboard".
* **Kombinasi Grid Kartu Statistik:**
  * Terdiri dari 4 kolom kartu metrik ringkas: *Total Pengguna*, *Total Event*, *Tiket Terjual*, dan *Total Pendapatan*.
  * Setiap kartu memiliki border tipis `neutral-200` (`#e2e8f0`), background putih (`#ffffff`), soft shadow (`shadow-sm`), dan ikon indikator trend berwarna hijau `emerald-600` (untuk tren naik) atau merah `rose-600` (untuk tren turun).
* **Section Visualisasi Grafik:**
  * Layout 2 kolom di desktop untuk menampilkan dua grafik utama berdampingan.
  * Canvas grafik dibungkus dalam container ber-border `1px solid neutral-200` dengan radius sudut halus (`rounded-2xl`).

### 5.2 Estetika Visual Grafik (Chart Aesthetics)
* Menggunakan pustaka visualisasi grafik modern, seperti **Chart.js** atau **ApexCharts**.
* **Grafik Pertumbuhan Pengguna (Line Chart):**
  * Warna garis utama: `violet-500` (`#8b5cf6`) dengan ketebalan border `2.5px`.
  * Efek area di bawah garis: Gradasi halus (*smooth gradient opacity*) dari `violet-400` dengan opasitas `0.1` menuju transparan.
  * Pointer titik data: Memiliki efek hover membesar dengan warna background `violet-600`.
* **Grafik Perkembangan Event (Bar Chart):**
  * Warna batang grafik: `indigo-500` (`#6366f1`) dengan sudut batang melengkung halus (`borderRadius: 4`).
* **Responsivitas:** Grafik harus dikonfigurasi agar menyesuaikan ukuran container secara dinamis (`responsive: true` & `maintainAspectRatio: false`) sehingga tetap rapi saat dibuka dari perangkat mobile.

---

## 6. Rencana Pengujian (Testing Plan)

* **Pengujian Validasi Hak Akses Halaman (Role Authorization):**
  * Akses halaman `/admin/dashboard` menggunakan akun pengguna biasa (role `user`). Sistem harus mengembalikan error `403 Forbidden` atau redirect kembali.
  * Akses halaman menggunakan akun `superadmin` dan `organizer`. Pastikan dashboard dimuat dengan sukses.
* **Pengujian Keakuratan Agregasi Data:**
  * Lakukan registrasi 2 pengguna baru lewat fitur registrasi. Refresh halaman dashboard, pastikan angka pada kartu *Total Pengguna* bertambah tepat 2 unit secara instan.
* **Pengujian Filter Rentang Waktu:**
  * Pilih filter *"7 Hari Terakhir"*. Pastikan AJAX request terkirim dengan parameter filter yang sesuai dan grafik dirender ulang dengan label tanggal harian (bukan bulanan).
* **Pengujian Responsivitas UI:**
  * Ubah ukuran viewport browser ke ukuran layar ponsel pintar (`375px` - `425px`). Pastikan tata letak grid berubah menjadi 1 kolom vertikal dan grafik tidak memotong batas layar.
