Konsep **"Search-First"** ini sebenarnya jauh lebih modern dan *user-friendly* untuk database besar (7 generasi). Ini mirip dengan pendekatan Google atau Spotify—daripada membiarkan user tersesat di peta yang luas, kita langsung tanya: **"Siapa yang sedang kamu cari?"**

Untuk user tua, ini sangat membantu karena mereka tidak perlu belajar cara navigasi *pohon*, mereka cukup mengetik nama yang mereka ingat.

Berikut adalah breakdown detail visual dan alur untuk konsep **"Family Search Engine"** ini:

---

## 1. Hero Section: The Search Experience
Fokus utama saat aplikasi dibuka adalah satu kolom input besar di tengah layar.

* **CTA (Call to Action):** Gunakan kalimat yang emosional namun jelas.
    * *Contoh:* "Temukan silsilah dan jejak langkah keluarga kita." atau "Siapa nama kakek buyutmu?"
* **Search Input:** * Ukuran *font* besar (minimal 18px-20px).
    * *Placeholder* yang interaktif: "Ketik nama lengkap atau nama panggilan..."
    * Ikon kaca pembesar yang kontras.
* **Running Text (The Inspiration Ticker):**
    Di bawah search bar, buat 2-3 baris teks berjalan horizontal dengan kecepatan berbeda (menggunakan nama-nama acak dari database).
    * *Visual:* Nama-nama tampil seperti "tag" atau "pill" kecil yang bergerak perlahan.
    * *Fungsi:* Memberi kesan bahwa database ini "hidup" dan kaya akan data, sekaligus memancing ingatan user tua ("Oh, ada nama Pakde Anwar, coba saya cari").

---

## 2. Real-time Suggestion (Dropdown Overlay)
Begitu user mulai mengetik minimal 2-3 karakter, muncul *overlay* hasil pencarian di bawah kolom input.

* **Komponen Result:**
    * **Avatar:** Foto profil bulat (kecil).
    * **Nama:** Nama Lengkap (Bold) + Nama Panggilan di dalam kurung.
    * **Konteks (Sub-text):** Ini penting untuk membedakan nama yang sama. Contoh: "Anak dari [Nama Ayah]" atau "Generasi ke-4".
* **Visual Feedback:** Baris yang dipilih berubah warna (hover state) agar user tahu mana yang akan diklik.

---

## 3. Visual Breakdown (Layout Markdown)

```markdown
+-------------------------------------------------------------+
|  [Logo Keluarga]                                  [Admin Login] |
|                                                                 |
|                                                                 |
|             "Temukan Kembali Akar Keluarga Kita"                |
|         __________________________________________              |
|        | [icon] Cari nama kakek, nenek, atau sepupu... | [CARI] |
|         ‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾             |
|                                                                 |
|   <<<< [Nama A]   [Nama B]   [Nama C]   [Nama D] <<<< (Running) |
|   >>>> [Nama E]   [Nama F]   [Nama G]   [Nama H] >>>> (Text)    |
|                                                                 |
|-----------------------------------------------------------------|
|  HASIL PENCARIAN (Muncul saat mengetik):                        |
|  +-----------------------------------------------------------+  |
|  | [Foto] Eka Fetra (Eka) - Anak dari Budi Santoso           |  |
|  | [Foto] Eka Wijaya (Wijaya) - Anak dari Bambang            |  |
|  +-----------------------------------------------------------+  |
|                                                                 |
+-------------------------------------------------------------+
```

---

## 4. Keunggulan untuk User Tua (Lansia)
* **Minimalis:** Tidak ada gangguan visual dari garis-garis pohon yang rumit.
* **Familiar:** Mirip dengan cara mereka mencari kontak di WhatsApp atau mencari video di YouTube.
* **Feedback Instan:** Running text membantu mereka "teringat" nama-nama yang mungkin sudah lupa ejaannya.

---
