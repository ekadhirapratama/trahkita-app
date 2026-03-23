# Dokumentasi Perencanaan Aplikasi Silsilah Keluarga

## 1. Ringkasan Proyek
Aplikasi ini bertujuan untuk mendokumentasikan dan menampilkan silsilah keluarga hingga 7+ generasi. Fokus utama adalah kemudahan akses bagi pengguna lansia (tanpa login) namun tetap menjaga integritas data melalui sistem moderasi.

---

## 2. Struktur Halaman & Fitur
Menggunakan pendekatan Mobile Layout First untuk memaksimalkan penggunaan layar HP.

### A. Halaman Utama 
Halaman utama menerapkan konsep "Search-First" yang sederhana dan ramah lansia:
* **Fokus Pencarian:** Kolom input besar di tengah layar sebagai fokus utama (placeholder: "Ketik nama lengkap atau nama panggilan...").
* **Dynamic CTA:** CTA yang bisa berubah2 setiap berapa detik menggunakan animasi typing.
* **Saran Real-time:** Dropdown overlay saat mengetik (2–3 karakter) menampilkan avatar, nama lengkap + nama panggilan, dan konteks singkat (mis. "Anak dari ..." atau "Generasi ke-4").
* **Feedback Visual:** Baris hasil berubah warna saat hover/selected untuk memudahkan interaksi.
* **Running Text:** Baris nama berjalan di bawah search untuk memberi kesan "database hidup" dan memancing ingatan pengguna.
* **Mobile & Lansia-Friendly:** Font besar, kontras tinggi, dan desain minimal agar mudah digunakan di ponsel tanpa navigasi yang rumit.


### B. Halaman/Modal Profil Anggota
Detail informasi individu saat nama diklik.
* **Identitas Utama:** Foto profil, Nama Lengkap, Nama Panggilan, Anak dari siapa.
* **Informasi Detail:** Tempat tinggal saat ini, Pekerjaan/Instansi.
* **Hubungan Keluarga:** Daftar pasangan dan daftar anak (setiap nama anak bisa diklik untuk pindah profil).
* **Tombol Interaksi:**
    * "Hubungi via WhatsApp" (Jika ada data nomor).
    * "Ajukan Perubahan/Koreksi" (Memicu form kontributor).

### C. Halaman Form Kontribusi (Public/Contributor)
Halaman sederhana untuk input data tanpa harus memiliki akun.
* **Form Input:** Nama, Hubungan (Anak dari siapa), Foto, Alamat, Pekerjaan.
* **Field Catatan:** Alasan perubahan atau informasi tambahan untuk admin.
* **Status Tracking:** Informasi bahwa "Data Anda akan ditinjau oleh Admin sebelum tampil".

### D. Halaman Admin Dashboard (Login Required)
Halaman khusus pengelola data.
* **Approval Queue:** Daftar pengajuan perubahan/penambahan data dari user publik.
* **Data Management:** CRUD (*Create, Read, Update, Delete*) penuh untuk semua anggota keluarga.
* **Access Management:** Mengatur siapa yang bisa menjadi Editor tambahan.
* **Activity Log:** Catatan siapa mengubah apa untuk menghindari vandalisme data.

---

## 3. Matriks Akses Pengguna

| Fitur | Public (Via Link) | Contributor (Suggest) | Admin/Editor |
| :--- | :---: | :---: | :---: |
| Melihat Halaman Utama | ✅ | ✅ | ✅ |
| Cari Nama Anggota | ✅ | ✅ | ✅ |
| Detail Alamat & Kerja | ⚠️ (Terbatas) | ✅ | ✅ |
| Mengajukan Koreksi Data | ❌ | ✅ | ✅ |
| Approve/Reject Data | ❌ | ❌ | ✅ |
| Hapus Anggota Keluarga | ❌ | ❌ | ✅ |

---

## 4. User Flow

### Flow A: Akses Publik (Lansia/Keluarga Umum)
> **Goal:** Melihat data tanpa ribet.
1. User menerima **Link Khusus/Scan QR** dari WhatsApp.
2. User langsung masuk ke **Halaman Utama** (Tanpa Login).
3. User melakukan *scrolling* atau menggunakan **Search** untuk mencari nama.
4. User klik nama untuk melihat foto dan siapa saja anaknya.

### Flow B: Pengajuan Perubahan (Crowdsourcing)
> **Goal:** Memperbarui data tanpa akses admin.
1. User (Public) melihat data yang salah/kurang.
2. User klik tombol **"Koreksi Data"** di profil tersebut.
3. User mengisi form perubahan dan mengunggah foto baru.
4. User klik **"Kirim"**.
5. Sistem memberi notifikasi: "Terima kasih, perubahan sedang ditinjau kordinator keluarga".

### Flow C: Moderasi Admin
> **Goal:** Menjaga validitas data.
1. Admin menerima notifikasi ada pengajuan data baru.
2. Admin masuk ke **Dashboard Admin** (Login).
3. Admin membandingkan data lama vs data usulan.
4. Admin klik **"Setujui"**.
5. Data di Pohon Silsilah otomatis terupdate secara real-time.

---

## 5. Catatan Khusus untuk UI/UX Designer
1.  **Readability:** Gunakan font yang besar dan kontras tinggi (Lansia friendly).
2.  **Mobile First:** Karena akses utama kemungkinan besar via WhatsApp, pastikan tampilan pohon nyaman di layar HP.
3.  **Privacy Shield:** Data sensitif (seperti nomor HP atau alamat detail) tidak boleh muncul di akses publik tanpa login, atau berikan sensor minimal.
4.  **Loading State:** Karena silsilah 7 generasi bisa berisi ratusan orang, gunakan *lazy loading* agar aplikasi tidak berat saat dibuka.

---

## 6. Meta Data
* **Author:** Eka Pratama
* **App Name:** TrahKita
* **Description:** Aplikasi Silsilah Keluarga: Cari tahu siapa kakek buyutmu atau sepupu jauhmu hanya dengan mengetik nama. Mudah digunakan untuk semua usia.
* **List CTA:** 
- "Cari nama anggota keluarga yang ingin Bapak/Ibu temukan..."
- "Temukan silsilah dan jejak langkah keluarga kita"
- "Ingat nama panggilannya? Coba ketik di sini."
- "Siapa nama kakek buyutmu?"
- "Sudah kenal dengan sedulur dari Generasi ke-4?"

## 7. Technical Requirement
* **Framework:** Laravel
* **Database:** MySQL
* **Hosting:** Hostinger
* **PHP Version for deployment:** PHP 7.3
* **PHP Version for development:** PHP 7.4