# Derco Bara Energy — Website

Website company profile **PT Derco Bara Energy** (holding) beserta tiga situs anak usaha: PT Korin Bara Energy, PT Korin Berkat Energy, dan PT Beramal Sukses Sejahtera. Dwibahasa (Indonesia dan Inggris), dengan CMS admin.

| Dokumen | Isi |
|---|---|
| [PRD.md](./PRD.md) | Kebutuhan produk, ruang lingkup, kriteria penerimaan |
| [DESIGN.md](./DESIGN.md) | Sistem desain, tema, dan layout |
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

> **Catatan animasi:** Framer Motion dan Motion adalah pustaka yang sama. Cukup pasang paket `motion`. Motion untuk UI (reveal, hover, transisi halaman), Anime.js untuk counter dan animasi SVG.

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

PT Korin Bara Energy saat ini berstatus `draft`.

---

## Menambah Anak Usaha Baru

1. Tambah nilai pada enum `CompanyTheme` (mis. `Contoh = 'contoh'`).
2. Buat folder `resources/js/themes/contoh/` berisi `Layout.tsx`, `blocks/`, dan `index.ts`.
3. Buat `resources/css/themes/contoh.css` dengan token warna dan font di dalam `[data-theme="contoh"] { ... }`.
4. Daftarkan di `resources/js/themes/registry.ts`.
5. Tambah baris perusahaan lewat CMS atau seeder. Enam halaman standar dibuat otomatis oleh `CreateCompanyPages`.

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

## Lisensi

Hak cipta © PT Derco Bara Energy. Seluruh hak dilindungi. Penggunaan dan distribusi mengikuti kesepakatan kerja sama proyek.
