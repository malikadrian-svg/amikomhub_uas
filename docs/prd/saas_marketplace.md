# Product Requirement Document (PRD) - SaaS Marketplace
## Fitur: Ekspansi Multi-Tenant SaaS Marketplace (HIMA/Kepanitiaan)

---

## 1. Deskripsi Fitur
Mengembangkan AmikomHub dari aplikasi e-ticketing "Satu Toko" menjadi platform marketplace multi-tenant layaknya Tokopedia/Tiket.com. Setiap Himpunan Mahasiswa (HIMA) atau Kepanitiaan dapat membuat akun, mempublikasikan acara mereka sendiri secara mandiri, dan melacak kinerja penjualan tiket di dasbor khusus mereka. Sementara itu, Superadmin memegang kontrol kelayakan (kurasi) atas semua event yang didaftarkan sebelum ditayangkan ke publik.

---

## 2. Struktur Hak Akses & Peran (Roles & Permissions)

Sistem memecah pengguna ke dalam 3 tingkatan peran:

1. **Superadmin (Admin Pusat):**
   * Mengelola kelayakan penyelenggara (HIMA/Kepanitiaan).
   * Meninjau antrean pengajuan event baru (`verify / approve / reject`).
   * Mengakses analitik makro (seluruh transaksi, total omzet platform, total tiket terjual di platform).
2. **Organizer (HIMA/Kepanitiaan):**
   * Melakukan registrasi akun penyelenggara.
   * Membuat draf event, mengunggah materi promosi (poster, keterangan, kuota awal, harga).
   * Mengakses Dashboard khusus berisi: pendapatan bersih, statistik sisa kuota tiket secara real-time, dan umpan balik ulasan pembeli.
   * Hanya diizinkan melihat dan memodifikasi data event/transaksi milik organisasinya sendiri.
3. **User (Buyer):**
   * Melihat daftar event terpilih yang sudah berstatus `'approved'` oleh Superadmin.
   * Melakukan transaksi tiket secara daring.

---

## 3. Perubahan Skema Database

Untuk mendukung arsitektur multi-tenant ini, struktur database yang sudah ada perlu diubah melalui SQL/Laravel Migration.

### 3.1 Tabel `users`
Kolom `role` dirubah tipe datanya agar menampung domain peran baru:

```php
Schema::table('users', function (Blueprint $table) {
    // Menambahkan role organizer dan mengubah default menjadi user. 
    // Di Laravel, pengubahan enum dapat menggunakan query mentah database atau doctrine/dbal.
    $table->enum('role', ['superadmin', 'organizer', 'user'])->default('user')->change();
});
```

### 3.2 Tabel `events`
Menambahkan kolom `organizer_id` sebagai Foreign Key yang merujuk ke tabel `users(id)` dan kolom `status` kelayakan event:

```php
Schema::table('events', function (Blueprint $table) {
    $table->foreignId('organizer_id')->nullable()->constrained('users')->cascadeOnDelete()->after('category_id');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('poster_path');
});
```

* *Note:* Data event yang sudah ada sebelum migrasi (legacy events) secara default akan diatur `organizer_id` ke user Superadmin pertama (atau bernilai null) untuk mencegah kegagalan constraint database.

---

## 4. Spesifikasi Fungsional & Dashboarding

### 4.1 Dashboard Organizer (HIMA/Kepanitiaan)
Ketika user dengan `role = 'organizer'` masuk ke panel admin `/admin/dashboard`, dashboard akan secara khusus menyaring data penjualan tiket milik organizer bersangkutan.

* **Metrik Utama (Real-time Analytics):**
  * **Total Pendapatan:** Diambil dari penjumlahan `transactions.total_price` yang memiliki status `'Success'` dan terelasikan dengan event milik `organizer_id` yang sedang masuk login.
  * **Total Tiket Terjual:** Jumlah transaksi sukses untuk event buatan organizer bersangkutan.
  * **Sisa Stok Tiket:** Indikator visual real-time sisa kuota tiket dari masing-masing event (Stock vs kuota awal).
* **Isolasi Data (Data Multi-tenancy Isolation):**
  * Query SQL untuk mengambil event wajib difilter berdasarkan id pengguna aktif:
    ```php
    // EventController.php (Admin Area)
    $myEvents = Event::where('organizer_id', Auth::id())->get();
    ```
  * Jika user mengakses rute edit `/admin/events/{event}/edit` milik organizer lain, sistem harus menolak dengan respon 403 / ModelNotFoundException.

### 4.2 Dashboard Superadmin
* **Antrean Kelayakan (Event Approval Feed):**
  * Menyajikan daftar event yang diajukan oleh HIMA dengan status `'pending'`.
  * Tombol aksi:
    * **Approve:** Mengubah status event menjadi `'approved'`. Sistem akan mempublikasikan event tersebut ke halaman homepage sehingga bisa dibeli secara instan.
    * **Reject:** Mengubah status event menjadi `'rejected'`. Penyelenggara mendapatkan notifikasi penolakan dan dapat mengajukan revisi data acara.
* **Analitik Global:**
  * Menampilkan total akumulasi pendapatan kotor platform secara menyeluruh dari seluruh transaksi berstatus `'Success'`.
  * Menampilkan jumlah organizer aktif dan statistik event terpopuler.

---

## 5. Tata Kelola Visibilitas Event (Homepage & Search Rules)
* Event yang berhak tampil di halaman depan publik (`HomeController@index` dan `/events`) wajib memenuhi kondisi:
  1. `status` event bernilai `'approved'`.
  2. Tanggal event belum terlampaui (`date` >= `now()`).
  3. Sisa stok tiket tersedia (`stock` > 0), jika stok habis tetap ditampilkan namun tombol beli berstatus "Habis Terjual".

---

## 6. Kriteria Keberhasilan & Skenario Pengujian (Testing Scenario)
* **Pengujian Dashboard Isolation:** Login sebagai Organizer A. Masuk ke halaman penjualan transaksi. Pastikan tidak ada data transaksi dari event Organizer B yang bocor ke dashboard Organizer A.
* **Pengujian Approval Flow:** 
  1. Login sebagai Organizer A, buat event baru bertajuk "Malam Keakraban HIMA". Pastikan status awal event di database bernilai `'pending'`.
  2. Buka homepage publik. Event "Malam Keakraban HIMA" tidak boleh muncul.
  3. Login sebagai Superadmin. Masuk ke panel approval, lakukan verifikasi dan klik **"Approve"**.
  4. Buka kembali homepage umum. Event tersebut sekarang sukses ditayangkan dan dapat dipesan tiketnya.
