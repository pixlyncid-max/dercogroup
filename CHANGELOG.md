# Changelog

Semua perubahan penting pada proyek **PT Derco Bara Energy** didokumentasikan dalam berkas ini. Format mengacu pada [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan mematuhi [Semantic Versioning](https://semver.org/).

---

## [1.0.0] - 2026-11-10

### Ditambahkan (Fase 1 – Fondasi & Arsitektur)
- Struktur Laravel 12 + Inertia.js React 19 + TypeScript strict.
- Migrasi database 10 entitas: `companies`, `pages`, `page_blocks`, `products`, `product_specs`, `operations`, `company_stats`, `certifications`, `company_contacts`, `contact_messages`.
- Enum backed PHP: `CompanyTheme`, `CompanyStatus`, `PageKey`, `BlockType`, `OperationType`, `CertificationCategory`, `ContactType`.
- Arsitektur berbasis Action (`app/Domain/*/Actions`) dengan controller tipis.
- Middleware multibahasa (`SetLocale`) dan resolusi entitas (`ResolveCompany`).
- Model dasar dengan trait translasi Spatie (`spatie/laravel-translatable`), media library Spatie, dan permission Spatie.
- Diagram relasi basis data Mermaid di `docs/erd.md`.

### Ditambahkan (Fase 2 – Sistem Desain & Token)
- Empat tema visual terisolasi via CSS variables bertarget `[data-theme="..."]`:
  - `holding` (Slate/Dark Stone, Tembaga Warm Ochre, Emas Mineral Gelap).
  - `korin-berkat` (Navy Blue Korporat, Tembaga/Gold Terang, Putih Bersih).
  - `korin-bara` (Earth Charcoal, Amber Oranye Tambang, Putih Bersih).
  - `beramal` (Forest/Teal Hijau Laut, Emerald/Navy, Putih Bersih).
- Tipografi profesional: Syne + Plus Jakarta Sans untuk holding, Inter, Outfit, DM Sans untuk anak usaha.
- Kontras warna WCAG 2.2 AA (semua rasio teks di atas 4.5:1, elemen interaktif 3:1).
- Pustaka preset animasi Motion (`resources/js/motion/presets.ts`) dengan dukungan penuh `prefers-reduced-motion`.
- Hook bersama di `resources/js/core`: `useNavigation`, `useLocale`, `useSeo`, `useContactForm`.
- Komponen primitif `ui/`: `Button`, `Section`, `Container`, `Badge`, `Image`, `Card`.

### Ditambahkan (Fase 3 – Beranda Holding Sesuai Referensi Visual)
- Tata letak Beranda Holding 100% presisi mengacu pada `docs/reference/holding-home.jpeg`:
  - Topbar gelap & navbar transparan dengan dropdown anak usaha.
  - Hero banner ekskavator dengan tipografi berjarak rapat.
  - Section "Our Offer" / Unit Bisnis dengan kartu batu bara bertekstur.
  - Komposisi foto bertumpuk asimetris (*layered split*) dengan aksen garis tembaga.
  - Banner tebal bertema tembaga kontras tinggi.
  - Section misi dengan daftar ikon terarah.
  - Footer gelap lengkap dengan kontak SCBD dan jam operasional.

### Ditambahkan (Fase 4 – Enam Halaman Tiga Anak Usaha)
- Satu controller generik (`SubsidiaryPageController`) melayani 18 halaman anak usaha tanpa duplikasi.
- Layout dan identitas visual berbeda 100% untuk ketiga anak usaha:
  - **PT Korin Berkat Energy**: layout korporat maritim, spesifikasi kalori batubara interaktif, filter laboratorium.
  - **PT Beramal Sukses Sejahtera**: layout logistik fasilitas dermaga, diagram koordinasi alur sungai Mahakam, accordion perizinan WAI-ARIA.
  - **PT Korin Bara Energy**: layout operasi tambang terbuka (status Draft, previewable oleh admin, hidden untuk publik).
- 22 halaman standar grup yang dapat diakses penuh dalam `/id` dan `/en`.

### Ditambahkan (Fase 5 – CMS Filament & Fitur Bisnis)
- Panel CMS Filament di `/admin` dengan dua peran: `Super Admin` dan `Editor`.
- Manajemen Halaman dan Blok Konten (tambah, hapus, susun urutan *drag-and-drop*).
- Manajemen Produk & Spesifikasi Teknis dengan penanda *Highlight*.
- Manajemen Operasi & Peta Koordinat.
- Manajemen Sertifikat & Berkas PDF Kepatuhan dwibahasa dengan status kedaluwarsa otomatis.
- Manajemen Pesan Masuk Formulir Kontak B2B dengan status tindak lanjut.
- Fitur penayangan 1-klik untuk mengaktifkan PT Korin Bara Energy dari `Draft` ke `Published`.
- Perlindungan bot formulir kontak dengan honeypot dan rate limiter IP.

### Ditambahkan (Fase 6 – Kinerja, SEO, Aksesibilitas, & Keamanan)
- Code splitting per tema (`ThemedPage.tsx` dynamic import registry).
- Optimasi SSR dan preload aset hero `fetchpriority="high"`.
- Dynamic `sitemap.xml` dwibahasa dengan atribut `xhtml:link rel="alternate" hreflang`.
- Semantic Open Graph meta tags, Twitter Cards, dan Structured Data JSON-LD (`Organization` & `LocalBusiness`).
- Aksesibilitas WCAG 2.2 AA terverifikasi axe-core (skip-to-content link, focus rings, ukuran target sentuh min 44px).
- Header keamanan HTTP lengkap: CSP, X-Content-Type-Options, Referrer-Policy, Strict-Transport-Security.
- Halaman kesalahan bermerek 404 dan 500 adaptif mengikuti tema terakhir.
- Audit anti-tampilan AI (DESIGN.md 1.1) pada seluruh halaman utama.

### Ditambahkan (Fase 7 – Rilis & Deployment)
- `ProductionSeeder` (`database/seeders/ProductionSeeder.php`): seeder minimal produksi via environment variables tanpa data placeholder.
- Skrip deploy otomatis produksi `scripts/deploy.sh` dan skrip cadangan harian `scripts/backup.sh`.
- Konfigurasi Supervisor (`scripts/supervisor/derco.conf`) dan unit systemd (`scripts/systemd/`).
- Dokumentasi serah terima konten klien di `docs/CONTENT-CHECKLIST.md`.
- Buku panduan pengguna CMS admin di `docs/CMS-GUIDE.md`.
- Diagram relasi basis data di `docs/erd.md`.
- Tag rilis resmi `v1.0.0`.

