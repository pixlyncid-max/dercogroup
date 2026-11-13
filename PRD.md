# PRD — Website PT Derco Bara Energy

Versi 1.6 · Revisi 6 (CMS v2: Editor Halaman Per Perusahaan untuk Pengguna Awam)
Dokumen pendamping: [DESIGN.md](./DESIGN.md) · [README.md](./README.md) · [docs/CMS-DESIGN.md](./docs/CMS-DESIGN.md) · [docs/CMS-GUIDE.md](./docs/CMS-GUIDE.md)

---

## 1. Ringkasan

Membangun website company profile **PT Derco Bara Energy** (holding) beserta tiga situs anak usaha di bawah satu aplikasi:

1. PT Korin Bara Energy (tayang penuh, 6 halaman aktif)
2. PT Korin Berkat Energy (5 halaman aktif)
3. PT Beramal Sukses Sejahtera (4 halaman aktif)

Website dwibahasa (Indonesia dan Inggris), dikelola lewat CMS admin, dibangun dengan Laravel 12, React, dan MySQL. Tampilan holding mengikuti gambar referensi; tiap anak usaha memiliki identitas visual dan layout sendiri dengan warna dasar putih. Tampilan CMS admin (/admin) mengikuti spesifikasi visual referensi konsol korporat modern.

---

## 2. Latar Belakang dan Tujuan

### 2.1 Masalah
Perusahaan memiliki tiga bisnis utama tetapi belum punya satu pintu masuk yang menyatukan identitas grup sekaligus memberi ruang identitas bagi masing-masing anak usaha. Calon mitra dan pembeli B2B perlu cepat memahami siapa grup ini, apa yang dijual, di mana beroperasi, dan apakah legal serta berkualitas.

### 2.2 Tujuan
| # | Tujuan | Cara mengukur |
|---|---|---|
| G1 | Membangun kredibilitas grup di mata calon mitra B2B | Halaman Mutu & Kepatuhan lengkap; tidak ada halaman kosong |
| G2 | Memudahkan pengunjung menemukan anak usaha yang relevan | Maksimal 2 klik dari beranda holding ke halaman produk anak usaha |
| G3 | Menghasilkan lead lewat form kontak | Form berfungsi, pesan tersimpan dan terkirim ke email tujuan |
| G4 | Konten dapat dikelola tanpa programmer | Seluruh teks, gambar, produk, dan dokumen dapat diedit di CMS |
| G5 | Mudah dikembangkan | Anak usaha ke-4 dapat ditambahkan tanpa mengubah struktur kode inti |

### 2.3 Bukan Tujuan (versi 1.0)
- Portal berita/artikel dan karier.
- Sistem e-commerce atau pemesanan online.
- Area login untuk pelanggan.
- Domain terpisah per anak usaha (semua di bawah path holding).
- Integrasi ERP atau sistem operasional internal.

---

## 3. Pengguna

| Persona | Kebutuhan | Yang dicari di website |
|---|---|---|
| **Calon pembeli (B2B)** | Menilai pemasok batu bara | Spesifikasi produk, lokasi, sertifikat, cara menghubungi |
| **Calon mitra/investor** | Memahami grup | Struktur grup, profil, visi-misi, rekam jejak |
| **Regulator/auditor** | Memverifikasi legalitas | Dokumen izin dan kepatuhan yang dapat diunduh |
| **Admin konten (internal)** | Mengelola konten | CMS yang sederhana dan aman |

---

## 4. Ruang Lingkup

### 4.1 Peta Situs (21 halaman database, 20 halaman publik aktif)

```
Holding — PT Derco Bara Energy (4 halaman)
├── Beranda
├── Tentang Kami
├── Bisnis Kami  ──┬── PT Korin Bara Energy      [tayang - 6 halaman]
│                  │     ├── Beranda · Tentang Kami · Produk
│                  │     └── Operasi · Mutu & Kepatuhan · Kontak
│                  ├── PT Korin Berkat Energy    [aktif - 5 halaman]
│                  │     ├── Beranda · Tentang Kami · Produk
│                  │     └── Operasi · Kontak
│                  └── PT Beramal Sukses Sejahtera [aktif - 5 halaman tayang + 1 dinonaktifkan]
│                        ├── Beranda · Tentang Kami · Layanan & Produk
│                        ├── Operasi · Hubungi Kami
│                        └── [Mutu & Kepatuhan: nonaktif/unpublished, publik 404]
└── Hubungi Kami
```

- Holding: 4 halaman publik.
- PT Korin Bara Energy: 6 halaman aktif tayang (berstatus `published`).
- PT Korin Berkat Energy: 5 halaman aktif (halaman Mutu & Kepatuhan dihapus permanen).
- PT Beramal Sukses Sejahtera: 6 halaman tersimpan di CMS (5 halaman aktif tayang + 1 halaman Mutu & Kepatuhan nonaktif `is_published=false` dan `show_in_nav=false`, publik 404).
- Total: **21 halaman di basis data**, dengan **20 halaman publik aktif** yang dapat diakses pengunjung biasa tanpa login. Ditambah halaman detail produk dan detail layanan dinamis.

### 4.2 URL dan Bahasa
```
/id                                   /en
/id/tentang-kami                      /en/about-us
/id/bisnis-kami                       /en/our-business
/id/bisnis-kami/{slug}                /en/our-business/{slug}
/id/bisnis-kami/{slug}/produk         /en/our-business/{slug}/products
/id/hubungi-kami                      /en/contact-us
(301 redirect: /id/kontak -> /id/hubungi-kami; /en/contact -> /en/contact-us)

Rute Kontak Beramal:
/id/bisnis-kami/beramal/hubungi-kami  /en/our-business/beramal/contact-us
(301 redirect: /id/bisnis-kami/beramal/kontak -> hubungi-kami; /en/.../contact -> contact-us)
```
Slug halaman diterjemahkan per bahasa. `/` mengarahkan ke bahasa sesuai preferensi browser, dengan cadangan `/id`. PT Korin Berkat dan PT Korin Bara mempertahankan slug `/kontak` dan `/contact`, sedangkan Holding dan PT Beramal Sukses Sejahtera menggunakan `/hubungi-kami` dan `/contact-us`.

### 4.3 Identitas Visual
Lihat [DESIGN.md](./DESIGN.md). Ringkasnya:

| Entitas | Warna | Dasar |
|---|---|---|
| Holding | Charcoal + tembaga (mengikuti gambar referensi) | Gelap dan putih bergantian |
| Korin Berkat | Navy + gold | Putih |
| Korin Bara | Gold + navy | Putih |
| Beramal Sukses | Dark water + cerulean | Putih |

---

## 5. Kebutuhan Fungsional

Prioritas: **P0** wajib rilis, **P1** penting, **P2** bila waktu memungkinkan.

### 5.1 Navigasi dan Halaman Publik (FR-PUB)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-PUB-1 | Seluruh 14 halaman publik aktif dapat diakses sesuai peta situs dan menampilkan konten dari CMS | P0 |
| FR-PUB-2 | Menu navigasi dibentuk otomatis dari halaman yang `show_in_nav = true` via `useNavigation()`; penomoran Korin Berkat dinamis sesuai item (01-05) | P0 |
| FR-PUB-3 | Setiap anak usaha memakai Layout dan tema visualnya sendiri; berpindah dari holding ke anak usaha mengganti Layout seluruhnya | P0 |
| FR-PUB-4 | Beranda holding mengikuti susunan referensi; beranda Korin Berkat dan Beramal menyembunyikan heading section setelah hero (`sr-only`) | P0 |
| FR-PUB-5 | Setiap halaman anak usaha menyediakan tautan kembali ke holding | P0 |
| FR-PUB-6 | Halaman 404 dan 500 bermerek sesuai tema terakhir yang dikunjungi | P1 |
| FR-PUB-7 | Tombol "kembali ke atas" dan indikator halaman aktif di menu | P2 |
| FR-PUB-8 | Konfigurasi set halaman dinamis per tema via `CompanyTheme::pages()` tanpa pengkondisian bercabang di controller/action | P0 |

### 5.2 Visibilitas Anak Usaha (FR-VIS)
Fitur visibilitas anak usaha (`draft` / `published`) merupakan kebutuhan inti arsitektur sistem. Di Revisi 4, PT Korin Bara Energy telah beralih ke status `published` (tayang penuh). Fitur FR-VIS tetap aktif sepenuhnya untuk seluruh entitas perusahaan di masa mendatang.

| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-VIS-1 | Tabel `companies` memiliki kolom `status` (`draft` / `published`) | P0 |
| FR-VIS-2 | Perusahaan `draft` tidak muncul di menu, grid Bisnis Kami, footer, sitemap.xml, dan pencarian | P0 |
| FR-VIS-3 | URL perusahaan `draft` mengembalikan **404** bagi pengunjung biasa | P0 |
| FR-VIS-4 | Admin yang login dapat membuka halaman `draft` lewat mode **preview** dengan penanda "Pratinjau" yang jelas | P0 |
| FR-VIS-5 | Halaman preview diberi header `noindex, nofollow` | P0 |
| FR-VIS-6 | Mengubah status ke `published` langsung menayangkan perusahaan tanpa deploy ulang; grid Bisnis Kami beradaptasi dari 2 ke 3 kartu | P0 |
| FR-VIS-7 | Status juga berlaku pada tingkat halaman (`is_published`) dan produk | P1 |

### 5.3 Dwibahasa (FR-I18N)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-I18N-1 | Bahasa Indonesia dan Inggris, prefix `/id` dan `/en` | P0 |
| FR-I18N-2 | Seluruh konten CMS yang tampil ke publik memiliki field per bahasa | P0 |
| FR-I18N-3 | Pengalih bahasa membawa pengunjung ke halaman setara (slug terjemahan) | P0 |
| FR-I18N-4 | Jika terjemahan EN kosong, tampilkan versi ID dan tandai di CMS sebagai "belum diterjemahkan" | P1 |
| FR-I18N-5 | Tag `hreflang` dan `lang` pada `<html>` | P0 |
| FR-I18N-6 | Teks antarmuka tetap (tombol, label form, pesan error) tersimpan di berkas terjemahan, bukan di database | P0 |

### 5.4 Form Kontak (FR-CON)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-CON-1 | Field: nama, email, telepon (opsional), organisasi (opsional), subjek, pesan | P0 |
| FR-CON-2 | Validasi di server (Form Request) dan umpan balik inline di klien | P0 |
| FR-CON-3 | Pesan disimpan ke `contact_messages` dengan `company_id` asal halaman dan `locale` | P0 |
| FR-CON-4 | Email notifikasi ke alamat tujuan yang dapat diatur per perusahaan di CMS | P0 |
| FR-CON-5 | Proteksi spam: honeypot + rate limit (mis. 5 pesan per IP per jam) | P0 |
| FR-CON-6 | CAPTCHA (mis. Cloudflare Turnstile) dapat diaktifkan lewat pengaturan | P2 |
| FR-CON-7 | Halaman/pesan sukses setelah kirim, tanpa muat ulang penuh | P0 |

### 5.5 Konten Dinamis (FR-DYN)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-DYN-1 | Produk dengan spesifikasi terstruktur (label, nilai, satuan) | P0 |
| FR-DYN-2 | Operasi: tipe (tambang, stockpile, pelabuhan, armada), nama, deskripsi, koordinat | P0 |
| FR-DYN-3 | Statistik perusahaan (angka, satuan, awalan/akhiran) untuk counter animasi | P0 |
| FR-DYN-4 | Sertifikasi dan izin: kategori, penerbit, nomor, tanggal terbit, masa berlaku, berkas PDF per bahasa | P0 |
| FR-DYN-5 | Kontak perusahaan: alamat, telepon, email, WhatsApp, jam operasional, media sosial | P0 |
| FR-DYN-6 | Sertifikat yang melewati `expires_at` diberi penanda "kedaluwarsa" di CMS (tidak otomatis disembunyikan) | P2 |

### 5.6 SEO (FR-SEO)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-SEO-1 | Server-side rendering (Inertia SSR) agar konten terbaca mesin pencari | P0 |
| FR-SEO-2 | Meta title, description, dan Open Graph per halaman, dapat diedit per bahasa | P0 |
| FR-SEO-3 | `sitemap.xml` dinamis (hanya konten `published`) dan `robots.txt` | P0 |
| FR-SEO-4 | Data terstruktur `Organization` dan `LocalBusiness` (schema.org) dari `company_contacts` | P1 |
| FR-SEO-5 | URL kanonik dan hreflang untuk setiap pasangan bahasa | P0 |

### 5.7 Branding dan Logo Perusahaan (FR-BRD)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-BRD-1 | Logo resmi holding dan setiap anak usaha tampil di header dan footer dengan rasio aspek terjaga | P0 |
| FR-BRD-2 | Konfigurasi branding terpusat di satu sumber (`config/branding.php`) dan ditransmisikan via API `CompanyResource` | P0 |
| FR-BRD-3 | Logo memilih varian yang tepat secara otomatis sesuai kontras permukaan (`light` untuk latar terang, `dark` untuk latar gelap, `compact` untuk rail sempit) | P0 |
| FR-BRD-4 | Dilarang menggunakan filter CSS (brightness/invert); varian permukaan gelap dihasilkan dari skrip terprogram tanpa mengubah warna merek | P0 |
| FR-BRD-5 | Logo di header berupa tautan beranda dengan accessible label dwibahasa; logo di footer tidak memiliki tautan untuk menghindari navigasi redundan | P0 |
| FR-BRD-6 | Fallback wordmark semantik otomatis ditampilkan jika aset gambar logo tidak ditemukan atau gagal dimuat | P1 |

---

## 6. CMS Admin v2 (Revisi 6: Editor Halaman untuk Pengguna Awam)

Dibangun dengan Filament di `/admin`, terpisah dari tampilan publik.

### 6.1 Arsitektur & Prinsip Utama
1. **Satu CMS untuk Semua Perusahaan**: SATU admin dapat mengelola holding dan ketiga anak usaha tanpa pembatasan akun per perusahaan. Tidak ada tabel `company_user` dan tidak ada isolasi akun.
2. **Tenancy sebagai Pemisah Konten**: Filament Multi-Tenancy (tenant = `Company`) digunakan secara eksklusif sebagai pemisah konten, bukan pembatas izin akses pengguna.
3. **Sidebar Halaman Website**:
   - Sidebar menyajikan halaman website aktual yang dimiliki oleh perusahaan aktif:
     - **PT Derco Bara Energy**: Beranda (termasuk Header) · Tentang Kami · Bisnis Kami · Kontak · Footer.
     - **PT Korin Berkat Energy**: Beranda (termasuk Header) · Tentang Kami · Produk · Operasi · Kontak · Footer.
     - **PT Korin Bara Energy**: Beranda (termasuk Header) · Tentang Kami · Produk · Operasi · Kontak · Footer.
     - **PT Beramal Sukses Sejahtera**: Beranda (termasuk Header) · Tentang Kami · Produk · Operasi · Kontak · Footer.
   - Di bawahnya terdapat grup **"Pengaturan"**: Pesan Masuk (dengan lencana jumlah pesan baru), Pengaturan Website, Pengguna (Super Admin), dan Perusahaan (Super Admin).
   - Tidak ada halaman dasbor terpisah: setelah login, admin langsung mendarat di editor Beranda pada perusahaan terakhir yang dikunjungi (awalnya holding).
4. **Tata Letak Tetap (Fixed Layout)**: Admin awam mengelola *isi* (konten, foto, teks), bukan susunan makro. Admin berwenang menyembunyikan section tertentu (saklar *toggle*), menambah/menghapus/mengurutkan item di dalam daftar repeater, tetapi tidak dapat menambah atau memindahkan section secara bebas.
5. **Kontrol Penuh Teks & Foto**: Seluruh teks dan foto yang tampil di website publik (termasuk logo perusahaan di header & footer) dapat diubah langsung dari CMS. Hanya pesan sistem teknis (validasi Laravel, halaman 404/500) yang tetap berada di berkas bahasa.
6. **Pengalih Bahasa Dwibahasa**: Saklar pil "Indonesia | English" di header aksi melayang dengan lencana penghitung dinamis jumlah kolom pada bahasa alternatif yang masih kosong.
7. **Simpan Instan & Riwayat Perubahan**: Tombol "Simpan Perubahan" langsung menayangkan konten ke website publik. Dilengkapi pengaman otomatis `ContentRevision` (snapshot formulir dan pemulihan instan hingga 10 versi terakhir per halaman) serta penguncian optimistis (`content_version`) dengan resolusi konflik multi-admin ("Muat Ulang Versi Terbaru" vs "Timpa Konten").
8. **Modul Dorman & Halaman Mutu Dihapus**: Halaman "Mutu & Kepatuhan" dihapus permanen untuk SEMUA perusahaan. Modul Sertifikasi disembunyikan dari antarmuka CMS dan dicatat sebagai modul dorman (kode tetap ada di backend untuk kebutuhan ekspansi mendatang).
9. **Peran Global (Berlaku untuk Semua Perusahaan)**:
   - **Super Admin**: Akses penuh ke seluruh fitur, konten, pengelolaan Pengguna, Perusahaan, status tayang website, tema visual, dan pengaturan sistem.
   - **Editor**: Mengelola seluruh konten halaman, foto, logo, dan pesan masuk untuk semua perusahaan. Dibatasi dari menu Pengguna, Perusahaan, status tayang, tema, dan pengaturan sistem.
10. **Aksen Warna Tema Adaptif**: Aksen tombol simpan dan lencana aktif menyesuaikan perusahaan aktif (Derco: Tembaga `#C87A38`; Korin Berkat: Emas `#EDB856`; Korin Bara: Amber `#F59E0B`; Beramal: Cerulean `#0284C7`), dengan sidebar navy `#0F172A` yang konsisten.

### 6.2 Matriks CRUD per Modul
| Modul | Lingkup | Buat | Baca | Ubah | Hapus | Pengaman & Keterangan |
|---|---|---|---|---|---|---|
| **Editor Halaman (Beranda, Profil, dll.)** | Tenant | Skema | Ya | Ya | Nonaktifkan Section | Tata letak tetap. Section dapat dinonaktifkan via saklar visibilitas. Menyimpan memicu snapshot revisi. |
| **Header & Footer** | Global Tenant | Skema | Ya | Ya | n/a | Header diedit di Beranda; Footer memiliki pseudo-halaman tersendiri. Berlaku lintas halaman pada tenant tersebut. |
| **Pengaturan Website** | Tenant | n/a | Ya | Ya | n/a | Nama, tagline, deskripsi, email penerima formulir kontak, dan SEO bawaan (Google & pratinjau sosial). Status tayang & tema dikunci untuk Super Admin. |
| **Pesan Masuk** | Tenant | Form Publik | Ya | Ya | Ya (Soft delete) | Dilengkapi catatan internal, aksi status cepat (*Baru, Dibaca, Dibalas*), ekspor CSV terlingkup, lencana sidebar, dan lonceng top bar. |
| **Pengguna CMS** | Global (Super Admin) | Ya | Ya | Ya | Ya | Role Super Admin & Editor. Dilindungi dari hapus akun sendiri dan hapus/pencabutan Super Admin terakhir. Reset sandi email. |
| **Perusahaan** | Global (Super Admin) | Ya | Ya | Ya | Ya | Menambah perusahaan memicu `CreateCompanyPages` dan inisiasi section bawaan skema. Dilarang menghapus holding, perusahaan beranak, atau perusahaan berpesan masuk. |

### 6.3 Kebutuhan Fungsional CMS (FR-CMS v2)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| FR-CMS-1 | Login aman berbasis email/kata sandi; reset kata sandi lewat broker email | P0 |
| FR-CMS-2 | Pengalih bahasa pil ID/EN di bar aksi atas dengan indikator jumlah kolom kosong | P0 |
| FR-CMS-3 | Seluruh section dihasilkan dari satu sumber skema (`SectionSchema` & kelas `Block`) terpadu | P0 |
| FR-CMS-4 | Unggah gambar otomatis dikonversi ke WebP kualitas tinggi (maks 5MB); dokumen PDF maks 10MB | P0 |
| FR-CMS-5 | Validasi ketat teks alternatif (Alt Text) wajib terisi dalam ID dan EN sebelum gambar disimpan | P0 |
| FR-CMS-6 | Tombol "Preview Website" membuka drawer iframe responsif live (`?preview=1`) dengan saklar perangkat (Desktop, Tablet, Mobile) | P0 |
| FR-CMS-7 | Riwayat perubahan otomatis menyimpan snapshot formulir hingga 10 versi terakhir dan dapat dipulihkan instan | P0 |
| FR-CMS-8 | Penguncian optimistis mendeteksi konflik edit multi-admin dan menyajikan dialog banding versi | P0 |
| FR-CMS-9 | Seluruh teks publik bebas dari teks tertulis di kode (hardcoded JSX) dan ditegakkan oleh ESLint `react/jsx-no-literals` | P0 |
| FR-CMS-10 | Uji kegunaan awam menjamin 10 tugas umum dapat diselesaikan dalam $\le 5$ klik dari sidebar | P0 |

---

## 7. Kebutuhan Non-Fungsional

### 7.1 Performa
| Metrik | Target (mobile, jaringan 4G) |
|---|---|
| Largest Contentful Paint | ≤ 2,5 detik |
| Interaction to Next Paint | ≤ 200 ms |
| Cumulative Layout Shift | ≤ 0,1 |
| Berat halaman awal (tanpa gambar hero) | ≤ 300 KB JS terkompresi |
| Skor Lighthouse (Performance, Accessibility, SEO, Best Practices) | ≥ 90 |

Strategi: code splitting per tema (hanya tema yang dikunjungi yang dimuat), gambar responsif dengan rasio tetap, font self-host dengan `font-display: swap`, cache respons halaman publik, query dengan eager loading.

### 7.2 Keamanan
- Form Request untuk semua input; proteksi CSRF; output ter-escape.
- Unggahan dibatasi tipe dan ukuran (gambar, PDF); nama berkas di-sanitasi.
- Kata sandi di-hash (bcrypt/argon2), sesi aman, `HttpOnly` dan `SameSite`.
- Rate limit pada login admin dan form kontak.
- Header keamanan (CSP, `X-Content-Type-Options`, `Referrer-Policy`).
- Pencadangan database dan media berkala (harian untuk database).
- Dependensi diperiksa berkala (`composer audit`, `npm audit`).

### 7.3 Aksesibilitas
Target WCAG 2.2 AA. Rincian di [DESIGN.md](./DESIGN.md), bagian 12.

### 7.4 Kemudahan Pemeliharaan
Tujuan utama proyek ini adalah kode yang **rapi, terstruktur, dan tanpa pengulangan.** Aturan yang mengikat:
1. **Controller tipis**; logika bisnis di kelas Action.
2. **Satu controller generik** untuk enam halaman anak usaha, bukan 18 controller.
3. **Tema hanya berisi presentasi**; logika (menu, bahasa, SEO, form) di `core/` sebagai hook.
4. **Satu sumber** untuk preset animasi, token warna, enum, dan tipe data.
5. TypeScript mode `strict`; PHP memakai tipe pada parameter dan return.
6. Pemformat otomatis: Laravel Pint, ESLint, Prettier. Pemeriksaan dijalankan di CI.
7. Tidak ada angka atau teks ajaib; konstanta dan enum bernama.
8. Setiap fitur disertai tes (lihat 7.5).

### 7.5 Pengujian
| Jenis | Alat | Cakupan minimal |
|---|---|---|
| Unit dan fitur backend | Pest | Action, resolver slug/bahasa, visibilitas draft, form kontak |
| Komponen frontend | Vitest + Testing Library | Hook `core/`, blok utama |
| End-to-end | Playwright | Navigasi holding → anak usaha, ganti bahasa, kirim form, 404 untuk draft |
| Aksesibilitas | axe | Semua halaman utama pada tiap tema |

### 7.6 Kompatibilitas
Dua versi terbaru Chrome, Edge, Firefox, Safari (desktop dan mobile). Responsif 360px hingga 1920px.

### 7.7 Kualitas Visual (NFR-VIS)
Tampilan harus terlihat seperti **desain UI profesional**, bukan keluaran generator otomatis. Standar rinci ada di [DESIGN.md](./DESIGN.md) bagian 1.1.

| ID | Kebutuhan | Prioritas |
|---|---|---|
| NFR-VIS-1 | Tidak memuat ciri "tampilan AI" yang dilarang (gradasi neon, glassmorphism berlebihan, hero berformula, kartu ikon seragam di setiap section, emoji sebagai ikon, copy klise) | P0 |
| NFR-VIS-2 | Komposisi section bervariasi dalam satu halaman; setiap halaman memiliki satu momen visual utama | P0 |
| NFR-VIS-3 | Grid 12 kolom, skala tipografi tetap, maksimal dua keluarga huruf dan satu keluarga ikon per tema | P0 |
| NFR-VIS-4 | Warna aksen dipakai hanya untuk elemen penting; netral mendominasi | P0 |
| NFR-VIS-5 | Copy placeholder faktual dan spesifik, bukan bahasa promosi klise | P1 |
| NFR-VIS-6 | Setiap halaman utama lulus enam uji pada DESIGN.md 1.1 (kabur, tiga detik, tukar tema, template, copy, referensi) | P0 |

### 7.8 Alur Kerja Pengembangan dengan Agent Skills (NFR-AGT)
| ID | Kebutuhan | Prioritas |
|---|---|---|
| NFR-AGT-1 | Pengembangan memakai skill pada folder `.agents/skills/ui-ux-pro-max` di akar proyek. Sebelum tiap tugas, agent memindai folder, membaca `SKILL.md` yang relevan, dan mengikutinya | P0 |
| NFR-AGT-2 | Bila skill berbeda dengan PRD atau DESIGN: PRD dan DESIGN menang untuk identitas visual dan ruang lingkup; skill menang untuk teknik dan kualitas pengerjaan. Konflik dilaporkan | P0 |
| NFR-AGT-3 | Laporan akhir tiap fase mencantumkan skill yang dipakai | P1 |

---

## 8. Model Data (ringkasan)

Gambar ERD dan penjelasan lengkap telah disepakati dalam diskusi. Tabel utama:

`companies` (dengan `parent_id`, `theme`, **`status`**) · `pages` · `page_blocks` · `products` · `product_specs` · `operations` · `company_stats` · `certifications` · `company_contacts` · `contact_messages` · `media` · `users` · `roles` / `permissions` · `settings`

Perubahan dari rancangan awal: kolom `is_active` pada `companies` diganti `status` (`draft` / `published`) untuk memenuhi FR-VIS.

Constraint penting: `UNIQUE(companies.slug)`, `UNIQUE(pages.company_id, pages.key)`, `UNIQUE(products.company_id, products.slug)`. Kolom bertipe enum PHP: `theme`, `pages.key`, `page_blocks.type`, `operations.type`, `certifications.category`, `company_contacts.type`, `contact_messages.status`.

---

## 9. Strategi Konten Placeholder

Konten asli belum ada. Selama pengembangan:
- **Seeder** mengisi seluruh 22 halaman, blok, produk contoh, operasi, statistik, dan sertifikat contoh dalam dua bahasa, bertanda `[PLACEHOLDER]`.
- Gambar memakai placeholder SVG lokal dengan rasio tetap (tanpa layanan eksternal).
- Dokumen PDF contoh dibuat otomatis (satu berkas generik).
- Copy placeholder ditulis faktual dan netral (tanpa kalimat klise) agar penilaian tampilan tidak tertipu oleh teks promosi kosong.
- **Serah terima konten:** setelah materi klien siap, admin mengganti konten lewat CMS tanpa perubahan kode. Daftar konten yang dibutuhkan ada di bagian 12.

---

## 10. Rencana Rilis

| Fase | Isi | Hasil |
|---|---|---|
| **0. Fondasi** | Laravel 12, Inertia + React + TS, Tailwind, SSR, i18n, CI, lint dan tes dasar | Proyek berjalan, halaman kosong dua bahasa |
| **1. Data dan CMS** | Migrasi, model, enum, seeder; Filament untuk semua modul; sistem blok | Konten dapat diisi dan diedit |
| **2. Holding** | Tema holding, 4 halaman sesuai referensi | Holding lengkap |
| **3. Korin Berkat** | Tema dan 6 halaman | Anak usaha pertama tayang |
| **4. Beramal Sukses** | Tema dan 6 halaman | Anak usaha kedua tayang |
| **5. Korin Bara** | Tema dan 6 halaman, status `draft` | Selesai namun tersembunyi |
| **6. Penyempurnaan** | Animasi, performa, SEO, aksesibilitas, tes E2E | Kandidat rilis |
| **7. Peluncuran** | Pindah konten asli, UAT klien, deploy, monitoring | Tayang |

Urutan fase 3–5 dapat bertukar tanpa mengubah fondasi, karena ketiganya berbagi pola yang sama.

---

## 11. Kriteria Penerimaan (Definition of Done)

1. Seluruh 22 halaman dapat dibuka dalam dua bahasa dan menampilkan konten dari CMS.
2. Beranda holding sesuai susunan referensi pada desktop dan mobile.
3. Korin Bara **tidak terlihat** oleh pengunjung di menu, grid, sitemap, dan URL langsung; terlihat oleh admin di mode preview; satu klik di CMS menayangkannya.
4. Tiga anak usaha yang tayang memiliki Layout berbeda dari holding dan satu sama lain.
5. Form kontak menyimpan pesan, mengirim email, dan menolak spam sederhana.
6. Seluruh target performa dan aksesibilitas pada 7.1 dan 7.3 terpenuhi.
7. Seluruh tes lulus di CI; Pint, ESLint, dan `tsc --noEmit` bersih.
8. Menambah satu anak usaha percobaan (bahan uji) tidak membutuhkan perubahan pada kode inti selain satu folder tema.
9. README memungkinkan pengembang baru menjalankan proyek dalam kurang dari 30 menit.
10. Seluruh halaman utama pada tiap tema lulus enam uji kualitas visual (DESIGN.md 1.1) dan tidak memuat ciri "tampilan AI".
11. Setiap fase tercatat memakai skill yang relevan dari folder `.agents/skills/ui-ux-pro-max`.

---

## 12. Risiko, Asumsi, dan Pertanyaan Terbuka

### 12.1 Risiko
| Risiko | Dampak | Mitigasi |
|---|---|---|
| Korin Berkat dan Korin Bara memakai palet gold + navy yang sama | Situs tampak kembar | Proporsi warna dibalik, rona gold digeser, layout dan tipografi dibedakan total |
| Konten asli terlambat | Peluncuran tertunda | Seeder lengkap dan rasio gambar tetap sehingga konten asli tinggal diganti |
| Tiga tema ditambah SSR memperbesar ukuran bundel | Performa turun | Code splitting per tema, audit bundel di CI |
| Slug terjemahan bocor ke berbagai tempat | Bug routing | Satu `PageResolver` terpusat |
| Gold/tembaga terang kurang kontras sebagai teks | Gagal aksesibilitas | Varian warna gelap untuk teks, aturan di DESIGN.md |
| Tampilan terkesan buatan AI atau template generik | Kredibilitas grup turun | Standar anti-"tampilan AI" (DESIGN.md 1.1), enam uji kualitas visual, pencocokan dengan gambar referensi, tinjauan manusia sebelum rilis |

### 12.2 Asumsi
- Bidang usaha Beramal Sukses Sejahtera belum diketahui; struktur halaman dibuat netral dan visual final menunggu klarifikasi.
- Hanya satu jalur kontak per perusahaan pada versi 1.0 (email tujuan dapat diatur per perusahaan).
- Holding mengikuti gambar referensi (gelap dan putih bergantian), sedangkan syarat "latar putih" dari klien berlaku untuk anak usaha.
- Hosting mendukung PHP 8.2+, MySQL 8, dan Node.js (untuk SSR).
- Folder `.agents/skills/ui-ux-pro-max` berisi skill agent (umumnya folder dengan berkas `SKILL.md`) yang tersedia di repositori. Isi dan lokasi pastinya dikonfirmasi saat Prompt 0 dijalankan.

### 12.3 Pertanyaan Terbuka
1. Apa bidang usaha PT Beramal Sukses Sejahtera?
2. Dapatkah klien menyediakan berkas logo resmi versi terang/negatif (format SVG atau PNG master) untuk menggantikan varian turunan hasil konversi?
3. Alamat email tujuan formulir kontak, dan apakah berbeda per perusahaan?
4. Domain, hosting, dan layanan email (SMTP) yang akan dipakai?
5. Kapan Korin Bara direncanakan tayang?
6. Apakah ada kebutuhan tambahan seperti peta interaktif, unduh company profile PDF, atau Google Analytics?

### 12.4 Konten yang Dibutuhkan dari Klien
Per perusahaan: logo master (termasuk varian negatif/terang untuk latar gelap), deskripsi singkat dan panjang (ID/EN), sejarah, visi-misi, nilai, foto operasi dan tim, daftar produk beserta spesifikasi, lokasi operasi, angka kunci, sertifikat dan izin (PDF), alamat, telepon, email, jam operasional.

---

## 13. Lampiran

- Diagram alur situs dan referensi desain beranda: dari klien/tim, disimpan di repositori pada `docs/reference/`.
- ERD (dua bagian) disepakati dalam diskusi dan dipindahkan ke `docs/erd.md` saat fase 1.
- Lembar pratinjau matriks kontras logo di latar gelap/terang: `docs/branding/logo-preview.png`.

---

## 14. Catatan Perubahan (Changelog)

### Versi 1.6 (Revisi 6 — CMS v2: Editor Halaman Per Perusahaan untuk Pengguna Awam)
- **Satu CMS untuk Semua Perusahaan:** Multi-tenancy Filament difungsikan murni sebagai pemisah konten; satu admin mengelola holding dan ketiga anak usaha tanpa pembatasan per perusahaan.
- **Editor Halaman dengan Tata Letak Tetap:** Admin awam mengelola teks, foto, dan visibilitas section secara stabil tanpa manipulasi drag-drop makro yang rentan merusak tata letak.
- **Skema Konten Tunggal (`SectionSchema`):** Satu sumber kebenaran backend untuk seluruh section, validasi ketat teks dan Alt text gambar (ID & EN), serta inisiasi bawaan otomatis saat perusahaan baru dibuat.
- **Frontend Membaca Konten CMS:** Seluruh teks dan foto publik dinamis dari database, penegakan ESLint `react/jsx-no-literals`, dan atribut stabil `id="section-{key}"` serta `data-section="{key}"`.
- **Pengalih Bahasa Dwibahasa:** Saklar pil `ID` | `EN` di bar aksi atas dengan penghitung dinamis kolom yang belum terisi.
- **Riwayat Perubahan & Penguncian Optimistis:** Penyimpanan snapshot formulir hingga 10 versi terakhir per halaman dengan pemulihan instan, didukung deteksi konflik edit multi-admin berbasis versi konten (`content_version`).
- **Pratinjau Responsif Terintegrasi:** Drawer preview live (`?preview=1`) dengan saklar perangkat desktop, tablet, dan ponsel.
- **Pesan Masuk & Pengaturan Website:** Penambahan catatan internal pada pesan kontak masuk, ekspor CSV terlingkup tenant, halaman Pengaturan Website terpadu, dan proteksi hapus entitas.
- **Halaman Mutu Dihapus & Modul Sertifikasi Dorman:** Halaman Mutu & Kepatuhan dihapus secara permanen untuk seluruh perusahaan; modul sertifikasi disembunyikan dari antarmuka CMS.

### Versi 1.4 (Revisi 3 — Integrasi Logo Header dan Footer Seluruh Tema)
- **Kebutuhan Fungsional FR-BRD:** Penambahan kelompok kebutuhan FR-BRD-1 s.d. FR-BRD-6 untuk logo header dan footer holding serta tiga anak usaha.
- **Konfigurasi Branding Terpusat:** Penambahan `config/branding.php` dan `ResolveCompanyLogo` action. Data logo tersedia melalui `CompanyResource` ke komponen antarmuka.
- **Generator Varian Terang:** Skrip otomatisasi Python `scripts/branding/generate_light_variants.py` menghasilkan varian logo bersih untuk permukaan gelap tanpa mengubah warna simbol/merek.
- **Komponen Logo & Aksesibilitas:** Komponen `ui/Logo` dengan tautan beranda berlabel ARIA dwibahasa di header, footer statis tanpa tautan, dan wordmark fallback. Lolos audit axe WCAG 2.2 AA.

### Versi 1.3 (Revisi 2 — Anak Usaha: Korin Berkat & Beramal)
- **Konfigurasi Set Halaman Dinamis:** Model data mendukung set halaman berbeda per tema via `CompanyTheme::pages()`. `CreateCompanyPages` dan seeder menggunakan konfigurasi ini.
- **PT Korin Berkat Energy:**
  - Halaman Mutu & Kepatuhan dihapus permanen dari basis data dan rute (URL menghasilkan 404), menyisakan 5 halaman aktif (Beranda, Tentang Kami, Produk, Operasi, Kontak).
  - Beranda: Judul section setelah hero disembunyikan visual (`sr-only`), tombol download dan link panah dihilangkan dari produk, blok Mutu & Kepatuhan (`certification_list`) dihapus.
  - Tentang Kami: Blok Timeline dihapus; Standar Kerja diganti menjadi Nilai Perusahaan (`values_grid`) 4 item editorial bernomor (Integritas, Keandalan Pasokan, Keselamatan Kerja, Efisiensi Operasional).
  - Produk: Tabel spesifikasi lengkap langsung disajikan dalam tata letak editorial 2 kolom tanpa membuka halaman detail terpisah.
- **PT Beramal Sukses Sejahtera:**
  - Halaman Mutu & Kepatuhan dinonaktifkan (`is_published=false`, `show_in_nav=false`), URL publik 404, pratinjau admin tetap diizinkan. 5 halaman aktif publik.
  - Header: Tombol solid pill kontak di header digantikan teks link kanan "Hubungi Kami" / "Contact Us" sejajar pengalih bahasa.
  - Beranda: Judul section setelah hero disembunyikan visual (`sr-only`), blok Jejak Infrastruktur (`operation_list`) dan Kepatuhan & Mutu (`certification_list`) dihapus dari beranda.
  - Kontak: Berganti nama menjadi "Hubungi Kami" / "Contact Us" dengan rute `/hubungi-kami` & `/contact-us` (301 redirect dari `/kontak` & `/contact`).

### Versi 1.2 (Revisi 1 — Holding / Web Utama)
- **Slug & Rute Kontak Holding:** Halaman kontak holding berganti slug menjadi `/id/hubungi-kami` dan `/en/contact-us` (title: "Hubungi Kami" / "Contact Us"). Slug `/id/kontak` dan `/en/contact` dialihkan melalui 301 Permanent Redirect. Tiga anak usaha tetap menggunakan `/kontak` dan `/contact`.
- **Navigasi Header Holding:** Menu Kontak dan tombol CTA digabung menjadi satu link kanan teks "Hubungi Kami" / "Contact Us" sejajar pengalih bahasa. Menu tengah holding menjadi 3 item (`Beranda · Tentang Kami · Bisnis Kami`).
- **Penyelarasan Blok Holding:**
  - Beranda: Kartu anak usaha asli dengan rasio 4:3, tombol "Lihat Selengkapnya", heading section besar disembunyikan secara visual (`sr-only`) untuk menjaga ritme desain.
  - Tentang Kami: Hero carousel 3 gambar (`HeroCarousel`), intro split content (profil Derco + Area Operasional `[ISI WILAYAH OPERASIONAL]`), penghapusan blok Timeline/sejarah.
  - Bisnis Kami: Hero carousel 3 gambar (`HeroCarousel`), blok anak usaha alternatif tanpa perhitungan / key stats angka.
- **Tipografi Holding:** Seluruh font holding diganti dengan Helvetica font stack (hanya berat 400 dan 700). Token `--radius-button: 8px` dan `--radius-card: 12px`.
