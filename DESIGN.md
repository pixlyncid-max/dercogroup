# DESIGN.md — PT Derco Bara Energy

Versi 1.1 · Dokumen sistem desain untuk website holding dan tiga anak usaha.
Dokumen pendamping: [PRD.md](./PRD.md) · [README.md](./README.md)

---

## 1. Prinsip Desain

Empat kata kunci: **profesional, simple, elegan, mudah dipahami.**

1. **Satu ide per section.** Setiap section menjawab satu pertanyaan pembaca. Judul section adalah jawabannya, bukan slogan.
2. **Hierarki jelas.** Pembaca yang hanya memindai judul dan angka tetap paham inti perusahaan dalam 30 detik.
3. **Ruang kosong adalah fitur.** Padding besar, kolom teks maksimal 65 karakter, tidak ada dekorasi tanpa fungsi.
4. **Bukti sebelum klaim.** Angka, sertifikat, lokasi operasi, dan foto nyata didahulukan dari kalimat promosi.
5. **Animasi untuk memandu, bukan memamerkan.** Gerak dipakai untuk menunjukkan urutan dan perubahan, selalu menghormati `prefers-reduced-motion`.
6. **Konsisten di dalam tema, berbeda antar tema.** Holding dan tiga anak usaha adalah empat "rumah" dengan struktur kode yang sama tetapi identitas visual berbeda.

### 1.1 Standar Tampilan Profesional (bukan "tampilan AI")

**Target:** tampilan seperti hasil kerja studio desain korporat. Pembaca tidak boleh merasa halaman ini dihasilkan otomatis dari template atau generator. Aturan ini mengikat semua tema dan semua fase.

**Ciri "tampilan AI" yang dilarang, beserta penggantinya**

| Hindari | Ganti dengan |
|---|---|
| Gradasi ungu-biru atau neon, efek glow, dan glassmorphism berlebihan | Warna datar dari palet tema; gradasi hanya sebagai overlay foto untuk keterbacaan teks |
| Hero berformula: lencana pil kecil + judul bergradasi + dua tombol + mockup melayang | Hero sesuai tema di dokumen ini: foto nyata, tipografi tegas, satu CTA utama |
| Setiap section berupa judul di tengah + tiga kartu ikon berukuran sama | Komposisi bervariasi antar section: split asimetris, daftar baris, tabel, timeline, pita warna |
| Semua kartu berradius, berbayangan, dan berjarak sama | Hierarki elevasi: sebagian elemen datar bergaris tipis, sebagian berbayang; radius mengikuti karakter tema |
| Emoji atau ikon di setiap judul dan poin | Satu keluarga ikon (garis, stroke konsisten), dipakai hanya bila menambah makna |
| Semua teks rata tengah | Teks rata kiri; rata tengah hanya untuk judul pendek tertentu |
| Animasi di mana-mana (melayang, berdenyut, memantul, partikel) | Gerak seperlunya sesuai bagian 10 |
| Copy klise: "solusi terdepan", "inovasi tanpa batas", "menuju masa depan" | Kalimat faktual dan spesifik: apa, di mana, berapa, sejak kapan |
| Ilustrasi 3D atau vektor stok generik, foto stok orang tersenyum | Foto operasi nyata; placeholder netral sampai foto asli tersedia |
| Angka bulat yang terkesan palsu ("100+", "99%") | Angka asli dari klien; placeholder ditandai jelas |
| Blob, garis, dan pola dekoratif tanpa fungsi | Dekorasi hanya yang tercantum per tema di dokumen ini |

**Cara kerja yang diikuti (cara kerja desainer UI profesional)**
1. **Konten dulu.** Tentukan hierarki informasi tiap halaman sebelum menata visual.
2. **Grid 12 kolom** dengan margin dan gutter tetap; setiap elemen sejajar dengan grid.
3. **Skala tipografi tetap.** Maksimal dua keluarga huruf per tema dan tiga berat huruf.
4. **Warna punya peran.** Netral mendominasi (±80–90%); aksen hanya untuk elemen penting. Aksen yang muncul di mana-mana berhenti berfungsi sebagai penanda.
5. **Ritme spasi disengaja.** Jarak dekat untuk hal yang berhubungan, jarak jauh untuk pemisah topik. Jangan memakai jarak seragam di mana-mana.
6. **Detail kecil yang rapi:** garis hairline, penomoran, label, perataan optis ikon, tinggi baris konsisten, gambar dengan rasio dan perlakuan warna seragam.
7. **Satu momen berkesan per halaman**, bukan sepuluh.
8. **Kurangi, bukan tambah.** Jika sebuah elemen dihapus dan halaman tetap jelas, hapus elemen itu.

**Uji sebelum sebuah halaman dianggap selesai**

| Uji | Cara | Lulus bila |
|---|---|---|
| Kabur | Blur layar atau perkecil halaman | Hierarki (judul, gambar, CTA) tetap terbaca |
| Tiga detik | Tunjukkan halaman 3 detik | Orang dapat menyebut apa yang ditawarkan |
| Tukar tema | Tutup logo dan nama | Halaman satu tema tidak bisa tertukar dengan tema lain |
| Template | Bandingkan dengan situs SaaS/startup generik | Tidak terlihat seperti template umum |
| Copy | Baca semua teks | Tidak ada kalimat yang bisa ditempel ke situs perusahaan lain tanpa diubah |
| Referensi | Sandingkan dengan gambar referensi (holding) | Ritme warna, proporsi, dan jarak sepadan |

### 1.2 Memakai Agent Skills

Pengembangan **wajib memakai skill yang ada di folder `.agents/skills/ui-ux-pro-max`** di akar proyek (bila berada di lokasi lain, pakai lokasi tersebut).
- Sebelum mengerjakan sebuah tugas, pindai folder tersebut, baca `SKILL.md` yang relevan (desain dan UI, frontend, Laravel, pengujian, aksesibilitas, dan sebagainya), lalu ikuti.
- Pembagian wewenang bila ada perbedaan: **DESIGN.md dan PRD.md menang** untuk identitas visual dan ruang lingkup; **skill menang** untuk teknik dan kualitas pengerjaan. Setiap konflik dilaporkan, bukan diputuskan diam-diam.
- Laporan akhir tiap fase menyebut skill apa saja yang dipakai.

---

## 2. Arsitektur Visual: Empat Tema

| Kunci tema | Entitas | Dasar | Karakter | Status |
|---|---|---|---|---|
| `holding` | PT Derco Bara Energy | Gelap dan putih bergantian (mengikuti gambar referensi) | Corporate industrial | Tayang |
| `korin-berkat` | PT Korin Berkat Energy | Putih | Editorial data-driven | Tayang |
| `korin-bara` | PT Korin Bara Energy | Putih | Hangat, padat, full-bleed | **Draft (disembunyikan)** |
| `beramal` | PT Beramal Sukses Sejahtera | Putih | Bersih, ringan, lembut | Tayang |

Aturan penerapan:
- Setiap tema dibungkus `<div data-theme="...">`. Token warna dan font dicakup ke atribut ini, **bukan ke `:root`**, agar tidak bocor antar tema.
- Layout (header, footer, navigasi) dimiliki masing-masing tema. Logika (menu, bahasa, SEO, form) dibagi lewat hook di `core/`.
- Konten halaman disusun dari **blok**. Tiap tema menyediakan tampilannya sendiri untuk blok yang sama (lihat bagian 9).

---

## 3. Token Global (dipakai semua tema)

### 3.1 Spasi (skala 4px)
`4 · 8 · 12 · 16 · 24 · 32 · 48 · 64 · 96 · 128`
Jarak vertikal antar section: **96px desktop, 64px tablet, 48px mobile.**

### 3.2 Container
| Nama | Lebar maksimal | Pemakaian |
|---|---|---|
| `narrow` | 720px | Teks panjang, artikel |
| `default` | 1200px | Isi umum |
| `wide` | 1360px | Hero dan galeri |

Padding horizontal: 24px mobile, 40px tablet, 48px desktop.

### 3.3 Breakpoint
`sm 640 · md 768 · lg 1024 · xl 1280 · 2xl 1536`. Desain dibuat **mobile-first**.

### 3.4 Bayangan dan z-index
- `shadow-sm`: `0 1px 2px rgba(0,0,0,.06)`
- `shadow-md`: `0 8px 24px rgba(0,0,0,.08)`
- Skala z: `base 0 · sticky 10 · overlay 20 · modal 30 · toast 40`

### 3.5 Gerak
| Token | Nilai |
|---|---|
| `duration-fast` | 150ms |
| `duration-base` | 300ms |
| `duration-slow` | 600ms |
| `ease-out` | `cubic-bezier(0.22, 1, 0.36, 1)` |
| `ease-in-out` | `cubic-bezier(0.65, 0, 0.35, 1)` |

### 3.6 Struktur token di CSS
```css
/* resources/css/themes/holding.css */
[data-theme="holding"] {
  --color-bg: #ffffff;
  --color-surface: #f5f5f4;
  --color-ink: #2a2e34;
  --color-accent: #c77b2e;
  --font-heading: "DM Sans", system-ui, sans-serif;
  --radius-card: 4px;
}
```
Tailwind membaca variabel ini lewat `theme.extend.colors` (`bg-bg`, `text-ink`, `bg-accent`), sehingga komponen bersama otomatis mengikuti tema.

---

## 4. Tema Holding — PT Derco Bara Energy

> **Mengikuti gambar referensi** (template "BeMining"): hero gelap dengan foto alat berat yang menembus tepi, grid penawaran dengan foto, gambar bertumpuk offset, pita warna tembaga, kartu kontak gelap, footer tiga kolom. Teks dan foto pada referensi hanya contoh; semua diganti konten Derco.

### 4.1 Palet

| Peran | Nama | Hex | Catatan |
|---|---|---|---|
| Latar gelap utama | Charcoal | `#2A2E34` | Hero, section "Bisnis Kami", kartu kontak |
| Latar footer | Charcoal Deep | `#1C1F23` | Footer |
| Aksen | Copper 500 | `#C77B2E` | Pita, garis, ikon, angka besar |
| Aksen teks/tombol | Copper 700 | `#A8621F` | Teks tembaga di atas putih dan tombol berlatar tembaga |
| Aksen di atas gelap | Copper 300 | `#E3A266` | Teks tembaga kecil di atas Charcoal |
| Latar terang | Putih | `#FFFFFF` | Section terang |
| Teks utama (terang) | Ink | `#2A2E34` | |
| Teks sekunder | Slate | `#5E646D` | |
| Garis | Line | `#E2E2DF` | |

**Aturan kontras:** `#C77B2E` di atas putih hanya ±3,3:1, jadi hanya untuk elemen besar atau dekoratif. Teks tembaga kecil dan tombol berlatar tembaga dengan teks putih memakai `#A8621F` (±4,7:1).

### 4.2 Tipografi
- **DM Sans** untuk judul dan isi (mirip referensi), berat 400/500/700.
- Judul section berkarakter tebal, huruf kecil-kapital normal (bukan semua kapital).
- Label kecil di atas judul (eyebrow): 12px, huruf kapital, letter-spacing `0.12em`, warna tembaga, diberi garis tembaga pendek di bawahnya (seperti "WHO WE ARE" pada referensi).

| Level | Desktop | Mobile |
|---|---|---|
| Display (hero) | 64/68 · 700 | 40/44 |
| H2 | 40/46 · 700 | 30/36 |
| H3 | 24/32 · 700 | 20/28 |
| Body | 16/28 · 400 | 16/26 |
| Small | 14/22 | 14/22 |

### 4.3 Layout Header dan Footer
**Header:** latar Charcoal, logo kiri, menu tengah (Beranda · Tentang Kami · Bisnis Kami · Kontak, huruf kapital 12px, item aktif bergaris bawah tembaga), kanan: pengalih bahasa `ID | EN` dan tombol putih "Hubungi Kami". Header sticky, menyusut tinggi saat scroll. Mobile: menu slide dari kanan.

**Footer:** latar Charcoal Deep, tiga kolom: (1) deskripsi singkat + logo, (2) alamat, (3) jam operasional. Baris bawah: hak cipta dan tautan ke anak usaha.

### 4.4 Beranda — Susunan Section

| # | Section | Latar | Tata letak |
|---|---|---|---|
| 1 | **Hero** | Charcoal | Kiri: eyebrow, judul besar, 2 baris deskripsi, tombol tembaga. Kanan: foto alat berat yang **menembus tepi kanan dan bawah** hero (offset negatif) |
| 2 | **Bisnis Kami** | Charcoal | Judul tembaga tengah + 1 kalimat. Grid kartu foto, **2 kolom saat Korin Bara draft, 3 kolom setelah tayang**. Foto penuh, nama perusahaan dan deskripsi di bawah, tombol outline "Lihat Selengkapnya" |
| 3 | **Tentang Kami (ringkas)** | Putih | Kiri: **dua foto bertumpuk offset** (besar atas, kecil bawah menjorok). Kanan: eyebrow, judul, paragraf tebal pembuka, paragraf isi, tombol tembaga |
| 4 | **Pita Komitmen** | Tembaga penuh | Kiri: panel tembaga dengan judul putih, ikon sertifikat, 1 kalimat, tombol putih "Mutu & Kepatuhan". Kanan: foto udara tambang tanpa tepi |
| 5 | **Visi dan Misi** | Putih | Kiri: foto bertumpuk (versi cermin dari section 3). Kanan: eyebrow, judul, daftar 3 poin dengan ikon lingkaran tembaga, paragraf, tombol |
| 6 | **Kartu Kontak** | Putih, kartu Charcoal | Kartu lebar di tengah: ikon garis tembaga, "Ada pertanyaan?", nomor telepon besar berwarna tembaga, tombol putih "Kirim Pesan" |
| 7 | **Footer** | Charcoal Deep | Lihat 4.3 |

Ritme warna: **gelap → gelap → putih → tembaga → putih → putih/kartu gelap → gelap.** Pita tembaga adalah pemecah ritme yang menjadi ciri khas halaman.

### 4.5 Halaman Holding Lainnya
- **Tentang Kami:** hero pendek bergaris tembaga; sejarah singkat (timeline vertikal 4–6 titik); visi-misi; nilai perusahaan (3–4 kartu ikon); struktur grup (diagram sederhana holding → anak usaha).
- **Bisnis Kami:** hero pendek; satu blok besar per anak usaha, **bergantian kiri-kanan** (foto, nama, 2 kalimat, 3 fakta kunci, tombol "Kunjungi Situs"). Anak usaha draft tidak muncul.
- **Kontak:** dua kolom. Kiri: form (nama, email, telepon, organisasi, subjek, pesan). Kanan: alamat, telepon, email, jam, peta statis.

### 4.6 Komponen Khas Holding
- **Foto bertumpuk offset:** dua gambar, yang kecil bergeser `-48px` ke bawah dan `+48px` ke samping, saling menimpa 24px.
- **Pita tembaga:** section dua kolom, satu sisi warna solid, satu sisi foto. Di mobile menumpuk (teks dulu, foto di bawah).
- **Garis eyebrow:** 40px × 2px tembaga di bawah label kecil.

---

## 5. Tema Korin Berkat Energy — "Editorial Index"

**Gagasan:** terasa seperti laporan tahunan yang dirancang rapi. Navy memimpin, gold hanya untuk garis halus, angka, dan tombol. Data adalah bintangnya.

### 5.1 Palet
| Peran | Hex |
|---|---|
| Latar | `#FFFFFF` |
| Surface | `#F5F6F8` |
| Navy (ink dan blok gelap) | `#0A192F` |
| Navy 700 | `#16294A` |
| Gold (aksen) | `#D4AF37` |
| Gold di atas putih (teks/garis tebal) | `#8A6D12` |
| Slate | `#51586A` |
| Line | `#E3E6EC` |

**Aturan:** `#D4AF37` **tidak pernah dipakai sebagai teks di atas putih** (kontras ±2:1). Di atas putih pakai `#8A6D12`; gold asli hanya di atas navy atau sebagai garis dekoratif.

### 5.2 Tipografi
- Judul: **Source Serif 4** (500/600), memberi kesan laporan resmi.
- Isi dan UI: **Inter** (400/500).
- Angka statistik: Source Serif 4, ukuran 72–96px, `font-variant-numeric: lining-nums`.

### 5.3 Layout
- **Header:** dua baris. Baris atas tipis navy (pengalih bahasa, tautan "← Derco Bara Energy"). Baris bawah putih dengan menu **bernomor** `01 Beranda · 02 Tentang Kami · 03 Produk · 04 Operasi · 05 Mutu & Kepatuhan · 06 Kontak`.
- **Grid editorial 12 kolom** dengan komposisi asimetris (7/5, 8/4). Garis tipis horizontal memisahkan section, seperti halaman majalah.
- **Footer:** navy, indeks halaman, kontak, dan tautan kembali ke holding.

### 5.4 Susunan Halaman
| Halaman | Isi dan tata letak |
|---|---|
| **Beranda** | Hero split 7/5: kiri putih (judul serif, deskripsi, 2 tombol); kanan panel navy dengan **3 statistik besar** bertumpuk dan garis gold. Lalu: "Sekilas" (4 angka besar, counter), ringkasan produk (baris daftar), peta operasi mini, strip sertifikat, CTA navy |
| **Tentang Kami** | Judul besar di kiri, narasi di kanan (kolom sempit). Timeline horizontal. Visi-misi dua kolom. Struktur kepemilikan sederhana |
| **Produk** | **Baris editorial, bukan kartu**: nama produk, ringkasan, 3 spesifikasi inline (CV · TM · Ash), panah. Klik membuka detail dengan **tabel spesifikasi** lengkap dan tombol unduh |
| **Operasi** | Peta sederhana dengan pin, di sampingnya daftar lokasi (tambang, stockpile, pelabuhan). Timeline vertikal alur produksi dari tambang ke kapal |
| **Mutu & Kepatuhan** | Daftar baris: nama sertifikat, penerbit, nomor, masa berlaku, tombol unduh PDF. Kelompok per kategori (sertifikat, izin, kebijakan, laporan) |
| **Kontak** | Dua kolom: form kiri, info dan peta kanan |

### 5.5 Gerak
Counter angka naik (Anime.js) saat terlihat; garis gold memanjang dari kiri (Motion, `scaleX`); baris produk bergeser 8px saat hover.

---

## 6. Tema Korin Bara Energy — "Field Rail" (status: draft)

**Gagasan:** hangat dan membumi, seperti lapangan tambang. Bidang gold dan foto lebar tanpa tepi mendominasi, navy menjadi warna teks. Navigasi berupa **rail vertikal** di kiri, yang membuat situs ini langsung terasa berbeda dari saudaranya.

> Tema ini **dibuat penuh tetapi tidak tayang** sampai status perusahaan diubah menjadi `published` di CMS (lihat PRD, FR-VIS).

### 6.1 Palet
| Peran | Hex |
|---|---|
| Latar | `#FFFFFF` |
| Cream (surface hangat) | `#FAF4E4` |
| Brass gold (bidang besar) | `#C9962B` |
| Gold gelap (teks/garis di atas putih) | `#7A5A10` |
| Ink navy | `#13203F` |
| Slate | `#4F566B` |
| Line | `#EADFC6` |

### 6.2 Tipografi
- Judul: **Barlow Condensed** (600/700), huruf kapital, letter-spacing `0.02em`.
- Isi: **Barlow** (400/500).
- Rasa: tegas, industrial, mudah dibaca dari jauh.

### 6.3 Layout
- **Rail kiri tetap** (lebar 88px desktop): logo vertikal di atas, ikon menu dengan label muncul saat hover/expand, pengalih bahasa di bawah. Mobile: berubah menjadi bar bawah dengan 5 ikon + menu "Lainnya".
- **Konten full-bleed**: foto menembus tepi kanan, tidak ada container sempit pada hero.
- **Panel gold** menimpa foto sebagai kotak informasi (judul + tombol navy).
- **Footer:** navy dengan blok gold untuk CTA kontak.

### 6.4 Susunan Halaman
| Halaman | Isi dan tata letak |
|---|---|
| **Beranda** | Hero full-bleed foto tambang, panel gold di kiri bawah berisi judul dan tombol. Strip 4 angka di bawah hero. "Apa yang kami lakukan" dalam 3 tile besar. Pita cream dengan foto operasi. CTA gold |
| **Tentang Kami** | Opening statement besar satu kalimat. Foto lebar. Dua kolom visi-misi. Nilai perusahaan sebagai deretan 4 kartu bergaris atas gold |
| **Produk** | Tile besar dua kolom dengan label sudut gold (mis. kategori kalori). Detail produk: foto lebar, spesifikasi dalam kotak cream |
| **Operasi** | **Strip proses horizontal dengan scroll-snap** (tambang → pengangkutan → stockpile → pengapalan), tiap langkah satu kartu foto. Di bawahnya daftar lokasi |
| **Mutu & Kepatuhan** | Grid kartu dokumen dengan ikon, nama, masa berlaku, tombol unduh |
| **Kontak** | Form di atas panel gold lebar, info dan peta di bawah |

---

## 7. Tema Beramal Sukses Sejahtera — "Calm Flow"

**Gagasan:** ringan, ramah, dan jernih seperti air. Banyak ruang kosong, sudut membulat besar, pola gelombang halus. Dark water memberi bobot, cerulean memberi kesegaran.

### 7.1 Palet
| Peran | Hex |
|---|---|
| Latar | `#FFFFFF` |
| Mist (surface) | `#E6F3F8` |
| Dark water (ink dan blok gelap) | `#0C3547` |
| Cerulean (aksen) | `#007BA7` |
| Cerulean terang (dekoratif) | `#5BB8D6` |
| Slate | `#4A5B66` |
| Line | `#D5E6EE` |

Kontras: cerulean `#007BA7` di atas putih ±4,8:1, aman untuk teks dan tombol.

### 7.2 Tipografi
- Judul dan isi: **Manrope** (400/500/700, judul 700 dengan letter-spacing `-0.01em`).
- Rasa: modern, ramah, tetap serius.

### 7.3 Layout
- **Header melayang (pill):** kapsul putih bersudut membulat penuh, menggantung 16px dari atas, bayangan `shadow-md`, sedikit transparan saat scroll.
- **Radius besar:** kartu 24px, gambar 28px, tombol pill.
- **Pemisah gelombang:** SVG gelombang halus antar section berganti Mist dan putih.
- **Footer:** dark water dengan pola gelombang tipis, CTA cerulean.

### 7.4 Susunan Halaman
| Halaman | Isi dan tata letak |
|---|---|
| **Beranda** | Hero putih: kiri teks dan tombol cerulean, kanan foto membulat besar dengan **bentuk organik cerulean** di belakangnya. Lalu: 3 kartu ikon "Layanan Utama", angka kunci dalam kapsul, ringkasan tentang, testimoni/mitra (logo), CTA dark water |
| **Tentang Kami** | Judul tengah, paragraf pembuka, foto lebar membulat, tiga langkah/nilai dalam stepper horizontal |
| **Produk** | Kartu ikon-foto 3 kolom. Label halaman dapat diubah di CMS (mis. "Layanan & Produk") karena judul halaman bisa diedit per perusahaan |
| **Operasi** | **Stepper alur kerja** (langkah bernomor di garis cerulean) + kartu fasilitas |
| **Mutu & Kepatuhan** | **Accordion** per kategori, isi daftar dokumen dengan tombol unduh |
| **Kontak** | Kartu form di tengah, info kontak dalam tiga kapsul |

> **Asumsi:** bidang usaha PT Beramal Sukses Sejahtera belum dipastikan. Struktur di atas netral (layanan/produk, alur kerja, fasilitas), dan ilustrasi final akan disesuaikan setelah bidang usahanya jelas.

---

## 8. Komponen Bersama (`resources/js/ui/`)

Komponen primitif tanpa gaya identitas. Warna, radius, dan font diambil dari token tema.

| Komponen | Fungsi |
|---|---|
| `Container` | Lebar sesuai token `narrow/default/wide` |
| `Section` | Padding vertikal standar, prop `tone` |
| `Button` | Varian `solid`, `outline`, `ghost`, ukuran `md/lg` |
| `Eyebrow`, `Heading`, `Prose` | Tipografi dasar |
| `Reveal` | Pembungkus animasi muncul saat scroll (Motion) |
| `Counter` | Angka naik (Anime.js) |
| `Image` | `<picture>` responsif, lazy, rasio tetap, placeholder |
| `FormField`, `Input`, `Textarea` | Form dengan label, error, ARIA |
| `LanguageSwitch` | Memakai `useLocale()` |

Aturan: komponen di `ui/` **tidak boleh** memuat warna hex atau nama perusahaan.

---

## 9. Pustaka Blok

Setiap blok memiliki satu skema data (backend) dan satu tampilan per tema (frontend).

| Tipe blok | Jenis | Isi | Dipakai di |
|---|---|---|---|
| `hero` | Statis | Eyebrow, judul, deskripsi, tombol, gambar | Semua beranda |
| `split_content` | Statis | Gambar + teks, posisi kiri/kanan | Tentang, Beranda |
| `stacked_images` | Statis | Dua gambar offset + teks (khas holding) | Holding |
| `color_band` | Statis | Pita berwarna + gambar + CTA | Holding |
| `mission_list` | Statis | Judul + daftar poin | Visi-misi |
| `rich_text` | Statis | Teks panjang | Tentang |
| `cta_banner` | Statis | Judul + tombol | Hampir semua |
| `timeline` | Statis | Daftar peristiwa | Tentang |
| `values_grid` | Statis | Kartu nilai | Tentang |
| `subsidiary_grid` | Dinamis | Anak usaha berstatus `published` | Holding |
| `stats` | Dinamis | `company_stats` | Beranda |
| `product_list` | Dinamis | `products` | Beranda, Produk |
| `operation_list` | Dinamis | `operations` | Operasi |
| `certification_list` | Dinamis | `certifications` | Mutu & Kepatuhan |
| `contact_info` | Dinamis | `company_contacts` | Kontak, Footer |
| `contact_form` | Dinamis | Form pesan | Kontak |

Blok yang tidak punya tampilan di sebuah tema akan memakai **tampilan fallback** bawaan tema (kartu atau daftar sederhana), sehingga halaman tidak pernah kosong.

---

## 10. Gerak dan Animasi

**Pembagian alat:**
- **Motion (`motion/react`):** reveal saat scroll, hover, layout animation, transisi halaman, menu mobile, accordion. (Framer Motion dan Motion adalah pustaka yang sama; cukup satu paket.)
- **Anime.js:** counter angka, animasi path SVG (garis peta, gelombang), timeline kompleks.

**Preset tunggal** di `resources/js/motion/presets.ts`: `fadeUp`, `fadeIn`, `stagger`, `slideInLeft`, `scaleX`. Tidak boleh mendefinisikan animasi ad hoc di komponen tema.

**Aturan:**
- Reveal: naik 24px + fade, 600ms, `ease-out`, `viewport={{ once: true, amount: 0.2 }}`.
- Stagger antar item: 80ms.
- Hanya animasikan `transform` dan `opacity`.
- `prefers-reduced-motion`: semua animasi gerak diganti fade 150ms; counter langsung menampilkan angka akhir.
- **Transisi antar tema:** saat pindah dari holding ke anak usaha, layar memudar 250ms dan Layout baru muncul dengan fade-in. Tujuannya agar perpindahan terasa disengaja.

---

## 11. Gambar dan Placeholder

Konten asli belum tersedia, jadi:
- Semua gambar memakai **komponen `Image` dengan rasio tetap** (`16/9`, `4/3`, `1/1`, `3/4`), sehingga tidak ada layout shift saat foto asli menggantikan placeholder.
- Placeholder berupa SVG lokal berwarna netral sesuai tema, berlabel rasio. **Tidak memakai layanan gambar eksternal.**
- Foto asli: WebP/AVIF, lebar 480/768/1200/1920, `loading="lazy"` kecuali hero (`fetchpriority="high"`).
- Teks alternatif wajib diisi di CMS untuk setiap gambar.
- Teks placeholder ditulis nyata dan singkat (bukan lorem ipsum) agar kerangka halaman dapat dinilai realistis, dan ditandai `[PLACEHOLDER]` di data seeder. Teks tidak memakai bahasa promosi klise (lihat bagian 1.1): tulis faktual dan netral, misalnya "Penambangan dan penjualan batu bara dengan izin usaha pertambangan sendiri", bukan "Solusi energi terdepan untuk masa depan".

---

## 12. Aksesibilitas (target WCAG 2.2 AA)

- Kontras teks minimal 4,5:1 (3:1 untuk teks besar ≥ 24px atau tebal ≥ 19px).
- Aturan khusus: gold dan tembaga terang **bukan warna teks di atas putih**.
- Semua elemen interaktif dapat dioperasikan dengan keyboard, dengan `:focus-visible` berupa ring 2px aksen tema + offset 2px.
- Tautan "Lewati ke konten" di awal setiap Layout.
- Struktur heading berurutan (satu `h1` per halaman).
- Form: label terlihat, pesan error terhubung via `aria-describedby`, status kirim diumumkan lewat `aria-live`.
- Atribut `lang` pada `<html>` mengikuti bahasa aktif.
- Target sentuh minimal 44×44px.

---

## 13. Struktur Berkas Tema

```
resources/
├── css/
│   ├── app.css                  token global
│   └── themes/
│       ├── holding.css
│       ├── korin-berkat.css
│       ├── korin-bara.css
│       └── beramal.css
└── js/
    ├── ui/                      komponen primitif
    ├── motion/presets.ts
    └── themes/
        ├── registry.ts
        ├── holding/{Layout.tsx, blocks/, index.ts}
        ├── korin-berkat/{...}
        ├── korin-bara/{...}
        └── beramal/{...}
```

Menambah tema baru = satu folder di `themes/`, satu berkas CSS token, satu baris di `registry.ts`, dan satu nilai pada enum `CompanyTheme`.

---

## 14. Checklist Kualitas Desain (sebelum rilis)

- [ ] Setiap halaman lolos uji "30 detik": pembaca paham apa, siapa, dan ke mana langkah berikutnya.
- [ ] Hanya satu `h1`, hierarki heading berurutan.
- [ ] Tidak ada warna hex di luar berkas token tema.
- [ ] Kontras teks lolos pada semua tema.
- [ ] Tampilan benar di 360px, 768px, 1280px, dan 1920px.
- [ ] Seluruh animasi mati dengan benar saat `prefers-reduced-motion`.
- [ ] Gambar punya rasio tetap dan teks alternatif.
- [ ] Kedua bahasa (ID/EN) diperiksa: teks tidak meluap atau terpotong.
- [ ] Anak usaha berstatus draft tidak muncul di menu, grid, sitemap, atau mesin pencari.
- [ ] Semua uji pada bagian 1.1 lulus untuk setiap halaman utama pada tiap tema (kabur, tiga detik, tukar tema, template, copy, referensi).
- [ ] Tidak ada ciri "tampilan AI" dari tabel bagian 1.1 (gradasi neon, hero berformula, kartu ikon seragam di setiap section, copy klise, emoji sebagai ikon).
- [ ] Hanya satu keluarga ikon, maksimal dua keluarga huruf per tema, dan aksen tidak dipakai di lebih dari elemen penting.
- [ ] Skill dari folder `.agents/skills/ui-ux-pro-max` yang relevan sudah dibaca dan diterapkan; skill yang dipakai dicantumkan di laporan fase.
