# DESIGN.md — PT Derco Bara Energy

Versi 1.5 · Dokumen sistem desain untuk website holding dan tiga anak usaha.
Dokumen pendamping: [PRD.md](./PRD.md) · [README.md](./README.md) · [docs/CMS-DESIGN.md](./docs/CMS-DESIGN.md)

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
| `korin-bara` | PT Korin Bara Energy | Putih | Hangat, padat, full-bleed | **Tayang (Published)** |
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
  --font-heading: "Helvetica Neue", Helvetica, Arial, "Liberation Sans", sans-serif;
  --font-body: "Helvetica Neue", Helvetica, Arial, "Liberation Sans", sans-serif;
  --radius-card: 12px;
  --radius-button: 8px;
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
- **Helvetica Font Stack:** `"Helvetica Neue", Helvetica, Arial, "Liberation Sans", sans-serif` untuk seluruh judul dan isi (menggantikan DM Sans).
- Hanya memakai berat **400 (regular)** dan **700 (bold)** agar konsisten di seluruh sistem fallback tanpa layout shift.
- Judul section berkarakter tebal, huruf kecil-kapital normal (bukan semua kapital).
- Label kecil di atas judul (eyebrow): 12px, huruf kapital, letter-spacing `0.12em`, warna tembaga, diberi garis tembaga pendek di bawahnya (seperti "WHO WE ARE" pada referensi).

| Level | Desktop | Mobile |
|---|---|---|
| Display (hero) | 64/68 · 700 | 40/44 · 700 |
| H2 | 40/46 · 700 | 30/36 · 700 |
| H3 | 24/32 · 700 | 20/28 · 700 |
| Body | 16/28 · 400 | 16/26 · 400 |
| Small | 14/22 · 400 | 14/22 · 400 |

### 4.3 Layout Header dan Footer
**Header:** latar Charcoal, logo kiri (`Logo` varian terang/dark surface, tinggi 48px menyusut ke 36px saat sticky, rasio asli terjaga, `aria-label="PT Derco Bara Energy, Beranda"` / `"... Home"`), menu tengah (`Beranda · Tentang Kami · Bisnis Kami`, huruf kapital 12px, item aktif bergaris bawah tembaga), kanan: pengalih bahasa `ID | EN` dan teks link "Hubungi Kami" (bukan tombol, tanpa border/latar, gaya tipografi identik dengan item menu lain, hover/aktif garis bawah tembaga, touch target 44×44px). Header sticky, menyusut tinggi saat scroll. Mobile: logo tinggi 32px, menu slide dari kanan dengan "Hubungi Kami" sebagai item terakhir.
- Slug halaman kontak holding: `/id/hubungi-kami` dan `/en/contact-us` (301 redirect dari `/id/kontak` dan `/en/contact`). Tiga anak usaha tetap mempertahankan `/kontak` dan `/contact`.

**Footer:** latar Charcoal Deep, tiga kolom: (1) deskripsi singkat + logo (`Logo` varian terang/dark surface, tinggi 44px, tanpa tautan), (2) alamat, (3) jam operasional. Baris bawah: hak cipta dan tautan ke anak usaha.

### 4.4 Beranda — Susunan Section

| # | Section | Latar | Tata letak |
|---|---|---|---|
| 1 | **Hero** | Charcoal | Kiri: eyebrow, judul besar, 2 baris deskripsi, tombol tembaga agak rounded (`--radius-button: 8px`). Kanan: foto alat berat yang **menembus tepi kanan dan bawah** hero (offset negatif) |
| 2 | **Bisnis Kami** | Charcoal | Kartu asli (`--radius-card: 12px`) untuk anak usaha berstatus `published`. Grid adaptif (2 kolom saat Korin Bara draft, 3 kolom saat published, 1 kolom di mobile). Setiap kartu memiliki foto 4:3, nama perusahaan, deskripsi maksimal 3 baris, dan tombol full-width "Lihat Selengkapnya". Efek hover: lift -4px + bayangan halus. Judul section dipertahankan semantik untuk SEO/a11y (`h2.sr-only`) tanpa tampilan visual. |
| 3 | **Tentang Kami (ringkas)** | Putih | Kiri: **dua foto bertumpuk offset** (besar atas, kecil bawah menjorok). Kanan: paragraf tebal pembuka Derco holding, paragraf isi, tombol tembaga rounded 8px. Heading section dipertahankan semantik (`h2.sr-only`). |
| 4 | **Pita Komitmen** | Tembaga penuh | Kiri: panel tembaga dengan judul putih, ikon sertifikat, 1 kalimat, tombol putih rounded 8px "Mutu & Kepatuhan". Kanan: foto udara tambang tanpa tepi. Heading section semantik (`h2.sr-only`). |
| 5 | **Visi dan Misi** | Putih | Kiri: foto bertumpuk (versi cermin dari section 3). Kanan: daftar 3 poin dengan ikon lingkaran tembaga, paragraf, tombol rounded 8px. Heading section semantik (`h2.sr-only`). |
| 6 | **Kartu Kontak** | Putih, kartu Charcoal | Kartu lebar di tengah (`--radius-card: 12px`): ikon garis tembaga, "Ada pertanyaan?", nomor telepon besar berwarna tembaga (Helvetica 700), tombol putih rounded 8px "Kirim Pesan". Heading section semantik (`h2.sr-only`). |
| 7 | **Footer** | Charcoal Deep | Lihat 4.3 |

Ritme warna: **gelap → gelap → putih → tembaga → putih → putih/kartu gelap → gelap.** Pita tembaga adalah pemecah ritme yang menjadi ciri khas halaman.

### 4.5 Halaman Holding Lainnya
- **Tentang Kami:**
  1. Hero Carousel (3 gambar berganti crossfade 800ms, interval 6s, dark overlay WCAG AA).
  2. Split Content: dua foto bertumpuk offset di kiri; di kanan paragraf pembuka profil PT Derco Bara Energy + paragraf "Area Operasional: ... berpusat di [ISI WILAYAH OPERASIONAL]".
  3. Visi & Misi grup.
  4. Nilai Perusahaan (`ValuesGridBlock`).
  5. Struktur Grup (`SubsidiaryGridBlock` struktur kepemilikan holding → anak usaha).
  6. CTA Banner "Hubungi Kami".
  *(Catatan: Blok Timeline/sejarah ditiadakan pada holding).*
- **Bisnis Kami:**
  1. Hero Carousel (3 gambar berganti crossfade 800ms, interval 6s).
  2. Satu blok per anak usaha bergantian kiri-kanan (foto, nama, deskripsi singkat, tombol "Kunjungi Situs"). *(Catatan: Perhitungan/key stats angka dihilangkan).*
- **Hubungi Kami (`/id/hubungi-kami`, `/en/contact-us`):** dua kolom. Kiri: info kontak (alamat, telepon, email, jam, peta). Kanan: formulir pesan kemitraan B2B.

### 4.6 Komponen Khas Holding
- **Foto bertumpuk offset:** dua gambar, yang kecil bergeser `-48px` ke bawah dan `+48px` ke samping, saling menimpa 24px.
- **Pita tembaga:** section dua kolom, satu sisi warna solid, satu sisi foto. Di mobile menumpuk (teks dulu, foto di bawah).
- **Garis eyebrow:** 40px × 2px tembaga di bawah label kecil.
- **HeroCarousel:** carousel 3–5 gambar otomatis dengan transisi fade halus (800ms, 6s autoplay), dark overlay WCAG AA (≥ 4.5:1), tombol indikator slide bernavigasi accessible (`role="tablist"` / `role="tab"`), jeda saat hover/fokus, dan reduced-motion support.

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
- Judul dan isi: **Manrope** (`@fontsource-variable/manrope`), bobot 400, 500, dan 700.
- Angka statistik dan tabel spesifikasi: Manrope 700, dengan `font-variant-numeric: tabular-nums` untuk perataan angka yang rapi dan tegas.
- *(Catatan: Source Serif 4 dan Inter dihapus sepenuhnya demi kesatuan visual dan efisiensi aset).*

### 5.3 Layout
- **Header:** dua baris. Baris atas tipis navy (pengalih bahasa, tautan "← Derco Bara Energy"). Baris bawah putih dengan logo kiri (`Logo` varian asli untuk latar terang, tinggi 40px, `aria-label="PT Korin Berkat Energy, Beranda"` / `"... Home"`) dan menu **bernomor dinamis** mengikuti jumlah halaman aktif (`01 Beranda · 02 Tentang Kami · 03 Produk · 04 Operasi · 05 Kontak`).
- **Grid editorial 12 kolom** dengan komposisi asimetris (7/5, 8/4). Garis tipis horizontal memisahkan section, seperti halaman majalah.
- **Footer:** navy, logo kiri (`Logo` varian terang/dark surface, tinggi 44px, tanpa tautan), indeks halaman, kontak, dan tautan kembali ke holding.

### 5.4 Susunan Halaman (5 Halaman Aktif)
| Halaman | Isi dan tata letak |
|---|---|
| **Beranda** | Hero split 7/5: kiri putih (judul Manrope, deskripsi, 2 tombol); kanan panel navy dengan **3 statistik besar** bertumpuk dan garis gold. Section setelah hero tidak menampilkan heading visual (`sr-only`): 4 angka sekilas operasional, ringkasan produk (tanpa ikon panah dan tanpa tombol unduh), jaringan operasional (tanpa tombol tautan), dan CTA navy. Bagian Kepatuhan dan Mutu dihapus sepenuhnya dari beranda |
| **Tentang Kami** | Judul besar di kiri, narasi di kanan. Visi-misi dua kolom. Section "Tahapan Operasional" (timeline) dihapus. Section "Standar Kerja" diganti menjadi **"Nilai Perusahaan"** (`ValuesGridBlock`) dengan 4 nilai editorial bernomor (Integritas & Kepatuhan, Keandalan Pasokan, Keselamatan Kerja, Efisiensi Operasional) dalam tata letak 3 kolom garis hairline |
| **Produk** | **Layout 2-kolom editorial dengan tabel spesifikasi langsung**: kolom kiri menampilkan nama produk dan ringkasan aplikasi industri; kolom kanan menampilkan tabel parameter teknis lengkap (CV, TM, IM, Ash, VM, FC, TS) dengan angka rata kanan. Tidak menggunakan tautan panah atau tombol unduh ke halaman detail terpisah |
| **Operasi** | Peta sederhana dengan pin, di sampingnya daftar lokasi (tambang, stockpile, pelabuhan) dan fasilitas operasional |
| **Mutu & Kepatuhan** | *Dihapus permanen* dari navigasi, database, dan sitemap (rute mengembalikan 404) |
| **Kontak** | Dua kolom: formulir kemitraan kiri, info kontak dan alamat kanan |

### 5.5 Gerak
Counter angka naik (Anime.js) saat terlihat; garis gold memanjang dari kiri (Motion, `scaleX`); baris produk bergeser 8px saat hover.

---

## 6. Tema Korin Bara Energy — "Field Rail" (status: tayang)

**Gagasan:** hangat dan membumi, seperti lapangan tambang. Bidang gold dan foto lebar tanpa tepi mendominasi, navy menjadi warna teks. Navigasi berupa **rail vertikal** di kiri, yang membuat situs ini langsung terasa berbeda dari saudaranya.

> Tema ini **tayang penuh** dengan status perusahaan `published` di basis data (lihat PRD, FR-VIS). Enam halaman aktif dan detail produk dapat diakses secara publik.

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
- **Rail kiri tetap** (lebar 88px desktop, latar putih): logo vertikal di atas (`Logo` varian ringkas/compact icon, tinggi 32px; tinggi 40px saat rail expand, `on="light"`), ikon menu dengan label muncul saat hover/expand, pengalih bahasa di bawah. Mobile: bar atas putih dengan logo compact (tinggi 32px) dan bar bawah dengan 5 ikon + menu "Lainnya".
- **Konten full-bleed**: foto menembus tepi kanan, tidak ada container sempit pada hero.
- **Panel gold** menimpa foto sebagai kotak informasi (judul + tombol navy).
- **Footer:** navy dengan logo (`Logo` varian terang/dark surface, tinggi 44px) dan blok gold untuk CTA kontak.

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
- **Header melayang (pill):** kapsul putih bersudut membulat penuh, menggantung 16px dari atas, bayangan `shadow-md`, sedikit transparan saat scroll. Logo di kiri (`Logo` varian asli untuk latar terang, tinggi 36px, `aria-label="PT Beramal Sukses Sejahtera, Beranda"` / `"... Home"`).
- **Tautan Kontak di Header:** tombol CTA solid pill dihapus; digantikan dengan satu teks link "Hubungi Kami" (EN: "Contact Us") di sebelah kanan pengalih bahasa `ID | EN` (tanpa latar/border, touch target 44×44px, underline cerulean saat aktif). Menu tengah hanya memuat halaman non-kontak (`Beranda · Tentang Kami · Layanan & Produk · Operasi`).
- **Radius besar:** kartu 24px, gambar 28px, tombol pill.
- **Pemisah gelombang:** SVG gelombang halus antar section berganti Mist dan putih.
- **Footer:** dark water dengan logo (`Logo` varian terang/dark surface, tinggi 44px), pola gelombang tipis, CTA cerulean.

### 7.4 Susunan Halaman (5 Halaman Aktif, 1 Disembunyikan)
| Halaman | Isi dan tata letak |
|---|---|
| **Beranda** | Hero putih: kiri teks dan tombol cerulean, kanan foto membulat besar dengan bentuk organik cerulean di belakangnya. Section setelah hero tidak menampilkan heading visual (`sr-only`): angka operasional (stats), ringkasan layanan & produk (tanpa ikon panah dan tanpa tombol unduh), dan CTA dark water. Bagian Jejak Infrastruktur (Operasi) serta Kepatuhan & Mutu dihapus dari beranda |
| **Tentang Kami** | Judul tengah, paragraf pembuka, foto lebar membulat, nilai layanan dalam stepper. Section "Tahapan Operasional" (timeline) dihapus |
| **Produk / Layanan & Produk** | Kartu ikon-foto 3 kolom dengan CalmHeader lengkap (deskripsi pengantar). Label halaman dapat disesuaikan lewat CMS |
| **Operasi** | Stepper alur kerja (langkah bernomor di garis cerulean) + kartu fasilitas |
| **Mutu & Kepatuhan** | **Dinonaktifkan** (`is_published=false`, `show_in_nav=false`). Tidak muncul di menu, beranda, maupun sitemap. URL publik 404, tetapi admin dapat mempratinjau via CMS |
| **Hubungi Kami** | Halaman kontak dinamai "Hubungi Kami" / "Contact Us" (slug rute `/id/beramal/hubungi-kami` dan `/en/beramal/contact-us` dengan 301 redirect dari `/kontak` dan `/contact`). Kartu form di tengah, info kontak dalam tiga kapsul |

> **Asumsi:** bidang usaha PT Beramal Sukses Sejahtera belum dipastikan. Struktur di atas netral (layanan/produk, alur kerja, fasilitas), dan ilustrasi final akan disesuaikan setelah bidang usahanya jelas.

---

## 8. Komponen Bersama (`resources/js/ui/`)

Komponen primitif tanpa gaya identitas. Warna, radius, dan font diambil dari token tema.

| Komponen | Fungsi |
|---|---|
| `Container` | Lebar sesuai token `narrow/default/wide` |
| `Section` | Padding vertikal standar, prop `tone` |
| `Button` | Varian `solid`, `outline`, `ghost`, ukuran `md/lg`, membaca `--radius-button` |
| `Logo` | Logo perusahaan adaptif: prop `on="light"|"dark"`, `size="sm"|"md"|"lg"|number`, `compact`, `plat`, fallback wordmark, clear space half-height, tanpa filter CSS |
| `Eyebrow`, `Heading`, `Prose` | Tipografi dasar |
| `Reveal` | Pembungkus animasi muncul saat scroll (Motion) |
| `Counter` | Angka naik (Anime.js) |
| `Image` | `<picture>` responsif, lazy, rasio tetap, placeholder |
| `HeroCarousel` | Carousel 3–5 gambar otomatis dengan transisi fade halus (800ms, 6s autoplay), dark overlay WCAG AA, pagination dots accessible, pause on hover/focus |
| `FormField`, `Input`, `Textarea` | Form dengan label, error, ARIA |
| `LanguageSwitch` | Memakai `useLocale()` |

### 8.1 Aturan Penggunaan Logo
- **Sumber Tunggal:** Konfigurasi dipusatkan di `config/branding.php` dan dikirimkan oleh backend lewat `CompanyResource` (`logo: { light, dark, compact, width, height, alt }`). Komponen React tidak menulis path berkas langsung.
- **Konvensi Varian:**
  - `logo.light`: Logo untuk latar **terang** (berkas asli dengan teks/tinta gelap).
  - `logo.dark`: Logo untuk latar **gelap** (varian turunan dengan teks/tinta putih, di mana simbol warna asli dipertahankan).
  - `logo.compact`: Versi ringkas/ikon untuk ruang sempit (rail Korin Bara).
- **Integritas Merek:** Dilarang menggunakan filter CSS (`filter: brightness/invert`) atau mewarnai ulang simbol. Ukuran proporsional sesuai rasio asli.
- **Ukuran Standar:** Header 36–48px (holding 48px menyusut ke 36px saat sticky; korin-berkat 40px; beramal 36px; korin-bara rail 32/40px). Footer 44px di seluruh tema.
- **Aksesibilitas & Tautan:** Logo header dibungkus tautan beranda dengan accessible label lengkap (`{Nama Perusahaan}, Beranda` / `{Company Name}, Home`). Logo footer berupa gambar murni tanpa tautan untuk menghindari redundansi navigasi. `alt` selalu memakai nama resmi perusahaan.
- **Fallback:** Jika logo tidak tersedia, komponen `Logo` menampilkan wordmark teks tebal accessible (`font-heading font-bold`).

Aturan: komponen di `ui/` **tidak boleh** memuat warna hex atau nama perusahaan.

---

## 9. Pustaka Blok

Setiap blok memiliki satu skema data (backend) dan satu tampilan per tema (frontend).

| Tipe blok | Jenis | Isi | Dipakai di |
|---|---|---|---|
| `hero` | Statis | Eyebrow, judul, deskripsi, tombol, gambar statis atau `slides` (3–5 gambar carousel) | Semua beranda, Tentang Kami holding, Bisnis Kami holding |
| `split_content` | Statis | Gambar + teks, posisi kiri/kanan | Tentang, Beranda |
| `stacked_images` | Statis | Dua gambar offset + teks (khas holding) | Holding |
| `color_band` | Statis | Pita berwarna + gambar + CTA | Holding |
| `mission_list` | Statis | Judul + daftar poin | Visi-misi |
| `rich_text` | Statis | Teks panjang | Tentang |
| `cta_banner` | Statis | Judul + tombol | Hampir semua |
| `timeline` | Statis | Daftar peristiwa | Tentang (anak usaha korin-bara; dihapus dari holding, korin-berkat, beramal) |
| `values_grid` | Statis | Kartu nilai | Tentang (korin-berkat: 4 nilai editorial 3-kolom) |
| `subsidiary_grid` | Dinamis | Anak usaha berstatus `published` (varian: cards, alternating, structure) | Holding |
| `stats` | Dinamis | `company_stats` | Beranda (korin-berkat & beramal: sr-only header) |
| `product_list` | Dinamis | `products` | Beranda, Produk (korin-berkat: tabel spesifikasi lengkap langsung tanpa download) |
| `operation_list` | Dinamis | `operations` | Operasi (dihapus dari beranda beramal) |
| `certification_list` | Dinamis | `certifications` | Mutu & Kepatuhan (korin-bara, beramal draft; dihapus permanen dari korin-berkat dan beranda beramal) |
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

- [x] Setiap halaman lolos uji "30 detik": pembaca paham apa, siapa, dan ke mana langkah berikutnya.
- [x] Hanya satu `h1`, hierarki heading berurutan.
- [x] Tidak ada warna hex di luar berkas token tema.
- [x] Kontras teks lolos pada semua tema (axe WCAG 2.2 AA otomatis lulus di seluruh suite).
- [x] Logo tampil tajam di header dan footer seluruh tema dengan varian kontras yang tepat (tanpa filter CSS, rasio proporsional, accessible alt & link).
- [x] Tampilan benar di 360px, 768px, 1280px, dan 1920px.
- [x] Seluruh animasi mati dengan benar saat `prefers-reduced-motion`.
- [x] Gambar punya rasio tetap dan teks alternatif.
- [x] Kedua bahasa (ID/EN) diperiksa: teks tidak meluap atau terpotong.
- [x] Anak usaha berstatus draft tidak muncul di menu, grid, sitemap, atau mesin pencari.
- [x] Semua uji pada bagian 1.1 lulus untuk setiap halaman utama pada tiap tema (kabur, tiga detik, tukar tema, template, copy, referensi).
- [x] Tidak ada ciri "tampilan AI" dari tabel bagian 1.1 (gradasi neon, hero berformula, kartu ikon seragam di setiap section, copy klise, emoji sebagai ikon).
- [x] Hanya satu keluarga ikon, maksimal dua keluarga huruf per tema, dan aksen tidak dipakai di lebih dari elemen penting.
- [x] Skill dari folder `.agents/skills/ui-ux-pro-max` yang relevan sudah dibaca dan diterapkan; skill yang dipakai dicantumkan di laporan fase.

---

## 15. Catatan Perubahan (Changelog)

### Versi 1.5 (Revisi 4 — Penayangan Korin Bara, Alias Logo, dan Redesain CMS Admin)
- **Penayangan PT Korin Bara Energy:**
  - Status Korin Bara diubah menjadi `published` di seeder dan lewat migrasi data idempoten `2026_11_12_000000_publish_korin_bara_energy`.
  - Seluruh 6 halaman Korin Bara aktif publik (200 OK) tanpa penanda preview dan terdaftar di `sitemap.xml`.
  - Grid Bisnis Kami beranda holding beradaptasi menjadi 3 kolom kartu (`PT Korin Berkat Energy`, `PT Beramal Sukses Sejahtera`, `PT Korin Bara Energy`).
  - Mekanisme draft/published (FR-VIS) dipertahankan utuh untuk entitas pengujian/masa depan.
- **Logo Korin Bara = Logo Korin Berkat:**
  - Konfigurasi `config/branding.php` untuk entri `korin-bara` merujuk langsung ke definisi logo `korin-berkat` via alias memori (tanpa duplikasi definisi atau file).
  - Varian ringkas (compact symbol) diekstrak ke `public/images/logo/Logo-KorinBerkatEnergy-compact.png` untuk rail 88px Korin Bara.
  - Berkas compact lama `Logo-KorinBaraEnergy-compact.png` tidak dihapus (dipertahankan sebagai arsip tidak aktif).
  - Alt text tetap unik semantik ("PT Korin Bara Energy").
- **Redesain Antarmuka CMS Admin (Filament v5):**
  - Mengacu 100% pada foto referensi konsol korporat modern (dokumen spesifikasi [docs/CMS-DESIGN.md](./docs/CMS-DESIGN.md) dan perbandingan [docs/branding/cms-compare/](./docs/branding/cms-compare/)).
  - Tema khusus di `resources/css/filament/admin/theme.css` dengan token warna terpusat (Primary Amber `#EDB856`, Sidebar Dark Navy `#111C2E`, Canvas `#F8FAFC`, Surface `#FFFFFF`, Border `#E2E8F0`).
  - Sidebar custom dengan siluet grafis pegunungan (`sidebar-mountains.png`), pintasan "View Website", dan logout.
  - Halaman login kustom (`resources/views/filament/layouts/auth.blade.php`): split 50:50 dengan backdrop foto pertambangan, tipografi korporat, input modern, pemulihan kata sandi, dan alternatif SSO.
  - 6 Widget dasbor kustom berbasis data riil database:
    1. `DashboardGreetingWidget` (live clock & greeting)
    2. `StatsOverviewWidget` (real DB metrics & sparkline trends)
    3. `HeroBannerWidget` (mining hero photo & badge)
    4. `QuickActionsWidget` (6 shortcut buttons)
    5. `WebsiteOverviewWidget` (analytics trend chart)
    6. `RecentActivityWidget` (live activity timeline)
  - Aksesibilitas WCAG 2.2 AA terverifikasi hijau via axe-core.

### Versi 1.4 (Revisi 3 — Logo di Header dan Footer Seluruh Tema)
- **Komponen Bersama `ui/Logo`:** Komponen mandiri untuk rendering logo beresolusi tinggi, mendukung varian `on="light"|"dark"`, `size="sm"|"md"|"lg"|number`, `compact`, `plat`, `priority`, dan wordmark fallback jika konfigurasi/file belum ada. Dilarang menggunakan filter CSS (brightness/invert).
- **Arsitektur Logo Satu Sumber:** Konfigurasi logo dipusatkan di `config/branding.php`, diproses oleh backend action `ResolveCompanyLogo`, dan disediakan ke frontend melalui `CompanyResource` (`logo: { light, dark, compact, width, height, alt }`). Tipe data TypeScript didefinisikan di `CompanyLogoData`.
- **Skrip Varian Terang Idempoten:** Generator Python `scripts/branding/generate_light_variants.py` (`npm run branding:light`) yang mengonversi tinta gelap menjadi putih bersih dengan menjaga transparansi alpha dan warna simbol merek asli. Lembar pratinjau matriks kontras disimpan di `docs/branding/logo-preview.png`.
- **Integrasi Tema:**
  - `holding`: Header menggunakan varian dark-surface (tinggi 48px menyusut ke 36px saat sticky), mobile 32px; footer menggunakan varian dark-surface (tinggi 44px).
  - `korin-berkat`: Header putih menggunakan varian asli/light-surface (tinggi 40px); footer navy menggunakan varian dark-surface (tinggi 44px).
  - `korin-bara`: Desktop rail putih menggunakan varian compact icon (tinggi 32px / 40px expand); mobile header menggunakan compact icon (tinggi 32px); footer navy menggunakan varian dark-surface (tinggi 44px).
  - `beramal`: Header kapsul putih menggunakan varian asli/light-surface (tinggi 36px); footer dark water menggunakan varian dark-surface (tinggi 44px).
- **Aksesibilitas (WCAG 2.2 AA):** Seluruh tautan logo di header memiliki accessible label (`aria-label="{Nama Perusahaan}, Beranda"` / `"{Company Name}, Home"`). Logo footer berupa gambar murni dengan atribut `alt` lengkap resmi nama perusahaan. Lolos uji axe WCAG 2.2 AA.

### Versi 1.3 (Revisi 2 — Anak Usaha: Korin Berkat & Beramal)
- **Konfigurasi Set Halaman Dinamis:** Menambahkan method `CompanyTheme::pages()` yang memetakan tema ke daftar `PageKey` yang diaktifkan. Navigasi bernomor Korin Berkat otomatis menyesuaikan jumlah item (01-05).
- **Tema Korin Berkat Energy:**
  - Tipografi: Menyatukan font menjadi **Manrope Variable** (400/500/700) untuk heading dan body, dengan `font-variant-numeric: tabular-nums`. Menghapus paket `@fontsource-variable/source-serif-4` dan `@fontsource-variable/inter`.
  - Beranda: Mengubah judul section setelah hero menjadi `sr-only` (Stats, ProductList, OperationList). Menghapus tombol unduh dan tautan panah pada ringkasan produk. Menghapus blok Mutu & Kepatuhan (`certification_list`).
  - Tentang Kami: Menghapus blok Timeline (Tahapan Operasional). Mengubah "Standar Kerja" menjadi "Nilai Perusahaan" / "Our Values" (`ValuesGridBlock`) dengan 4 nilai terstruktur dalam format editorial 3 kolom garis hairline.
  - Produk: Menampilkan tabel spesifikasi teknis lengkap langsung di halaman produk dalam 2-kolom editorial (tanpa tautan panah ke halaman detail terpisah dan tanpa tombol download).
  - Mutu & Kepatuhan: Dihapus permanen dari database, navigasi, dan rute (URL menghasilkan 404).
- **Tema Beramal Sukses Sejahtera:**
  - Header: Mengganti tombol solid pill kontak dengan satu teks link "Hubungi Kami" / "Contact Us" di sisi kanan sebelah pengalih bahasa ID | EN (touch target 44×44px, border-bottom accent cerulean saat aktif).
  - Beranda: Mengubah judul section setelah hero menjadi `sr-only` (Stats, ProductList). Menghapus bagian Jejak Infrastruktur (`operation_list`) dan Kepatuhan & Mutu (`certification_list`). Menghapus tombol panah/unduh pada ringkasan produk.
  - Tentang Kami: Menghapus blok Timeline (Tahapan Operasional).
  - Mutu & Kepatuhan: Dinonaktifkan (`is_published=false`, `show_in_nav=false`), 404 untuk publik, dapat dipratinjau admin.
  - Hubungi Kami: Menamai halaman kontak "Hubungi Kami" / "Contact Us" (slug `/id/beramal/hubungi-kami` & `/en/beramal/contact-us` dengan 301 redirect dari `/kontak` & `/contact`).

### Versi 1.2 (Revisi 1 — Web Utama / Tema Holding)
- **Tipografi:** Mengganti DM Sans dengan Helvetica font stack (`"Helvetica Neue", Helvetica, Arial, "Liberation Sans", sans-serif`) untuk `--font-heading` dan `--font-body` di tema holding. Hanya memakai berat 400 dan 700. Paket `@fontsource-variable/dm-sans` dihapus total.
- **Tombol & Radius:** `--radius-button: 8px` diterapkan pada holding (agak rounded). `--radius-card: 12px` untuk wadah kartu.
- **Header Holding:** Menggabungkan menu "Kontak" dan tombol "Hubungi Kami" menjadi SATU teks link "Hubungi Kami" (EN: "Contact Us") di sisi kanan sebelah pengalih bahasa ID | EN (uppercase, touch target 44×44px, hover/active bottom border copper). Menu tengah menjadi 3 item (`Beranda · Tentang Kami · Bisnis Kami`). Mobile drawer menempatkan "Hubungi Kami" sebagai link terakhir.
- **Rute Kontak Holding:** Mengubah slug kontak holding menjadi `/id/hubungi-kami` dan `/en/contact-us`, disertai 301 permanent redirect dari `/id/kontak` dan `/en/contact`. Tiga anak usaha tetap mempertahankan `/kontak` dan `/contact`.
- **Beranda Holding:**
  - Section "Bisnis Kami" diubah menjadi kartu asli untuk anak usaha published dengan foto 4:3, nama, deskripsi 3 baris, dan tombol "Lihat Selengkapnya".
  - Judul section besar setelah hero disembunyikan secara visual (`sr-only`) untuk kebersihan tampilan dan ritme visual tanpa merusak semantik heading/SEO.
- **Tentang Kami Holding:**
  - Hero menggunakan `HeroCarousel` (3 gambar, auto crossfade 800ms, autoplay 6s, accessible dots, pause on hover/focus).
  - Menghapus blok Timeline/sejarah dari halaman Tentang Kami holding.
  - Memperbarui deskripsi pembuka profil Derco holding dan menambahkan paragraf Area Operasional (`[ISI WILAYAH OPERASIONAL]`).
- **Bisnis Kami Holding:**
  - Hero menggunakan `HeroCarousel` (3 gambar).
  - Menghapus perhitungan / key stats angka dari blok anak usaha; menyisakan foto, nama, deskripsi singkat, dan tombol "Kunjungi Situs".
