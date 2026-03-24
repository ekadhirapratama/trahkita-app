# TrahKita — Family Search Engine

TrahKita adalah aplikasi web silsilah keluarga berbasis Laravel yang dapat diakses tanpa login (publik). Konsep utama aplikasi ini adalah sebagai **"Family Search Engine"** di mana pengguna cukup mengetik nama untuk menemukan anggota keluarga hingga beberapa generasi. 

Aplikasi ini juga menyediakan fitur kontribusi bagi pengguna, serta *dashboard* khusus bagi admin untuk mengelola anggota keluarga dan menyetujui pengajuan perubahan data.

## Spesifikasi Teknis (Tech Stack)

Aplikasi ini pada dasarnya dirancang menggunakan teknologi dengan kebutuhan minimal, sehingga ramah terhadap spesifikasi server dan shared hosting:

- **Framework**: Laravel 8.x
- **Bahasa**: PHP >= 7.3 / 7.4
- **Database**: MySQL (dapat disesuaikan dengan SQLite untuk development lokal)
- **Frontend**: Blade + Alpine.js
- **CSS**: Tailwind CSS
- **Authentication**: Laravel Breeze (Hanya untuk Admin)

## Tipe Pengguna & Hak Akses

1. **Publik / Lansia**
   - Mengakses lewat link (tanpa perlu mendaftar).
   - Mencari nama dan melihat profil beserta struktur keluarga inti dari orang yang dicari secara sederhana.

2. **Contributor (Publik)**
   - Semua akses seperti *Publik*.
   - Mengajukan penambahan anggota keluarga atau koreksi data ke admin. Identitas form bersifat opsional.

3. **Admin / Editor / Koordinator Keluarga**
   - Mengelola data anggota melalui *dashboard* setelah melewati otorisasi khusus (login via *username*).
   - Meninjau, menyetujui, atau menolak *submission* (perubahan) dari *Contributor*.
   - Manajemen akun administratif.

## Fitur Utama

- **Pencarian Publik Real-time**: Pencarian yang fleksibel menggunakan nama depan, nama lengkap, atau panggilan. Auto suggestion dan *Initial Avatar* tersedia.
- **Profil Struktur Silsilah**: Menampilkan ayah, ibu, saudara, dan detail pasangan (jika ada) yang dapat dinavigasi dengan mudah.
- **Sistem Moderasi Draft (Submissions)**: Data tidak serta merta berubah ketika publik melakukan penambahan/perubahan, melainkan disimpan dulu dalam bentuk draf menunggu persetujuan Admin.
- **Privasi Terjaga**: Nomor telepon, alamat lengkap, dan pekerjaan disembunyikan bagi pengunjung publik untuk menjaga keamanan informasi anggota keluarga.
- **Admin Panel Ringkas**: Desain panel ditekankan pada kemudahan interaksi. Akun admin baru dapat diterbitkan otomatis tanpa mengharuskan pengguna menyertakan alamat email.

## Cara Menjalankan Aplikasi di Lokal (Development)

Untuk instalasi dan pengembangan aplikasi secara lokal (tanpa data sensitif), pelajari instruksi berikut:

1. Prasyarat dasar:
   - Git dan Composer terpasang di komputer Anda.
   - Menggunakan minimal PHP 7.3/7.4.
   - Node.js & NPM terinstall.

2. **Instalasi:**
   Lakukan *install* module vendor dari PHP & JavaScript.
   ```bash
   composer install
   npm install
   npm run dev
   ```

3. **Konfigurasi Lingkungan (`.env`):**
   Ganti nama `.env.example` ke `.env` (atau buat file baru jika tidak ada) lalu atur `DB_CONNECTION`. Aturan credential database sepenuhnya dikelola secara mandiri oleh tim pengembang sesuai lingkungan server masing-masing.

4. **Siapkan Database:**
   Silakan jalankan perintah migrasi berikut pada direktori proyek:
   ```bash
   php artisan key:generate
   php artisan migrate
   ```
   > **Catatan:** Anda bisa memanfaatkan _seeder_ untuk sekadar mengisi data *dummy* menggunakan `php artisan db:seed`.

5. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Buka `http://localhost:8000` di peramban Anda. Aplikasi akan langsung tersedia untuk ditelusuri.

## Keamanan Data

Aplikasi ini menjunjung tinggi standar privasi. Keamanan yang telah diterapkan oleh sistem mencakup:
1. Tidak tereksposnya *Personally Identifiable Information* seperti alamat/telepon ke non-admin (hanya dapat diakses melalui view internal `/admin/...`).
2. Proses persetujuan (approval) perubahan dua tahap terhadap modifikasi basis data oleh pengguna publik.
3. Perlindungan rute Admin dengan Middleware `AdminOnly`.
4. Mencegah File-Upload vulnerability lewat limit ekstensi file (`image/*`).

## Git Flow & Deployment

Ringkasan singkat tentang alur kode dan langkah deploy tanpa menyertakan credential atau path server.

1. Branching model
   - `main` — development harian (tempat semua fitur dan perbaikan dikembangkan dan dites).
   - `deploy` — branch produksi yang dipantau oleh hosting untuk auto-deploy.

2. Aturan kerja
   - Kerjakan fitur di `main` (atau branch feature lokal), lakukan review dan testing.
   - Setelah siap, merge perubahan ke `deploy` untuk merilis ke production.

3. Langkah singkat deploy
   - Pastikan `main` up-to-date dan semua pengujian dasar lulus.
   - Build aset secara lokal: `npm install` lalu `npm run prod`.
   - Commit hasil build bila diperlukan lalu push ke `main`.
   - Merge `main` ke `deploy` dan push `deploy` ke remote: ini akan memicu auto-deploy di hosting.

4. Perintah git contoh (lokal)
```
git checkout main
git pull origin main
# build assets
npm run prod
git add -A
git commit -m "chore: build assets for release"
git push origin main

# release
git checkout deploy
git merge main
git push origin deploy
git checkout main
```
