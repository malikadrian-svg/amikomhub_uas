# Product Requirement Document (PRD) - Bypass Transaksi Acara Gratis (Free Events)
## Fitur: Alur Pendaftaran & Penerbitan E-Ticket Langsung Tanpa Payment Gateway (Bypass Midtrans) untuk Event Rp 0

---

## 1. Deskripsi Fitur
Untuk meningkatkan pengalaman pengguna dan menghemat sumber daya sistem, platform AmikomHub memerlukan alur penanganan khusus untuk acara gratis (*free events*) yang memiliki harga tiket Rp 0. 

Sistem akan melewati (*bypass*) proses integrasi dengan payment gateway (Midtrans) ketika nominal total pembayaran bernilai Rp 0. Transaksi akan langsung diproses sebagai transaksi sukses, tiket elektronik (E-Ticket) dengan kode QR langsung diterbitkan, stok tiket event dikurangi saat itu juga, dan pengguna segera dialihkan ke halaman transaksi sukses.

---

## 2. Aturan Bisnis (Business Rules)

1. **Pemicu Bypass (Trigger Condition):**
   * Logika bypass aktif jika dan hanya jika total harga pembelian tiket (`total_price`) setelah dikalkulasi (termasuk penerapan kupon/voucher diskon jika ada) bernilai tepat **Rp 0** (gratis).
2. **Status Transaksi Instan (Immediate Success State):**
   * Transaksi tidak boleh masuk ke status `Pending`. Status transaksi langsung disimpan sebagai `'Success'` di database.
3. **Pengurangan Stok Tiket (Stock Reservation):**
   * Sistem harus langsung mengurangi stok tiket event (`events.stock`) secara real-time pada saat transaksi bypass dilakukan.
   * Transaksi harus dicegah (dibatalkan) jika kuota stok tiket event sudah habis (`events.stock <= 0`).
4. **Penerbitan E-Ticket & Notifikasi:**
   * Setelah transaksi sukses disimpan, sistem secara otomatis memicu job backend (antrean) untuk menghasilkan E-Ticket (berisi QR Code unik) dan mengirimkannya via email kepada pembeli.
5. **Midtrans Token Bypass:**
   * Kolom `transactions.snap_token` diisi dengan nilai statis atau penanda khusus (seperti `'FREE_BYPASS'`) untuk menunjukkan bahwa transaksi ini diselesaikan tanpa melalui Midtrans.

---

## 3. Desain Skema Database

Fitur ini memanfaatkan tabel `events` dan `transactions` yang sudah ada tanpa melakukan perubahan struktur tabel (no schema migration needed).

### Data Kolom Tabel `transactions` yang Berdampak:
* `event_id`: Merujuk ke ID event yang berharga Rp 0 atau yang total pembelajarannya menjadi Rp 0.
* `total_price`: Disimpan dengan nilai `0`.
* `status`: Disimpan langsung dengan nilai `'Success'`.
* `snap_token`: Disimpan dengan nilai `NULL` atau `'FREE_BYPASS'`.

---

## 4. Alir Backend & Logika Validasi (Backend Logic & Implementation Step-by-Step)

### 4.1 Registrasi Rute di `routes/web.php`
Menyediakan endpoint khusus untuk memproses checkout transaksi gratis secara aman:
```php
Route::post('/checkout/process-free', [CheckoutController::class, 'processFreeTransaction'])->name('checkout.process_free');
```

### 4.2 Alur Logika Controller (`CheckoutController.php`)
Ketika user mengajukan pemesanan tiket gratis:
1. **Validasi Awal:** Pastikan event masih aktif, tanggal pelaksanaan belum terlewati, dan stok tiket masih tersedia (`stock > 0`).
2. **Validasi Harga:** Cek apakah harga tiket event bersangkutan memang Rp 0 (atau total belanja setelah kupon diskon bernilai Rp 0). Jika total harga > Rp 0, tolak request ini dan arahkan ke alur Midtrans biasa.
3. **Database Transaction & Lock (Mencegah Race Condition):**
   * Gunakan database transaction (`DB::transaction`) dan fitur lock (`sharedLock` atau `lockForUpdate`) saat memeriksa dan mengurangi stok event untuk menghindari *overselling*.
   ```php
   DB::transaction(function () use ($request, $event) {
       // Lock data event untuk update stok
       $activeEvent = Event::where('id', $event->id)->lockForUpdate()->first();
       
       if ($activeEvent->stock <= 0) {
           throw new \Exception('Tiket event ini sudah habis terjual.');
       }
       
       // Kurangi stok tiket
       $activeEvent->decrement('stock');
       
       // Buat data transaksi sukses secara langsung
       $transaction = Transaction::create([
           'event_id' => $activeEvent->id,
           'order_id' => 'FREE-' . time() . '-' . rand(1000, 9999),
           'customer_name' => $request->customer_name,
           'customer_email' => $request->customer_email,
           'customer_phone' => $request->customer_phone,
           'total_price' => 0,
           'status' => 'Success',
           'snap_token' => 'FREE_BYPASS'
       ]);
       
       // Picu Job Pengiriman E-Ticket
       dispatch(new SendTicketEmailJob($transaction));
   });
   ```

---

## 5. Persyaratan UI/UX (Mengikuti design.md)

### 5.1 Halaman Checkout Event (`/checkout/{event}`)
* **Pendeteksian Harga Gratis:**
  * Jika total tagihan adalah Rp 0, area instruksi pembayaran payment gateway disembunyikan.
  * Form data pembeli (Nama, Email, No. HP) tetap ditampilkan dan wajib diisi oleh pengguna.
* **Tombol Aksi Utama:**
  * Tombol pembayaran Midtrans diganti dengan tombol bertuliskan **"Dapatkan Tiket Gratis"** atau **"Konfirmasi Pendaftaran"**.
  * Visual tombol: Solid violet (`violet-600`, hover `violet-700`, transisi `150ms`) dengan ikon tiket atau tanda centang halus di sebelah teks.
  * Menampilkan teks catatan kecil di bawah tombol: *"Pendaftaran Anda gratis. Tiket akan dikirimkan langsung ke email Anda setelah konfirmasi."* dengan ukuran `Body XS` (`neutral-500`).

### 5.2 Pengalihan Halaman Sukses
* Setelah backend merespon sukses, pengguna langsung dialihkan ke Halaman Sukses Transaksi (`/checkout/success/{order_id}`).
* Halaman ini menampilkan visualisasi sukses dengan ilustrasi centang besar berwarna hijau `emerald-500` dan teks konfirmasi: *"Pendaftaran Berhasil! Tiket Anda telah dikirimkan ke email [email_pengguna]."*

---

## 6. Rencana Pengujian (Testing Plan)

* **Pengujian Integrasi Transaksi Rp 0 (Bypass):**
  * Pilih event gratis (harga Rp 0), isi formulir checkout, lalu klik tombol **"Dapatkan Tiket Gratis"**. Pastikan sistem langsung meredirect ke halaman sukses tanpa memunculkan popup/iframe Midtrans.
  * Periksa tabel `transactions` di database untuk memastikan status transaksi tersebut adalah `'Success'` dan `snap_token` bernilai `'FREE_BYPASS'`.
* **Pengujian Pengurangan Stok Secara Real-Time:**
  * Buat event gratis dengan stok awal `5` unit. Lakukan pendaftaran tiket gratis. Pastikan stok event langsung berkurang menjadi `4`.
* **Pengujian Pencegahan Overselling (Race Condition):**
  * Simulasikan 10 user melakukan checkout bersamaan pada event gratis yang stoknya hanya tersisa `2`. Pastikan hanya 2 transaksi pertama yang berhasil dan 8 transaksi lainnya mendapatkan pesan error *"Tiket event ini sudah habis terjual"*.
* **Pengujian Penolakan Bypass untuk Event Berbayar:**
  * Coba kirim request POST secara manual (via Postman/cURL) ke endpoint `/checkout/process-free` untuk event dengan harga Rp 50.000. Pastikan backend menolak dan mengembalikan status error `400 Bad Request`.
