# Panduan Pengguna CMS Admin (CMS User Guide)
## PT Derco Bara Energy & Anak Usaha

Dokumen ini adalah panduan operasional bagi administrator (**Super Admin** dan **Editor Konten**) untuk mengelola konten, media, produk, perizinan, dan pesan masuk pada situs web **PT Derco Bara Energy** dan ketiga anak usahanya.

---

## 1. Akses & Masuk ke Sistem (Login)

Buka peramban (*browser*) dan akses URL CMS:
```
https://derco-bara-energy.co.id/admin
```

```
+--------------------------------------------------------------+
|                    PT DERCO BARA ENERGY                      |
|                         PANEL ADMIN                          |
|                                                              |
|   Email         : [ admin@derco-bara-energy.co.id          ] |
|   Kata Sandi    : [ ******************                     ] |
|   [x] Ingat Saya                                             |
|                                                              |
|                   [   MASUK KE DASHBOARD   ]                 |
+--------------------------------------------------------------+
```

### Peran Pengguna (Roles):
- **Super Admin**: Memiliki akses penuh terhadap seluruh perusahaan, pengaturan umum sistem, manajemen pengguna CMS, serta penayangan status perusahaan (*Draft/Published*).
- **Editor**: Dapat mengedit teks halaman, menambah spesifikasi produk, mengunggah foto/sertifikat, dan merespons pesan masuk kontak.

---

## 2. Navigasi Panel Admin

Setelah berhasil masuk, Anda akan melihat tampilan dashboard dengan menu samping (*sidebar*):

```
+------------------+------------------------------------------------------+
| DERCO CMS        |  [ID | EN]  Pencarian Global        (Profil) [Keluar] |
+------------------+------------------------------------------------------+
| NAVIGASI UTAMA   |                                                      |
|                  |  Ringkasan Sistem                                    |
| [o] Dashboard    |  +------------------+ +------------------+           |
|                  |  | Total Perusahaan | | Pesan Baru       |           |
| KONTEN KORPORAT  |  | 4 (1 Draft)      | | 3 Belum Dibaca   |           |
| [ ] Perusahaan   |  +------------------+ +------------------+           |
| [ ] Halaman      |                                                      |
| [ ] Blok Halaman |  Aktivitas Terbaru                                   |
|                  |  - Form kontak masuk dari PT Kaltim Power Coal       |
| OPERASI & PRODUK |  - Pembaruan dokumen RKL-RPL Semester I              |
| [ ] Produk       |                                                      |
| [ ] Operasi      |                                                      |
| [ ] Sertifikasi  |                                                      |
|                  |                                                      |
| KOMUNIKASI       |                                                      |
| [ ] Pesan Masuk  |                                                      |
| [ ] Kontak Resmi |                                                      |
|                  |                                                      |
| PENGATURAN       |                                                      |
| [ ] Pengguna     |                                                      |
| [ ] Setelan SEO  |                                                      |
+------------------+------------------------------------------------------+
```

---

## 3. Mengedit Teks Dwibahasa (Indonesia & Inggris)

Sistem menggunakan standar antarmuka dwibahasa tab paralel. Setiap kolom teks menyediakan dua bahasa:

```
Judul Halaman:
+------------------------+------------------------+
| [x] Bahasa Indonesia   | [ ] English            |
+------------------------+------------------------+
| Energi & Logistik Pertambangan Terintegrasi     |
+-------------------------------------------------+
```
```
+------------------------+------------------------+
| [ ] Bahasa Indonesia   | [x] English            |
+------------------------+------------------------+
| Integrated Mining Energy & Logistics            |
+-------------------------------------------------+
```

> **Aturan Bahasa:**
> - Pastikan kedua tab (Indonesia dan English) terisi dengan baik.
> - Jika tab English dikosongkan, sistem web publik secara otomatis menggunakan teks Bahasa Indonesia sebagai *fallback* agar tampilan halaman tidak kosong.

---

## 4. Mengedit Halaman & Blok Konten (Page Blocks)

Situs dibangun menggunakan arsitektur blok modular. Setiap halaman terdiri dari susunan blok vertikal yang dapat disesuaikan urutannya.

### Langkah Mengedit Blok:
1. Buka menu **Halaman** di sidebar.
2. Pilih halaman yang ingin disunting (misal: *Beranda — PT Derco Bara Energy*).
3. Klik tombol **Ubah (Edit)**.
4. Gulir ke bagian **Daftar Blok Halaman**:

```
+--------------------------------------------------------------------+
| BLOK HALAMAN                                      [ + Tambah Blok ]|
+--------------------------------------------------------------------+
| [::] 1. Hero Banner                                   [Edit] [Hapus]
|      Tipe: hero | Status: Aktif | Urutan: 1                        |
|                                                                    |
| [::] 2. Ringkasan Bisnis Grup                         [Edit] [Hapus]
|      Tipe: subsidiary_grid | Status: Aktif | Urutan: 2             |
|                                                                    |
| [::] 3. Statistik Operasional                         [Edit] [Hapus]
|      Tipe: stats | Status: Aktif | Urutan: 3                       |
+--------------------------------------------------------------------+
```

### Mengubah Urutan Blok:
- Arahkan kursor pada ikon pegangan `[::]` di sisi kiri blok, lalu seret (*drag & drop*) ke posisi yang diinginkan.
- Atau ubah angka pada kolom `Urutan (Sort Order)` secara manual.

### Tipe Blok Utama yang Tersedia:
- **Hero**: Spanduk utama paling atas dengan judul, subjudul, tombol CTA, dan gambar latar belakang.
- **Split Content**: Tata letak dua kolom (teks penjelasan di satu sisi dan foto/media di sisi lain).
- **Subsidiary Grid**: Kartu pratinjau anak-anak usaha grup (otomatis hanya menampilkan perusahaan berstatus *Published*).
- **Stats**: Deretan angka capaian dan statistik operasional.
- **Values Grid**: Kotak tata nilai atau pilar keunggulan perusahaan.
- **Contact Info & Form**: Informasi kontak resmi dan formulir pengiriman pesan.

---

## 5. Mengunggah & Mengatur Media Gambar

1. Pada blok atau produk yang memiliki kolom **Unggah Gambar**, klik tombol **Pilih Berkas** atau seret berkas langsung ke area unggah.
2. **Kriteria Gambar yang Dianjurkan:**
   - Format: `.webp`, `.jpg`, atau `.png`.
   - Ukuran foto hero: minimal `1920 × 1080` piksel, rasio `16:9`.
   - Ukuran foto kartu: minimal `800 × 600` piksel, rasio `4:3`.
   - Ukuran berkas: di bawah 1,5 MB per gambar (sistem akan otomatis mengompresi gambar untuk performa web cepat).
3. **Penting — Teks Alternatif Gambar (*Alt Text*):**
   - Selalu isi teks alt dalam ID dan EN (misal: *Ekskavator hidrolik memuat batubara di pit tambang*).
   - Teks alt penting untuk keterbacaan pembaca tunanetra (*screen reader*) dan nilai SEO Google.

---

## 6. Mengelola Produk & Spesifikasi Batubara

Khusus untuk anak usaha dagang (**PT Korin Berkat Energy**) dan tambang (**PT Korin Bara Energy**):

1. Masuk ke menu **Produk**.
2. Klik tombol **Buat Produk Baru** atau pilih produk yang ada.
3. Masukkan:
   - Nama Produk (ID/EN)
   - Kategori & Ringkasan Singkat (ID/EN)
   - Foto Produk Batubara
4. Pada tabel **Spesifikasi Teknis Batubara**, klik **Tambah Baris Spesifikasi**:

```
+--------+--------------------+---------+--------+-----------+
| Kode   | Label Parameter    | Nilai   | Satuan | Highlight |
+--------+--------------------+---------+--------+-----------+
| GAR    | Gross Calorific    | 4.200   | kcal/kg|   [ x ]   |
| TM     | Total Moisture     | 34.0    | %      |   [ x ]   |
| ASH    | Ash Content        | 6.0     | %      |   [   ]   |
| TS     | Total Sulfur       | 0.2     | %      |   [ x ]   |
+--------+--------------------+---------+--------+-----------+
```
> Centang **Highlight** untuk parameter utama yang ingin ditampilkan menonjol di kartu pratinjau produk pada halaman publik.

---

## 7. Mengelola Sertifikat & Berkas PDF Legalitas

1. Buka menu **Sertifikasi**.
2. Klik **Tambah Sertifikat**.
3. Pilih Kategori:
   - `Izin Usaha` (*Permit*)
   - `Standar Manajemen` (*Quality/Safety Standard, mis. ISO*)
   - `Laporan Kepatuhan` (*Compliance Report, mis. RKL-RPL*)
4. Masukkan Nomor Sertifikat, Instansi Penerbit, Tanggal Terbit, dan Tanggal Kedaluwarsa.
5. Unggah berkas dokumen:
   - **Salinan PDF Bahasa Indonesia**
   - **Salinan PDF Bahasa Inggris** (jika ada dokumen terjemahan resmi)
6. Sertifikat yang kedaluwarsa akan ditandai dengan badge peringatan kuning di CMS dan secara otomatis diperbarui keterangannya di halaman publik.

---

## 8. Membaca & Membalas Pesan Kontak Masuk

Setiap pesan yang dikirim pengunjung melalui formulir kontak web publik akan:
1. Dikirim via email otomatis ke alamat tujuan perusahaan (`contact_default_email`).
2. Disimpan secara aman di database CMS admin.

### Melihat Pesan:
1. Buka menu **Pesan Masuk** di sidebar.
2. Pesan baru yang belum dibaca akan memiliki tanda indikator oranye.
3. Klik pada baris pesan untuk membuka detail lengkap:
   - Nama Pengirim & Nama Perusahaan Pengirim
   - Alamat Email & Nomor Telepon / WhatsApp
   - Subjek Permintaan (*Kemitraan, Pasokan Batubara, Logistik, Umum*)
   - Isi Pesan Lengkap
   - Waktu Pengiriman & Alamat IP
4. Ubah status pesan menjadi **Sudah Dibaca** atau **Sudah Ditindaklanjuti** untuk dokumentasi tim administrasi.

---

## 9. Menayangkan PT Korin Bara Energy (Publikasi 1-Klik)

Saat ini **PT Korin Bara Energy** berada dalam status `Draft` sesuai arahan proyek (disiapkan penuh tetapi disembunyikan dari publik).

### Cara Melakukan Pratinjau (*Admin Preview*):
1. Pastikan Anda telah login ke CMS.
2. Buka tab baru di browser dan akses halaman Beranda Korin Bara:
   `https://derco-bara-energy.co.id/id/bisnis-kami/korin-bara-energy`
3. Anda akan melihat pita pratinjau berwarna amber di bagian atas:
   `[ MODE PRATINJAU DRAFT: Halaman ini hanya terlihat oleh administrator dan tidak terindeks publik ]`

### Cara Menayangkan Secara Resmi (*Go Live*):
1. Masuk ke CMS Admin -> menu **Perusahaan**.
2. Klik **PT Korin Bara Energy**.
3. Pada opsi **Status Publikasi**, ubah dari `Draft` menjadi `Published`:

```
Status Publikasi:
( ) Draft (Disembunyikan dari Publik)
(*) Published (Tayang untuk Publik)
```

4. Klik tombol **Simpan Perubahan** (*Save*).
5. **Dampak Langsung:**
   - PT Korin Bara Energy seketika muncul di kartu unit usaha pada Beranda Holding.
   - Menu navigasi anak usaha otomatis bertambah.
   - URL `/id/bisnis-kami/korin-bara-energy/*` dan `/en/our-business/korin-bara-energy/*` langsung dapat diakses bebas oleh seluruh pengunjung umum.
   - Peta situs `sitemap.xml` otomatis memperbarui seluruh halaman Korin Bara untuk diindeks oleh Google.

---

## 10. Pengaturan SEO Global

Buka menu **Setelan SEO** di sidebar untuk memperbarui:
- **Default Meta Title**: Judul yang muncul di tab browser dan hasil pencarian Google.
- **Default Meta Description**: Deskripsi rangkuman situs di Google Search.
- **Google Search Console / Analytics Code**: Masukkan ID pelacakan (misal: `G-XXXXXXXXXX`) tanpa perlu mengubah kode sumber program.

---

## 11. Bantuan & Dukungan Teknis

Jika mengalami kendala operasional atau memerlukan penambahan fitur:
- Email Tim Pengembang: `tech-support@derco-bara-energy.co.id`
- Dokumentasi Teknis Sistem: Lihat berkas `README.md` pada repositori proyek.
