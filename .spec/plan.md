# Implementation Plan — TrahKita
**Versi:** 1.1  
**Tanggal:** 23 Maret 2026  
**Author:** Engineering Manager  
**Status:** Final — Siap Development

---

## Keputusan Teknis yang Sudah Dikonfirmasi

Berikut ringkasan keputusan yang sudah disepakati dan menjadi acuan seluruh dokumen ini:

| # | Topik | Keputusan |
|---|---|---|
| 1 | Data awal | Tidak ada import; semua entry manual oleh Admin |
| 2 | Skala data | ~100 anggota — tidak butuh infrastruktur kompleks |
| 3 | Avatar | Gunakan inisial nama sebagai placeholder jika foto belum ada |
| 4 | Status meninggal | Tidak ditampilkan berbeda di fase ini |
| 5 | Akses publik | Satu link global untuk semua orang |
| 6 | Contributor | Siapa saja bisa submit tanpa daftar; identitas (nama/email/HP) bersifat opsional |
| 7 | Sistem Admin | Tidak ada role — semua admin setara; akun dibuat dengan username + password otomatis + copy-to-clipboard |
| 8 | Notifikasi | Tidak ada notifikasi di fase ini |
| 9 | Domain | Sudah tersedia |
| 10 | Foto/Storage | Foto di-*resize* otomatis saat upload; tidak ada batasan storage |
| 11 | Export/Import data | Tidak diperlukan |

---

## 1. Gambaran Besar Proyek

**TrahKita** adalah aplikasi web silsilah keluarga berbasis Laravel yang bisa diakses tanpa login. Konsep utamanya adalah **"Family Search Engine"** — pengguna cukup ketik nama untuk menemukan anggota keluarga hingga 7+ generasi.

### Siapa penggunanya?
| Tipe Pengguna | Cara Akses | Yang Bisa Dilakukan |
|---|---|---|
| **Publik / Lansia** | Via link/QR Code | Cari nama, lihat profil (data terbatas) |
| **Contributor** | Via link yang sama | Semua yang Publik bisa + ajukan koreksi data |
| **Admin / Editor** | Login khusus | Kelola semua data, approve/reject pengajuan |

---

## 2. Tech Stack

| Komponen | Pilihan | Catatan |
|---|---|---|
| **Framework** | Laravel | Gunakan versi yang kompatibel dengan PHP 7.4 (Laravel 8.x) |
| **PHP** | 7.4 (dev) / 7.3 (prod) | Hindari sintaks PHP 8.x (named arguments, union types, dll.) |
| **Database** | MySQL | Versi 5.7+ (cek versi Hostinger) |
| **Frontend** | Blade + Alpine.js | Alpine.js untuk interaksi ringan (dropdown search, dll.) |
| **CSS** | Tailwind CSS | Utility-first, cocok untuk Mobile First + theming lansia-friendly |
| **Storage** | Local disk (Hostinger) | Untuk foto profil; pertimbangkan *compress* sebelum simpan |
| **Auth** | Laravel Breeze / manual | Hanya untuk Admin; publik tidak perlu login |

> **Catatan untuk Junior Dev:** Kenapa Alpine.js bukan Vue/React? Karena kebutuhan interaktivitas di proyek ini masih ringan (dropdown, modal). Alpine.js jauh lebih sederhana dan tidak butuh proses *build* yang rumit di shared hosting.

---

## 3. Desain Database

Berikut adalah tabel-tabel utama yang perlu dibuat. Ini adalah "tulang punggung" aplikasi.

### Tabel `members` (Anggota Keluarga)
```sql
- id                  (INT, PK, auto increment)
- full_name           (VARCHAR 255) -- Nama lengkap
- nickname            (VARCHAR 100) -- Nama panggilan
- gender              (ENUM: 'male', 'female')
- birth_date          (DATE, nullable)
- birth_place         (VARCHAR 100, nullable)
- current_address     (TEXT, nullable) -- Tersembunyi untuk publik
- occupation          (VARCHAR 255, nullable) -- Tersembunyi untuk publik
- phone_number        (VARCHAR 20, nullable) -- Tersembunyi untuk publik
- photo_path          (VARCHAR 255, nullable) -- Path foto di storage; NULL = tampilkan avatar inisial
- father_id           (INT, FK → members.id, nullable)
- mother_id           (INT, FK → members.id, nullable)
- generation          (INT) -- Generasi ke berapa (dihitung otomatis)
- is_active           (BOOLEAN, default: true)
- created_at / updated_at
```
> **Catatan:** Kolom `death_date` tidak disertakan di fase ini karena belum ada kebutuhan menampilkan status meninggal. Bisa ditambahkan di fase berikutnya jika diperlukan.

### Tabel `marriages` (Data Pernikahan)
```sql
- id
- husband_id          (INT, FK → members.id)
- wife_id             (INT, FK → members.id)
- marriage_date       (DATE, nullable)
- is_active           (BOOLEAN) -- Untuk kasus cerai/meninggal
```
> **Kenapa tabel terpisah?** Karena satu orang bisa memiliki lebih dari satu pasangan.

### Tabel `submissions` (Pengajuan Perubahan dari Contributor)
```sql
- id
- target_member_id    (INT, FK → members.id, nullable) -- NULL jika tambah anggota baru
- submission_type     (ENUM: 'add', 'update')
- submitted_data      (JSON) -- Data yang diusulkan
- reason              (TEXT) -- Alasan perubahan
- submitter_name      (VARCHAR 255, nullable) -- Opsional; bisa anonim
- submitter_email     (VARCHAR 255, nullable) -- Opsional; untuk keperluan trace
- submitter_phone     (VARCHAR 20, nullable) -- Opsional; untuk keperluan trace
- photo_path          (VARCHAR 255, nullable)
- status              (ENUM: 'pending', 'approved', 'rejected')
- reviewed_by         (INT, FK → users.id, nullable)
- reviewed_at         (TIMESTAMP, nullable)
- created_at / updated_at
```
> **Catatan:** Identitas pengirim sepenuhnya opsional. Minimal satu field kontak dianjurkan agar bisa di-follow up, tapi form tetap bisa disubmit tanpa mengisi ketiganya.

### Tabel `users` (Admin)
```sql
- id
- name                (VARCHAR 100) -- Username untuk login
- password            (VARCHAR 255) -- Di-hash; di-generate otomatis saat akun dibuat
- created_at / updated_at
```
> **Catatan:** Tidak ada sistem role — semua akun admin memiliki hak akses yang sama. Tidak menggunakan email untuk login; cukup username + password. Password di-generate otomatis oleh sistem dan ditampilkan sekali dengan tombol *copy-to-clipboard* untuk diberikan ke admin baru.

### Tabel `activity_logs` (Log Aktivitas)
```sql
- id
- user_id             (INT, FK → users.id)
- action              (VARCHAR 255) -- "approved submission #12", "deleted member #5"
- target_type         (VARCHAR 50) -- "member", "submission"
- target_id           (INT)
- created_at
```

---

## 4. Struktur Folder Project (Laravel)

```
app/
  Http/
    Controllers/
      PublicController.php       ← Halaman utama & pencarian
      ProfileController.php      ← Detail profil anggota
      SubmissionController.php   ← Form kontribusi publik
      Admin/
        DashboardController.php
        MemberController.php     ← CRUD anggota
        SubmissionController.php ← Approve/reject
        UserController.php       ← Kelola akun admin (buat + hapus)
    Middleware/
      AdminOnly.php              ← Guard akses admin
  Models/
    Member.php
    Marriage.php
    Submission.php
    User.php
    ActivityLog.php
  Services/
    SearchService.php            ← Logika pencarian + ranking
    GenerationService.php        ← Hitung generasi otomatis

resources/
  views/
    layouts/
      app.blade.php              ← Layout publik
      admin.blade.php            ← Layout admin
    public/
      index.blade.php            ← Halaman utama (search)
      profile.blade.php          ← Profil anggota
      submission.blade.php       ← Form kontribusi
    admin/
      dashboard.blade.php
      members/                   ← CRUD views
      submissions/               ← Approval views

routes/
  web.php                        ← Route publik
  admin.php                      ← Route admin (prefix: /admin)
```

---

## 5. Rencana Development (Fase per Fase)

Development dibagi menjadi **4 fase** agar bisa di-*review* per bagian, tidak langsung kerjakan semua.

---

### FASE 1 — Fondasi & Setup (Estimasi: 3–5 hari)
> Tujuan: Project bisa jalan di local, database siap, dan Auth admin berfungsi.

**Task List:**
- [ ] Setup project Laravel 8.x baru
- [ ] Konfigurasi environment (`.env`): database, storage, APP_URL
- [ ] Buat semua migration (tabel `members`, `marriages`, `submissions`, `users`, `activity_logs`)
- [ ] Buat semua Model beserta relasi (`hasMany`, `belongsTo`, dll.)
- [ ] Setup *database seeder* dengan data dummy (min. 20 anggota, 2–3 generasi) untuk keperluan testing
- [ ] Setup Auth untuk Admin: login menggunakan **username + password** (bukan email)
  - Gunakan Laravel Breeze sebagai scaffolding awal, lalu sesuaikan form login-nya
- [ ] Buat Middleware `AdminOnly` untuk proteksi semua route `/admin`

**Output yang harus bisa dilihat di akhir fase ini:**
- Admin bisa login dan logout menggunakan username + password
- Route `/admin/dashboard` hanya terbuka jika sudah login; redirect ke halaman login jika belum
- Semua tabel sudah terbentuk di database

---

### FASE 2 — Fitur Publik (Estimasi: 5–7 hari)
> Tujuan: Halaman utama bisa digunakan; pengguna bisa cari dan lihat profil.

**Task List:**
- [ ] Buat halaman utama (`/`) dengan layout Search-First
  - Input pencarian besar di tengah
  - Running text nama-nama anggota (ambil acak dari database)
  - Animasi *typing* placeholder CTA (gunakan Alpine.js) — teks CTA sesuai daftar di PRD
- [ ] Buat API endpoint `GET /api/search?q={nama}` untuk real-time suggestion
  - Query ke kolom `full_name` dan `nickname`
  - Return: avatar (inisial nama jika tidak ada foto), nama lengkap, nama panggilan, konteks ("Anak dari ..." atau "Generasi ke-N")
  - Minimal 2 karakter untuk trigger pencarian
- [ ] Tampilkan dropdown suggestion saat user mengetik (Alpine.js + Fetch API)
- [ ] Buat komponen **Avatar Inisial**: jika `photo_path` kosong, tampilkan lingkaran berwarna berisi 1–2 huruf inisial nama. Warna background bisa di-*generate* dari hash nama agar konsisten.
- [ ] Buat halaman profil anggota (`/member/{id}`)
  - Tampilkan: avatar/foto, nama lengkap, nama panggilan, "Anak dari [nama ayah] & [nama ibu]"
  - Data sensitif (alamat, pekerjaan, HP) **tidak ditampilkan** di halaman publik
  - Daftar pasangan (nama bisa diklik → pindah ke profil pasangan)
  - Daftar anak (nama bisa diklik → pindah ke profil anak)
  - Tombol **"Ajukan Perubahan"** di bagian bawah profil

**Output yang harus bisa dilihat di akhir fase ini:**
- Buka halaman utama, ketik nama, muncul dropdown suggestion dengan avatar inisial
- Klik nama di suggestion, masuk ke halaman profil
- Di profil, klik nama anak, berpindah ke profil anak tersebut

---

### FASE 3 — Fitur Kontribusi & Admin (Estimasi: 7–10 hari)
> Tujuan: Contributor bisa ajukan perubahan; Admin bisa review, kelola data, dan kelola akun admin lain.

**Task List (Form Kontribusi — Halaman Publik):**
- [ ] Buat halaman form kontribusi, dapat diakses dari tombol "Ajukan Perubahan" di profil
  - Field wajib: jenis perubahan (tambah anggota baru / koreksi data), data yang diusulkan
  - Field opsional identitas pengirim: Nama, Email, *atau* Nomor HP — minimal isi salah satu, tapi boleh lewati semuanya (anonim)
  - Field: Alasan perubahan / catatan untuk admin
  - Field: Upload foto (opsional)
  - Validasi: jika ada foto, harus format image; ukuran max 2MB
  - Setelah submit: tampilkan pesan "Terima kasih, perubahan Anda sedang ditinjau koordinator keluarga"
- [ ] Simpan pengajuan ke tabel `submissions` dengan status `pending`

**Task List (Admin Dashboard):**
- [ ] Halaman Dashboard: tampilkan statistik ringkas
  - Total anggota keluarga
  - Jumlah pengajuan menunggu review (*pending*)
  - Aksi terakhir (dari activity log)
- [ ] Fitur CRUD Anggota (`/admin/members`):
  - List semua anggota dengan fitur pencarian nama
  - Tambah anggota baru (form lengkap: nama, nama panggilan, gender, tgl lahir, tempat lahir, ayah, ibu, alamat, pekerjaan, nomor HP, upload foto)
  - Edit data anggota yang sudah ada
  - Hapus anggota (tampilkan dialog konfirmasi sebelum menghapus)
- [ ] Fitur Approval Pengajuan (`/admin/submissions`):
  - List semua pengajuan dengan filter status (pending / approved / rejected)
  - Halaman detail pengajuan: tampilkan data lama vs data yang diusulkan secara berdampingan
  - Tombol **"Setujui"**: otomatis update data anggota terkait dan ubah status jadi `approved`
  - Tombol **"Tolak"**: ubah status jadi `rejected`
- [ ] Fitur Kelola Akun Admin (`/admin/users`):
  - List semua akun admin yang terdaftar
  - Tambah admin baru: Admin mengisi **username** saja → sistem men-*generate* **password acak** (misal: 12 karakter alfanumerik)
  - Setelah akun dibuat: tampilkan password sekali di halaman dengan tombol **"Salin ke Clipboard"** agar bisa langsung dibagikan
  - Hapus akun admin (tidak bisa hapus akun yang sedang digunakan untuk login)
- [ ] Activity Log: setiap aksi admin (approve/reject/hapus/tambah) dicatat otomatis ke tabel `activity_logs`

**Output yang harus bisa dilihat di akhir fase ini:**
- Contributor isi form (bisa anonim) → data masuk ke antrian Admin
- Admin login, lihat dashboard, lihat pengajuan baru, klik Setujui → data anggota terupdate
- Admin bisa membuat akun admin baru, menyalin password, dan menghapus akun

---

### FASE 4 — Polish, Testing & Deployment (Estimasi: 3–5 hari)
> Tujuan: Aplikasi siap dipakai, tidak ada data sensitif bocor, performa oke.

**Task List:**
- [ ] **Security Audit sederhana:**
  - Pastikan data sensitif (alamat, HP, pekerjaan) tidak muncul di response JSON publik
  - Semua route `/admin/*` terlindungi Middleware
  - Input dari user selalu di-*validate* dan di-*sanitize* (cegah XSS & SQL Injection — Laravel sudah handle ini via Eloquent & Blade, tapi perlu double-check)
  - Upload foto hanya terima format image (jpg, png, webp), validasi MIME type dan ukuran max
- [ ] Optimasi performa pencarian: tambahkan *index* di kolom `full_name` dan `nickname`
- [ ] *Compress* foto saat upload (gunakan package Intervention Image)
- [ ] Uji coba tampilan di HP (Chrome DevTools Mobile View) — pastikan font besar, tombol mudah diklik
- [ ] Buat file `README` dan instruksi deployment ke Hostinger
- [ ] **Deployment ke Hostinger:**
  - Upload file project
  - Setup `.env` production
  - Jalankan `php artisan migrate --force`
  - Jalankan `php artisan config:cache` & `php artisan route:cache`

---

## 6. Aturan Keamanan & Privacy Penting

> Ini bukan opsional — harus diimplementasikan sejak awal.

1. **Data Sensitif Tersembunyi:** Kolom `current_address`, `occupation`, dan `phone_number` dari tabel `members` **tidak boleh** masuk ke response JSON untuk endpoint publik. Gunakan *API Resource* atau seleksi kolom eksplisit di query.

2. **Validasi Upload File:** Saat menerima foto dari contributor, validasi:
   - MIME type harus `image/jpeg`, `image/png`, atau `image/webp`
   - Ukuran maksimal (misal: 2MB)
   - Simpan dengan nama acak (*random filename*), jangan gunakan nama asli dari user

3. **CSRF Protection:** Laravel sudah handle ini secara default untuk semua form POST. Pastikan semua form pakai `@csrf`.

4. **Rate Limiting:** Tambahkan rate limiting di endpoint `/api/search` agar tidak bisa di-*abuse* untuk scraping data. Contoh: maksimal 60 request per menit per IP.

5. **Input Sanitization:** Semua input teks yang ditampilkan kembali ke layar harus melalui Blade `{{ }}` (bukan `{!! !!}`), kecuali ada alasan khusus.

---

## 7. API Endpoints yang Dibutuhkan

| Method | Endpoint | Akses | Deskripsi |
|---|---|---|---|
| GET | `/` | Publik | Halaman utama |
| GET | `/api/search?q={nama}` | Publik | Real-time search suggestion |
| GET | `/member/{id}` | Publik | Halaman profil anggota |
| GET | `/member/{id}/suggest` | Publik | Form kontribusi untuk anggota tertentu |
| POST | `/member/{id}/suggest` | Publik | Kirim pengajuan perubahan |
| GET | `/admin/dashboard` | Admin | Dashboard + statistik |
| GET | `/admin/members` | Admin | List semua anggota |
| GET | `/admin/members/create` | Admin | Form tambah anggota baru |
| POST | `/admin/members` | Admin | Simpan anggota baru |
| GET | `/admin/members/{id}/edit` | Admin | Form edit anggota |
| PUT | `/admin/members/{id}` | Admin | Update data anggota |
| DELETE | `/admin/members/{id}` | Admin | Hapus anggota |
| GET | `/admin/submissions` | Admin | List pengajuan (filter by status) |
| GET | `/admin/submissions/{id}` | Admin | Detail perbandingan pengajuan |
| POST | `/admin/submissions/{id}/approve` | Admin | Setujui pengajuan |
| POST | `/admin/submissions/{id}/reject` | Admin | Tolak pengajuan |
| GET | `/admin/users` | Admin | List semua akun admin |
| POST | `/admin/users` | Admin | Buat akun admin baru (generate password otomatis) |
| DELETE | `/admin/users/{id}` | Admin | Hapus akun admin |

---

## 8. Rekomendasi Package Laravel

| Package | Kegunaan | Wajib? |
|---|---|---|
| `intervention/image` | Resize & compress foto otomatis saat upload | Ya |
| `laravel/breeze` | Scaffolding Auth Admin (simple & ringan) | Ya |
| `laravel/telescope` | Debug tool saat development — **jangan deploy ke production!** | Dev only |

---

## 9. Definition of Done (DoD)

Setiap fitur dianggap **selesai** jika memenuhi kriteria berikut:
- [ ] Fungsional sesuai PRD
- [ ] Tidak ada data sensitif yang bocor ke akses publik
- [ ] Tampilan rapi di layar HP (min. lebar 360px)
- [ ] Tidak ada error di Laravel log
- [ ] Sudah di-*review* dan di-*approve* oleh Engineering Manager

---

*Dokumen ini sudah final berdasarkan hasil klarifikasi. Setiap perubahan keputusan teknis harus didiskusikan dengan Engineering Manager sebelum diimplementasikan.*
