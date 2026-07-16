# Product Requirement Document (PRD) - Fitur Ulasan dan Rating
## Fitur: Sistem Ulasan dan Penilaian Bintang (Rating & Review) Pasca-Event

---

## 1. Deskripsi Fitur
Menumbuhkan ekosistem pasca-event yang interaktif dan membangun kredibilitas penyelenggara (HIMA/Kepanitiaan). Fitur ini mewadahi pembeli tiket untuk memberikan ulasan tertulis dan penilaian bintang (skala 1-5) setelah acara rampung. Hasil akumulasi penilaian ini akan ditayangkan di Profil Penyelenggara.

---

## 2. Aturan Bisnis (Business Rules)
Untuk menjamin kredibilitas dan keaslian penilaian, aturan bisnis berikut ditetapkan:

1. **Kelayakan Pengulas (Eligible Buyers Only):**
   * Ulasan hanya dapat dikirimkan oleh pengguna terdaftar yang secara sah membeli tiket event bersangkutan dengan status transaksi `'Success'`.
   * Satu pengguna hanya dapat memberikan **maksimal satu (1) ulasan** per event.
2. **Jeda Waktu Penilaian (Post-Event Rating Delay):**
   * Formulir ulasan diaktifkan **sehari setelah acara berakhir** atau `event.date + 24 jam` (H+1).
   * Sebelum waktu tersebut tercapai, tombol ulasan akan dinonaktifkan (disabled) atau disembunyikan.
3. **Validasi Skala Bintang & Komentar:**
   * Rating wajib dipilih antara angka `1` hingga `5`.
   * Komentar bersifat opsional/wajib dengan batas maksimal 500 karakter untuk mencegah spamming teks.

---

## 3. Desain Skema Database
Sistem membutuhkan satu tabel baru yaitu `reviews`.

### Tabel `reviews`
```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    event_id BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    CONSTRAINT check_rating CHECK (rating >= 1 AND rating <= 5)
);
```

### Relasi Model Eloquent (Laravel):
* **Model `User`:**
  ```php
  public function reviews() {
      return $this->hasMany(Review::class);
  }
  ```
* **Model `Event`:**
  ```php
  public function reviews() {
      return $this->hasMany(Review::class);
  }
  
  // Helper memantau rata-rata rating
  public function getAverageRatingAttribute() {
      return round($this->reviews()->avg('rating'), 1) ?? 0;
  }
  ```
* **Model `Review`:**
  ```php
  class Review extends Model {
      protected $fillable = ['user_id', 'event_id', 'rating', 'comment'];
      
      public function user() {
          return $this->belongsTo(User::class);
      }
      public function event() {
          return $this->belongsTo(Event::class);
      }
  }
  ```

---

## 4. Alir Backend & Validasi (Backend Logic & Validation)
* **Logika Validasi Form Review:**
  * Controller `ReviewController` memvalidasi input rating minimal `1` maksimal `5` dengan custom validation rules di Laravel. 
  * Cek keberadaan pemesanan:
    ```php
    $hasPurchased = Transaction::where('customer_email', auth()->user()->email)
        ->where('event_id', $eventId)
        ->where('status', 'Success')
        ->exists();
    ```

---

## 5. Persyaratan UI/UX (Mengikuti design.md)

### 5.1 Halaman Tiket Pengguna (`/my-ticket`)
* Setiap kartu daftar tiket yang dibeli memiliki tombol aksi ulasan.
* **Kondisi Tombol:**
  * **Event belum mulai / belum H+1:** Tampilkan tombol **"Tulis Ulasan"** dengan style *disabled* (Background `neutral-100`, text `neutral-400`, cursor `not-allowed`). Tambahkan indikator tooltip: *"Ulasan dapat ditulis 1 hari setelah acara selesai"*.
  * **Event telah H+1:** Tombol berstatus aktif dengan style primary button (`violet-500`, hover `violet-600`, transisi `150ms`).
* **Dialog Modal Ulasan (Popup):**
  * Menggunakan modal layout tengah (`radius-2xl` - 24px, backdrop overlay `rgba(15, 23, 42, 0.3)`).
  * Input bintang interaktif: 5 ikon bintang outline (`1.75px` stroke). Hovering di atas bintang memberikan highlight warna emas (`#fbbf24`), dan klik akan mengunci pilihan rating.
  * Form textarea setinggi minimal `96px` (`space-24`), border `1px solid neutral-200` (`#e2e8f0`), focus state border `violet-400` dengan soft glow ring shadow.

### 5.2 Halaman Profil Publik Penyelenggara (HIMA/Kepanitiaan)
Halaman ini dapat diakses publik untuk melihat kredibilitas organizer.
* **Visual Rata-rata Rating:**
  * Angka display ukuran besar (Display size `40px` semibold, letter-spacing `-0.02em`) misalnya: `4.7` diikuti total ulasan *"Dari 142 ulasan pembeli"*.
  * Bar persentase distribusi rating (bintang 5 berapa persen, bintang 4 berapa persen) menggunakan utility progress bar (tinggi `4px`, fill `violet-500`, background `neutral-100`).
* **Daftar Testimoni (Review Feed):**
  * Layout grid atau list dengan jarak pemisah `space-6` (24px).
  * Setiap kartu testimonial memakai border `1px solid neutral-100` (`#f1f5f9`) and background `neutral-0` (`#ffffff`).
  * Informasi ulasan memuat: Nama Pengulas (disamarkan sebagian jika privasi, e.g. `"Rina A***"`), rating bintang yang diberikan, tanggal ulasan, dan komentar review menggunakan typography `Body` size (`14px` regular, color `neutral-800`).

---

## 6. Rencana Pengujian (Testing Plan)
* **Pengujian Hak Akses ulasan:** Coba panggil Endpoint POST ke `/reviews` tanpa membeli tiket. Sistem harus menolak dengan status HTTP 403 Forbidden.
* **Pengujian Waktu (Time Constraint):** Atur tanggal event di database menjadi hari ini (belum berakhir H+1). Akses halaman `my-ticket`, pastikan tombol ulasan terkunci. Ubah tanggal event menjadi 2 hari lalu di DB, pastikan tombol terbuka dan sukses submit ulasan.
* **Validasi Skema:** Coba kirim data rating bernilai `6` atau `0`. Database / Controller harus menghasilkan error validasi.
