# Derco Bara Energy — Website

Website company profile **PT Derco Bara Energy** (holding) beserta tiga situs anak usaha: PT Korin Bara Energy, PT Korin Berkat Energy, dan PT Beramal Sukses Sejahtera. Dwibahasa (Indonesia dan Inggris), dengan CMS admin.

| Dokumen | Isi |
|---|---|
| [PRD.md](./PRD.md) | Kebutuhan produk, ruang lingkup, kriteria penerimaan |
| [DESIGN.md](./DESIGN.md) | Sistem desain, tema, dan layout |
| [docs/CMS-DESIGN.md](./docs/CMS-DESIGN.md) | Spesifikasi visual tema & dasbor CMS Admin (Revisi 4) |
| [docs/erd.md](./docs/erd.md) | Diagram relasi entitas basis data (Mermaid ERD) |
| [docs/CONTENT-CHECKLIST.md](./docs/CONTENT-CHECKLIST.md) | Daftar periksa konten & aset siap pakai untuk klien |
| [docs/CMS-GUIDE.md](./docs/CMS-GUIDE.md) | Panduan operasional CMS Filament untuk administrator |

---

## Tech Stack

| Lapisan | Teknologi |
|---|---|
| Backend | Laravel 12 (PHP 8.2) |
| Frontend | React + TypeScript lewat Inertia.js, dengan SSR |
| Database | MySQL 8 |
| CMS Admin | Filament |
| Styling | Tailwind CSS + CSS variables per tema |
| Animasi | Motion (`motion/react`) dan Anime.js |
| Multibahasa | `spatie/laravel-translatable` |
| Media | `spatie/laravel-medialibrary` |
| Hak akses | `spatie/laravel-permission` |
| Tes | Pest, Vitest, Playwright |
| Kualitas kode | Laravel Pint, ESLint, Prettier |

> **Catatan tipografi & animasi:** Tema holding menggunakan system Helvetica stack (`"Helvetica Neue", Helvetica, Arial, "Liberation Sans", sans-serif`) dengan bobot 400 dan 700 untuk performa maksimal dan 0 layout shift. Framer Motion dan Motion adalah pustaka yang sama. Cukup pasang paket `motion`. Motion untuk UI (reveal, hover, transisi halaman, carousel crossfade), Anime.js untuk counter dan animasi SVG.

---

## Prasyarat

- PHP ≥ 8.2 dengan ekstensi `mbstring`, `intl`, `gd` atau `imagick`, `pdo_mysql`, `zip`
- Composer 2
- Node.js ≥ 20 dan npm
- MySQL 

---

## Memulai

```bash
# 1. Kloning dan masuk
git clone <url-repositori> derco-bara-energy
cd derco-bara-energy

# 2. Pasang dependensi
composer install
npm install

# 3. Konfigurasi lingkungan
cp .env.example .env
php artisan key:generate
```

Atur koneksi database di `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=derco
DB_USERNAME=root
DB_PASSWORD=

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
```

```bash
# 4. Buat database, migrasi, dan isi data placeholder
php artisan migrate --seed
php artisan storage:link

# 5. Buat akun super admin
php artisan make:filament-user

# 6. Jalankan
composer run dev        # server Laravel + queue + Vite
```

Buka:
- Situs publik: <http://localhost:8000> (mengarah ke `/id`)
- CMS admin: <http://localhost:8000/admin>

### Pengelolaan Multi-Perusahaan (CMS Multi-Tenancy)
CMS mengimplementasikan Filament Multi-Tenancy dengan `Company` sebagai entitas tenant:
- **Tenant Switcher**: Terletak di bagian atas sidebar CMS. Menampilkan logo resmi, nama perusahaan, serta lencana status tayang (`Published`/`Draft`). Holding (`derco-bara-energy`) selalu berada di urutan teratas.
- **Satu Admin untuk Semua**: Seluruh pengguna ber-peran `Super Admin` dan `Editor` dapat berpindah dan mengelola keempat entitas perusahaan tanpa hambatan.
- **Resource Global vs Ter-scope Tenant**:
  - *Global (Super Admin saja)*: `Perusahaan`, `Pengguna CMS`, `Pengaturan Sistem`.
  - *Ter-scope Tenant*: `Halaman & Blok`, `Produk & Spesifikasi`, `Operasi & Lokasi`, `Statistik Perusahaan`, `Sertifikasi & Izin`, `Kontak Perusahaan`, `Pesan Masuk`.
- **Navigasi Adaptif**: Modul `Produk`, `Operasi`, dan `Sertifikasi` otomatis tersembunyi bila perusahaan terkait tidak memiliki kapabilitas halaman tersebut (mis. Holding tidak menampilkan menu Produk/Operasi/Sertifikasi).
- **Widget Ringkasan Grup**: Tabel konsolidasi seluruh anak usaha pada dasbor untuk memantau status tayang, pesan baru, dan draft halaman dalam satu kueri efisien tanpa N+1.

---

## Perintah Sehari-hari

| Perintah | Fungsi |
|---|---|
| `composer run dev` | Server pengembangan lengkap (Laravel + queue + Vite + dev SSR) |
| `npm run build` | Build produksi (klien) |
| `npm run build:ssr` | Build bundel klien + SSR (`bootstrap/ssr/ssr.js`) |
| `php artisan migrate:fresh --seed` | Reset database dan isi ulang placeholder |
| `./vendor/bin/pint` | Format PHP |
| `npm run lint` | ESLint |
| `npm run format` | Prettier |
| `npm run branding:light` | Hasilkan varian logo latar gelap (`*-light.png`) secara otomatis |
| `npm run types` | Pemeriksaan tipe TypeScript (`tsc --noEmit`) |
| `php artisan test` | Tes backend (Pest) |
| `npm run test` | Tes frontend (Vitest) |
| `npx playwright test` | Tes end-to-end |

> Seluruh skrip di atas telah terdefinisi di `package.json` dan `composer.json`. Saat menjalankan `composer run dev`, Vite otomatis merender SSR lewat endpoint internal `/__inertia_ssr` sehingga `view-source` pada `/id` dan `/en` langsung memuat HTML hasil SSR tanpa harus menjalankan proses `inertia:start-ssr` terpisah.

---

## Struktur Proyek

```
app/
├── Domain/                      logika per domain bisnis
│   ├── Company/                 Models, Actions, Resources, Enums
│   ├── Page/                    termasuk Blocks/ (satu kelas per tipe blok)
│   ├── Product/
│   ├── Operation/
│   ├── Compliance/
│   └── Contact/
├── Filament/                    CMS admin (Resources, Pages)
├── Http/
│   ├── Controllers/             tipis, memanggil Action lalu render Inertia
│   ├── Middleware/              SetLocale, ResolveCompany, HandleInertiaRequests
│   └── Requests/                validasi
└── Support/                     trait dan helper bersama

database/
├── migrations/
├── factories/
└── seeders/                     data placeholder dua bahasa

lang/{id,en}/                    teks antarmuka tetap (tombol, label, error)

resources/
├── css/
│   ├── app.css                  token global
│   └── themes/                  satu berkas token per tema
└── js/
    ├── core/                    hook, i18n, tipe, util (tanpa UI)
    ├── ui/                      komponen primitif (Button, Section, Image, ...)
    ├── motion/presets.ts        satu sumber preset animasi
    ├── themes/
    │   ├── registry.ts          kunci tema → Layout + blok
    │   ├── holding/
    │   ├── korin-berkat/
    │   ├── korin-bara/
    │   └── beramal/
    ├── pages/                   halaman tipis, memakai ThemedPage
    ├── ssr.tsx
    └── app.tsx

tests/
├── Feature/
├── Unit/
└── e2e/
```

---

## Cara Kerja Singkat

```
Database (companies, pages, page_blocks, ...)
   → Action (ambil dan susun data)
   → API Resource (bentuk data untuk frontend)
   → Inertia props
   → ThemedPage membaca company.theme
   → registry.ts memilih Layout dan komponen blok tema tersebut
```

- **Satu halaman, banyak tampilan.** Controller halaman anak usaha hanya satu. Perbedaan tampilan datang dari tema yang dipilih lewat `companies.theme`.
- **Konten berbasis blok.** Halaman adalah daftar `page_blocks`. Kolom `type` dipetakan ke komponen di tema aktif; kolom `data` (JSON) berisi isinya.
- **Navigasi otomatis.** Menu dibentuk dari tabel `pages` (`show_in_nav = true`) lewat hook `useNavigation()`.

### Rute dan Bahasa
```
/{locale}                                   holding, beranda
/{locale}/{about-slug}                      holding, tentang
/{locale}/{business-slug}                   daftar anak usaha
/{locale}/{business-slug}/{company}         beranda anak usaha
/{locale}/{business-slug}/{company}/{page}  halaman anak usaha
```
`locale` ∈ `id`, `en`. Slug halaman diterjemahkan dan diselesaikan terpusat oleh `PageResolver`. Jangan menulis pencocokan slug di tempat lain.

### Visibilitas Anak Usaha
`companies.status` bernilai `draft` atau `published`.
- `draft`: disembunyikan dari menu, grid, sitemap; URL mengembalikan 404 bagi publik.
- Admin yang login dapat membuka halaman `draft` dalam mode pratinjau (berlabel dan `noindex`).
- Untuk menayangkan: ubah status menjadi `published` di CMS.

PT Korin Bara Energy berstatus `published` (tayang penuh). Fitur status draft/published (FR-VIS) tetap aktif untuk seluruh entitas lainnya.

---

## Pengelolaan Logo & Branding

Sistem branding menggunakan pendekatan **satu sumber kebenaran** (`config/branding.php`) yang memetakan tema ke metadata logo:

```php
// config/branding.php
return [
    'logos' => [
        'holding' => [
            'light' => '/images/logo/Logo-DercoBaraEnergy.png',
            'dark' => '/images/logo/Logo-DercoBaraEnergy-light.png',
            'width' => 676,
            'height' => 369,
            'alt' => 'PT Derco Bara Energy',
        ],
        // ... anak usaha lainnya
    ],
];
```

### Konvensi Berkas & Varian Terang:
- `light`: Berkas logo asli untuk latar terang (tinta gelap di atas latar putih).
- `dark`: Varian terang untuk latar gelap (tinta putih di atas latar charcoal/navy/dark water).
- `compact`: Ikon/simbol ringkas untuk ruang sempit (rail Korin Bara 88px).

### Menghasilkan & Memperbarui Varian Logo:
1. Simpan berkas logo master di `public/images/logo/`.
2. Jalankan skrip pembuat varian terang:
   ```bash
   npm run branding:light
   ```
   Skrip ini idempoten dan tidak akan menimpa berkas yang sudah ada kecuali ditambahkan argumen `--force`:
   ```bash
   python scripts/branding/generate_light_variants.py --force
   ```
3. Jika klien di kemudian hari menyediakan berkas resmi versi negatif (SVG/PNG), cukup timpa berkas `*-light.png` tersebut secara langsung tanpa mengubah kode.
4. Lembar verifikasi matriks kontras dapat dibuat ulang kapan saja lewat:
   ```bash
   python scripts/branding/generate_preview_sheet.py
   ```
   Hasil pratinjau tersimpan di `docs/branding/logo-preview.png`.

---

## Menambah Anak Usaha Baru

1. Tambah nilai pada enum `CompanyTheme` (mis. `Contoh = 'contoh'`).
2. Definisikan set halaman yang aktif untuk tema tersebut di method `CompanyTheme::pages()`, memetakan tema ke daftar `PageKey` (mis. `[PageKey::Home, PageKey::About, PageKey::Products, PageKey::Operations, PageKey::Contact]`).
3. Daftarkan konfigurasi logo di `config/branding.php` yang memetakan tema ke path logo `{ light, dark, compact, width, height, alt }`.
4. Letakkan logo asli di `public/images/logo/Logo-Contoh.png` dan jalankan `npm run branding:light` untuk menghasilkan varian `-light.png`.
5. Buat folder `resources/js/themes/contoh/` berisi `Layout.tsx`, `blocks/`, dan `index.ts`.
6. Buat `resources/css/themes/contoh.css` dengan token warna dan font di dalam `[data-theme="contoh"] { ... }`.
7. Daftarkan di `resources/js/themes/registry.ts`.
8. Tambah baris perusahaan lewat CMS atau seeder. Halaman dibuat otomatis oleh `CreateCompanyPages` sesuai set halaman yang dikonfigurasi pada tema.

Tidak ada controller, rute, atau migrasi baru yang dibutuhkan.

## Menambah Tipe Blok Baru

1. Buat kelas blok di `app/Domain/Page/Blocks/` yang mendefinisikan **skema satu kali**: field, aturan validasi, dan form Filament.
2. Tambah nilai pada enum `BlockType`.
3. Buat komponen tampilan di setiap tema. Jika sebuah tema belum punya, tampilan fallback dipakai.
4. Tambah tipe TypeScript untuk `data` blok di `core/types`.

---

## Konvensi Kode

### Prinsip
- **Controller tipis.** Logika masuk ke Action (`app/Domain/*/Actions`).
- **Tidak ada pengulangan.** Jika kode yang sama ditulis dua kali, ekstrak menjadi Action, hook, atau komponen `ui/`.
- **Tema hanya presentasi.** Dilarang menaruh logika bisnis, fetch, atau aturan bahasa di folder `themes/`.
- **Satu sumber kebenaran.** Warna di berkas token tema, animasi di `motion/presets.ts`, nilai tetap di enum.
- **Tipe di mana-mana.** PHP bertipe pada parameter dan return; TypeScript `strict`.

### Penamaan
| Hal | Gaya | Contoh |
|---|---|---|
| Kelas PHP | PascalCase | `GetCompanyPageAction` |
| Method dan variabel PHP | camelCase | `publishedCompanies()` |
| Tabel dan kolom | snake_case, tabel jamak | `page_blocks`, `company_id` |
| Komponen React | PascalCase | `HeroBlock.tsx` |
| Hook | `use` + camelCase | `useNavigation.ts` |
| Berkas non-komponen TS | camelCase | `presets.ts` |
| Enum PHP | PascalCase, case PascalCase | `BlockType::Hero` |

### Git
- Cabang: `feature/<ringkas>`, `fix/<ringkas>`, `chore/<ringkas>`.
- Pesan commit mengikuti Conventional Commits: `feat(theme): tambah layout korin-berkat`.
- Pull request harus lolos CI (Pint, ESLint, `tsc`, Pest, Vitest) sebelum digabung.

---

## Multibahasa

- **Konten CMS** disimpan sebagai JSON per bahasa (`{"id": "...", "en": "..."}`) memakai `spatie/laravel-translatable`.
- **Teks antarmuka tetap** (tombol, label form, pesan error) ada di `lang/id/` dan `lang/en/`, diakses lewat `t()` di frontend.
- Jika terjemahan EN kosong, tampil versi ID.

---

## Pengujian

```bash
php artisan test              # backend
npm run test                  # frontend
npx playwright test           # end-to-end
```

Skenario E2E minimum: navigasi holding → anak usaha, ganti bahasa, kirim form kontak, perusahaan `draft` mengembalikan 404 untuk publik dan terlihat oleh admin.

---

## Deploy

### 1. Persiapan Lingkungan Produksi (.env)
Pastikan berkas `.env` di server produksi memiliki pengaturan berikut:
```dotenv
APP_NAME="PT Derco Bara Energy"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://derco-bara-energy.co.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=derco_production
DB_USERNAME=derco_user
DB_PASSWORD="<password-kuat>"

QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

# Akun Super Admin Otomatis (ProductionSeeder)
ADMIN_NAME="Super Administrator"
ADMIN_EMAIL="admin@derco-bara-energy.co.id"
ADMIN_PASSWORD="<password-rahasia-admin>"

# Konfigurasi SMTP Notifikasi Formulir Kontak
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD="<api-key>"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@derco-bara-energy.co.id"
MAIL_FROM_NAME="PT Derco Bara Energy"
```

### 2. Pemasangan Awal (Fresh Setup di Server)
Untuk instalasi pertama kali di server baru:
```bash
# Kloning repositori
git clone <url-repositori> /var/www/derco-app
cd /var/www/derco-app

# Pasang dependensi
composer install --no-dev --optimize-autoloader
npm ci

# Siapkan .env dan generate key
cp .env.example .env
php artisan key:generate

# Build aset SSR dan Client
npm run build:ssr

# Migrasi dan Seeder Produksi Minimal (TANPA data placeholder)
php artisan migrate --force
php artisan db:seed --class=ProductionSeeder --force

# Symlink storage publik & optimasi cache
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
php artisan filament:cache-components

# Set permission direktori storage dan bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 3. Skrip Deploy Otomatis
Telah disediakan skrip otomatis di `scripts/deploy.sh`:
```bash
chmod +x scripts/deploy.sh scripts/backup.sh
./scripts/deploy.sh
```

Untuk menjalankan deploy manual secara berurutan:
```bash
# Masuk mode pemeliharaan
php artisan down --secret="derco-deploy-bypass"

# Perbarui kode & pasang dependensi
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build:ssr

# Migrasi & symlink
php artisan migrate --force
php artisan storage:link

# Optimasi cache produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
php artisan filament:cache-components

# Muat ulang queue worker & layanan SSR
php artisan queue:restart
supervisorctl restart derco-ssr derco-worker

# Keluar dari mode pemeliharaan
php artisan up
```

### 4. Konfigurasi Layanan Background (SSR & Queue Worker)

#### Opsi A: Supervisor (Disarankan untuk Ubuntu/Debian)
Berkas konfigurasi siap pakai ada di `scripts/supervisor/derco.conf`. Salin ke `/etc/supervisor/conf.d/derco.conf`:
```ini
[program:derco-ssr]
process_name=%(program_name)s_%(process_num)02d
command=node /var/www/derco-app/bootstrap/ssr/ssr.js
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/supervisor/derco-ssr.log
environment=NODE_ENV="production",PORT="13714"
stopwaitsecs=10
stopsignal=SIGTERM

[program:derco-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/derco-app/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/derco-worker.log
stopwaitsecs=3600
stopsignal=SIGTERM
```
Aktifkan Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

#### Opsi B: Systemd Service
Berkas unit systemd tersedia di `scripts/systemd/`:
- `scripts/systemd/derco-ssr.service` → `/etc/systemd/system/derco-ssr.service`
- `scripts/systemd/derco-queue.service` → `/etc/systemd/system/derco-queue.service`
```bash
sudo systemctl daemon-reload
sudo systemctl enable --now derco-ssr.service derco-queue.service
```

### 5. Pencadangan Harian Otomatis (Database & Storage)
Telah disediakan skrip pencadangan otomatis di `scripts/backup.sh` yang melakukan dump MySQL terkompresi gzip, kompresi direktori `storage/app/public`, dan rotasi penghapusan otomatis arsip lebih dari 30 hari.

Pasang di crontab server (`crontab -e`):
```cron
# Setiap pukul 02:00 pagi WIB, lakukan pencadangan harian ke /var/backups/derco
0 2 * * * /var/www/derco-app/scripts/backup.sh /var/backups/derco >> /var/log/derco-backup.log 2>&1
```

---

## Pemecahan Masalah

| Gejala | Kemungkinan penyebab dan solusi |
|---|---|
| Halaman anak usaha 404 | Status perusahaan masih `draft`, atau slug bahasa tidak cocok. Periksa di CMS |
| Gaya satu tema bocor ke tema lain | Variabel diletakkan di `:root`. Pindahkan ke `[data-theme="..."]` |
| Gambar tidak muncul | Jalankan `php artisan storage:link`; periksa izin folder `storage` |
| Tampilan berbeda antara SSR dan klien | Ada kode yang memakai `window` saat render server. Bungkus dengan `useEffect` |
| Email form kontak tidak terkirim | Periksa konfigurasi SMTP dan pastikan queue worker berjalan |
| Perubahan konten tidak tampil | Bersihkan cache: `php artisan cache:clear` |

---

## Catatan Rilis & Perubahan (Changelog)

### v1.6.0 (Revisi 5 — Multi-Tenancy Perusahaan, Pengelolaan Satu Admin, & CRUD Aktif)
- **Filament Multi-Tenancy (Tenant = `Company`):**
  - Satu panel CMS mengelola holding (`derco-bara-energy`) dan seluruh tiga anak usaha (`korin-berkat-energy`, `beramal-sukses-sejahtera`, `korin-bara-energy`).
  - **Satu Admin Mengelola Semua**: Tidak ada tabel `company_user`. Seluruh pengguna CMS (`Super Admin` maupun `Editor`) dapat mengakses dan beralih ke seluruh perusahaan tanpa batas via Tenant Switcher di sidebar.
  - Tenant Switcher di sidebar menampilkan logo resmi (`ResolveCompanyLogo`), nama entitas, holding di posisi pertama, dan lencana status tayang (`Published`/`Draft`).
  - Tenant bawaan saat login: Holding (`derco-bara-energy`).
- **Isolasi Konten Otomatis & Keamanan:**
  - Resource berpemilik `company_id` (`Page`, Blok, `Product`, `Operation`, `CompanyStat`, `Certification`, `CompanyContact`, `ContactMessage`) otomatis ter-scope ke tenant aktif.
  - Akses ID/URL lintas tenant mengembalikan respons `404 Not Found`.
- **Navigasi Adaptif per Kemampuan Perusahaan:**
  - Modul `Produk`, `Operasi`, dan `Sertifikasi` hanya tampil di navigasi bila perusahaan memiliki halaman terkait (`Company::hasPage(...)`). Holding tidak melihat menu Produk, dan Korin Berkat tidak melihat Sertifikasi.
- **Profil Tenant & Pengaturan:**
  - Halaman profil tenant (`/admin/{tenant}/profile`) untuk mengedit nama, tagline, deskripsi, email notifikasi formulir, dan metadata SEO bawaan.
  - Kolom status tayang (`published`/`draft`) dan tema visual hanya dapat diubah oleh Super Admin.
  - Tabel `settings` diperluas dengan `company_id` nullable dan constraint unik `['company_id', 'key']` dengan fallback otomatis ke pengaturan global via `CompanySettingsReader`.
- **Dasbor & Widget Ringkasan Grup:**
  - Seluruh widget dasbor ter-scope ke data tenant aktif (`StatsOverviewWidget`, `HeroBannerWidget`, `QuickActionsWidget`, `RecentActivityWidget`).
  - Penambahan widget **Ringkasan Grup** (`GroupSummaryWidget`) pada dasbor setiap tenant: menampilkan status tayang, jumlah pesan masuk baru, jumlah halaman draft, dan tombol tautan cepat perpindahan tenant yang dieksekusi dalam 1 query tunggal (`GetGroupSummaryAction`) tanpa N+1.
- **Pengaman CRUD & Hak Akses:**
  - Pengaman penghapusan: dilarang menghapus holding, dilarang menghapus perusahaan yang masih memiliki anak usaha/pesan masuk, dilarang menghapus akun sendiri atau Super Admin terakhir.
  - Editor dilarang menghapus halaman dan dilarang mengakses modul sistem (`Company`, `User`, `Setting`).
- **Pengujian Lengkap:**
  - Penambahan 9 skenario pengujian Pest di `tests/Feature/CmsMultiTenancyCrudTest.php` (total 79 tes, 987 assertions lulus 100%).

### v1.5.0 (Revisi 4 — Penayangan Korin Bara, Alias Logo, dan Redesain CMS Admin)
- **Penayangan PT Korin Bara Energy:**
  - Status Korin Bara beralih ke `published` di database seeder dan lewat migrasi data idempoten `2026_11_12_000000_publish_korin_bara_energy.php`.
  - Seluruh 6 halaman Korin Bara tayang aktif (200 OK) tanpa penanda preview dan terindeks di `sitemap.xml`.
  - Grid Bisnis Kami di beranda holding otomatis menjadi 3 kolom kartu.
  - Mekanisme draft/published (FR-VIS) dipertahankan utuh dan divalidasi dengan perusahaan uji berstatus draft.
- **Logo Korin Bara = Logo Korin Berkat:**
  - `config/branding.php` memetakan entri `korin-bara` merujuk langsung ke definisi logo `korin-berkat`.
  - Varian ringkas (compact symbol) diekstrak ke `public/images/logo/Logo-KorinBerkatEnergy-compact.png` untuk rail vertikal 88px Korin Bara.
  - Berkas compact lama `Logo-KorinBaraEnergy-compact.png` tidak dihapus (tetap aman sebagai berkas arsip yang tidak digunakan).
  - Alt text tetap unik semantik ("PT Korin Bara Energy").
- **Redesain CMS Admin (Filament v5):**
  - Mengikuti foto referensi konsol korporat modern (spesifikasi di `docs/CMS-DESIGN.md`, hasil perbandingan di `docs/branding/cms-compare/`).
  - Tema custom di `resources/css/filament/admin/theme.css` dengan token warna terpusat (Primary Amber `#EDB856`, Sidebar Dark Navy `#111C2E`, Canvas `#F8FAFC`).
  - Sidebar custom dengan siluet pegunungan (`sidebar-mountains.png`), pintasan "View Website", dan logout.
  - Halaman login kustom (`resources/views/filament/layouts/auth.blade.php`): split 50:50 dengan foto pertambangan, judul "Building a Sustainable Future Together", kartu input modern, dan alternatif SSO.
  - 6 Widget dasbor kustom dengan data riil database: `DashboardGreetingWidget`, `StatsOverviewWidget`, `HeroBannerWidget`, `QuickActionsWidget`, `WebsiteOverviewWidget`, `RecentActivityWidget`.
  - Aksesibilitas WCAG 2.2 AA terverifikasi via Playwright & axe-core.

### v1.4.0 (Revisi 3 — Logo di Header dan Footer Seluruh Tema)
- **Komponen Bersama `Logo`:** Komponen mandiri `resources/js/ui/Logo.tsx` yang menangani render logo beresolusi tinggi, prop `on="light"|"dark"`, `size="sm"|"md"|"lg"|number`, `compact`, `plat`, `priority`, dan wordmark fallback semantik. Dilarang menggunakan filter CSS (brightness/invert).
- **Arsitektur Konfigurasi Terpusat:** Penambahan `config/branding.php` dan `ResolveCompanyLogo` action. Objek data `logo: { light, dark, compact, width, height, alt }` disuntikkan ke frontend lewat `CompanyResource`. Tipe TypeScript didefinisikan di `CompanyLogoData`.
- **Generator Varian Terang:** Skrip Python `scripts/branding/generate_light_variants.py` (`npm run branding:light`) yang mengonversi tinta gelap menjadi putih bersih dengan menjaga transparansi alpha dan rona simbol asli secara idempoten. Matriks kontras divisualisasikan pada `docs/branding/logo-preview.png`.
- **Integrasi Header & Footer di Empat Tema:**
  - `holding`: Header menggunakan varian dark-surface (tinggi 48px menyusut ke 36px saat sticky), mobile 32px; footer menggunakan varian dark-surface (tinggi 44px).
  - `korin-berkat`: Header putih menggunakan varian asli/light-surface (tinggi 40px); footer navy menggunakan varian dark-surface (tinggi 44px).
  - `korin-bara`: Desktop rail putih menggunakan varian compact icon (tinggi 32px / 40px expand); mobile header menggunakan compact icon (tinggi 32px); footer navy menggunakan varian dark-surface (tinggi 44px).
  - `beramal`: Header kapsul putih menggunakan varian asli/light-surface (tinggi 36px); footer dark water menggunakan varian dark-surface (tinggi 44px).
- **Aksesibilitas & Pengujian:** Label tautan beranda lengkap (`aria-label="{Nama Perusahaan}, Beranda"` / `"{Company Name}, Home"`), footer murni gambar tanpa tautan. Pengujian lengkap: Pest feature tests (`BrandingLogoTest.php`), Vitest unit tests (`logo.test.tsx`), dan Playwright E2E & Axe accessibility tests (`branding-logo.spec.ts`).

### v1.3.0 (Revisi 2 — Anak Usaha: PT Korin Berkat Energy & PT Beramal Sukses Sejahtera)
- **Arsitektur & Konfigurasi Halaman Dinamis:** Penambahan method `CompanyTheme::pages()` pada enum `CompanyTheme` untuk mendefinisikan set halaman per tema. `CreateCompanyPages` dan seeder kini sepenuhnya fleksibel tanpa conditional bercabang di kode inti.
- **PT Korin Berkat Energy (Tema Editorial Index):**
  - **Tipografi Manrope:** Migrasi seluruh font heading dan body ke Manrope Variable (`@fontsource-variable/manrope`, bobot 400/500/700) dengan `font-variant-numeric: tabular-nums` untuk angka statistik dan tabel spesifikasi. Paket Source Serif 4 dan Inter dicopot bersih.
  - **Navigasi 5 Item:** Menu bernomor otomatis (01-05). Halaman Mutu & Kepatuhan dihapus permanen dari basis data dan rute (URL publik 404).
  - **Beranda Bersih:** Judul section besar setelah hero disembunyikan visual (`sr-only`) untuk menjaga ritme desain. Tautan panah dan tombol download pada ringkasan produk dihilangkan. Blok sertifikat/kepatuhan dihapus dari beranda.
  - **Tentang Kami:** Blok Timeline (Tahapan Operasional) dihapus; Standar Kerja diganti menjadi Nilai Perusahaan (`values_grid`) dengan 4 item editorial bernomor (Integritas & Kepatuhan, Keandalan Pasokan, Keselamatan Kerja, Efisiensi Operasional).
  - **Produk:** Tabel spesifikasi teknis lengkap disajikan langsung dalam format editorial 2 kolom (nama & aplikasi di kiri, tabel parameter CV, TM, IM, Ash, VM, FC, TS di kanan) tanpa tautan panah ke halaman detail terpisah.
- **PT Beramal Sukses Sejahtera (Tema Calm Flow):**
  - **Header & Kontak:** Tombol solid CTA pill di header digantikan teks link kanan "Hubungi Kami" / "Contact Us" sejajar pengalih bahasa (touch target 44×44px, aksen underline cerulean saat aktif). Menu tengah hanya memuat halaman non-kontak. Halaman kontak dinamai "Hubungi Kami" / "Contact Us" dengan rute `/hubungi-kami` & `/contact-us` (301 redirect dari `/kontak` & `/contact`).
  - **Halaman Mutu & Kepatuhan Nonaktif:** Status diubah menjadi `is_published=false` dan `show_in_nav=false` (URL publik 404, tidak muncul di menu/sitemap, admin dapat mempratinjau via CMS).
  - **Beranda & Tentang Kami:** Judul section setelah hero disembunyikan visual (`sr-only`), blok Jejak Infrastruktur (`operation_list`) dan Kepatuhan & Mutu (`certification_list`) dihapus dari beranda. Blok Timeline (Tahapan Operasional) dihapus dari Tentang Kami.
- **Migrasi & Aksi Idempoten:** Penambahan `SyncSubsidiaryPagesAction` dan migrasi database `2026_11_10_100000_apply_revision_2_subsidiary_pages.php` yang dapat dijalankan secara idempoten pada database produksi yang sudah terisi.
- **Pengujian Lengkap:** Penambahan pengujian unit Vitest (`revision2.test.tsx`), Pest feature tests (`Revision2Test.php`, pembaruan `DataModelAndCmsTest.php`, `KorinBerkatTest.php`, `BeramalTest.php`), dan E2E Playwright tests (`korin-berkat.spec.ts`, `beramal.spec.ts`) dengan validasi aksesibilitas axe WCAG 2.2 AA di seluruh halaman.

### v1.2.0 (Revisi 1 — Tema Holding / Web Utama PT Derco Bara Energy)
- **Tipografi:** Migrasi dari DM Sans ke Helvetica system font stack (`"Helvetica Neue", Helvetica, Arial, "Liberation Sans", sans-serif`) bobot 400 & 700. Paket `@fontsource-variable/dm-sans` dicopot bersih.
- **Sistem Radius:** `--radius-button: 8px` untuk tombol agak rounded; `--radius-card: 12px` untuk wadah kartu.
- **Navigasi Header:** Menggabungkan menu Kontak dan tombol Hubungi Kami menjadi satu tautan teks kanan "Hubungi Kami" / "Contact Us" sejajar pengalih bahasa ID | EN (uppercase, tanpa border/latar, touch target 44×44px, hover/active bottom border copper). Menu tengah menjadi 3 item (`Beranda · Tentang Kami · Bisnis Kami`).
- **Routing & SEO:** Mengubah slug kontak holding menjadi `/id/hubungi-kami` dan `/en/contact-us` dengan 301 Permanent Redirect dari `/id/kontak` dan `/en/contact`. Slug anak usaha tetap `/kontak` dan `/contact`.
- **Komponen HeroCarousel:** Komponen carousel gambar hero mandiri (`ui/HeroCarousel`) dengan auto-crossfade 800ms, jeda autoplay saat hover/fokus, pagination accessible (`role="tablist"` / `role="tab"`), dan dukungan `prefers-reduced-motion`.
- **Restrukturisasi Halaman Holding:**
  - Beranda: Kartu anak usaha adaptif berasio 4:3, tombol "Lihat Selengkapnya", heading section besar disembunyikan secara visual (`sr-only`) untuk menjaga ritme visual.
  - Tentang Kami: Hero carousel 3 gambar, deskripsi profil Derco + Area Operasional `[ISI WILAYAH OPERASIONAL]`, penghapusan blok Timeline/sejarah.
  - Bisnis Kami: Hero carousel 3 gambar, penyederhanaan blok anak usaha alternatif tanpa perhitungan / key stats angka.
- **Pengujian:** Penambahan unit test Vitest untuk `HeroCarousel`, Pest feature test untuk 301 redirect dan skema hero slides, serta Playwright E2E test untuk verifikasi navigasi header dan carousel.

---

## Lisensi

Hak cipta © PT Derco Bara Energy. Seluruh hak dilindungi. Penggunaan dan distribusi mengikuti kesepakatan kerja sama proyek.
