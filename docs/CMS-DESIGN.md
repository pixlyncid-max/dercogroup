# Spesifikasi Sistem Desain CMS Admin (Filament)
## PT Derco Bara Energy & Anak Usaha

Dokumen acuan: [PRD.md](../PRD.md) · [DESIGN.md](../DESIGN.md) · Gambar referensi: `docs/reference/cms/dashboard.png` dan `docs/reference/cms/login.png`.

---

## 1. Ringkasan Eksekutif & Analisis Referensi

Redesain tampilan CMS Admin (`/admin`) bertujuan mereplikasi presisi estetika dari foto referensi visual:
1. **Dashboard** (`docs/reference/cms/dashboard.png`): Antarmuka konsol korporat modern dengan sidebar gelap bermotif lanskap tambang halus di dasar, header terang berfitur pencarian terpusat (`Ctrl + K`), salam personal dinamis dengan tanggal & jam live, 4 kartu metrik utama bersparkline, kartu sorotan operasi/hero banner, kisi aksi cepat 6 tombol, grafik ikhtisar situs garis lengkung, serta linimasa aktivitas terkini.
2. **Halaman Login** (`docs/reference/cms/login.png`): Tata letak *split-screen* 50:50. Sisi kiri menampilkan lanskap operasional tambang terbuka, logo perusahaan, tajuk "Building a Sustainable Future Together", dan pilar bisnis. Sisi kanan menampilkan kartu masuk putih elegan bersudut melengkung halus dengan logo resmi, form input berkategori ikon, tombol masuk navy pekat, opsi SSO, dan hak cipta.

---

## 2. Dimensi Layar & Struktur Tata Letak (Layout)

| Parameter | Dimensi Referensi | Penerapan Filament |
|---|---|---|
| **Resolusi Kanvas Master** | `1672 × 941 px` | Viewport desktop standar `1440px - 1920px` |
| **Lebar Sidebar** | `252 px` (~`16rem`) | `$panel->sidebarWidth('16rem')` |
| **Sidebar Desktop** | Menempel kiri (fixed rail), dapat diciutkan | `$panel->sidebarCollapsibleOnDesktop()` |
| **Sidebar Mobile / Tablet** | Tersembunyi otomatis, terbuka sebagai slide-over drawer | Bawaan responsif Filament (`md` & `sm`) |
| **Header / Topbar** | Latar putih (`#FFFFFF`), tinggi `64px`, border-b halus | Topbar bawaan dengan custom style |
| **Area Konten Utama** | Latar abu-abu kebiruan lembut (`#EFF1F7`), padding `24px` | Konten berpusat dengan `$panel->maxContentWidth(MaxWidth::Full)` |

---

## 3. Palet Warna (Color Tokens)

Pengukuran nilai warna langsung dari berkas piksel referensi:

| Token | Nilai Hex | Peran pada Tampilan |
|---|---|---|
| `--cms-sidebar-bg` | `#152238` | Latar belakang sidebar navigasi utama |
| `--cms-sidebar-surface` | `#1C2D4A` | Wadah item navigasi sekunder / hover |
| `--cms-sidebar-active` | `#334155` / `rgba(235, 186, 88, 0.16)` | Latar item navigasi yang sedang aktif |
| `--cms-sidebar-text` | `#94A3B8` | Teks dan ikon navigasi tidak aktif |
| `--cms-sidebar-text-active`| `#FFFFFF` / `#EDB856` | Teks dan ikon item aktif (emas / putih) |
| `--cms-content-bg` | `#EFF1F7` | Latar utama seluruh halaman admin di luar sidebar |
| `--cms-card-bg` | `#FFFFFF` | Latar kartu widget, tabel, dan form |
| `--cms-card-border` | `#E2E8F0` | Garis tepi halus kartu dan tabel (1px) |
| `--cms-ink-primary` | `#0F172A` | Teks judul, angka statistik utama, dan teks tebal |
| `--cms-ink-secondary` | `#64748B` | Teks keterangan, label kecil, dan tanggal/jam |
| `--cms-accent-gold` | `#EDB856` | Aksen tombol utama, penanda hero, dan highlight aktif |
| `--cms-accent-gold-hover` | `#D9A038` | State hover elemen beraksen emas |
| `--cms-navy-button` | `#122A44` | Tombol primer pada halaman login dan aksi gelap |
| `--cms-navy-button-hover` | `#0C1D30` | State hover tombol navy |
| `--cms-success` | `#10B981` | Indikator tren positif (`↑ +12.4%`, `↑ +5%`) |
| `--cms-danger` | `#EF4444` | Indikator tren negatif / peringatan kedaluwarsa |

---

## 4. Tipografi

- **Keluarga Huruf Utama:** `Manrope` / `Inter`, dengan fallback `system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`.
- **Hierarki Skala:**
  - **Greeting / Hero Title:** `26px` (Desktop) / `20px` (Mobile), Weight `700`, Line-height `1.2`.
  - **Stat Counter Big Number:** `28px` - `32px`, Weight `800`, Tabular numbers.
  - **Widget Headings (Quick Actions, Website Overview):** `18px`, Weight `700`, Line-height `1.3`.
  - **Card Body / Table Text:** `14px`, Weight `400` / `500`, Line-height `1.5`.
  - **Eyebrow / Subtitles / Badges:** `11px` - `12px`, Weight `600`, Uppercase / Medium.

---

## 5. Bentuk, Radius, & Elevasi (Elevation & Shapes)

- **Radius Kartu Widget & Konten:** `16px` (`rounded-2xl`).
- **Radius Tombol:** `10px` (`rounded-xl`) untuk Quick Action & Login; `9999px` (`rounded-full`) untuk badge, pill waktu, dan tombol "View Website →".
- **Radius Input Form:** `10px` (`rounded-lg`).
- **Bayangan (Box Shadow):**
  - Kartu: `0 2px 8px -2px rgba(15, 23, 42, 0.05), 0 1px 4px -1px rgba(15, 23, 42, 0.03)`.
  - Login Card: `0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04)`.
- **Ketebalan Garis:** `1px` solid, warna `#E2E8F0` atau `#F1F5F9`.

---

## 6. Pemetaan Komponen Gambar ke Filament

| Elemen Referensi | Komponen Filament & Arsitektur |
|---|---|
| **Sidebar Gelap** | Tema CSS Filament dikompilasi via Tailwind/Vite, mengatur warna sidebar `--sidebar-bg` dan border. Motif lanskap ditambahkan melalui `PanelsRenderHook::SIDEBAR_NAV_END` / CSS background. |
| **Logo Perusahaan di Sidebar** | `Panel::brandLogo(view('filament.brand'))` membaca logo resmi dari `config/branding.php` varian dark-surface. |
| **Navigasi Grup** | Disusun menjadi 5 grup terstruktur: `Content Management` (Halaman, Media), `Business Modules` (Perusahaan, Produk, Operasi, Statistik, Kepatuhan), `Communications` (Pesan Masuk, Kontak), dan `System` (Pengguna, Pengaturan). |
| **Header / Search / Profil** | Topbar Filament: Global Search dengan shortcut `Ctrl + K`, Avatar profil pengguna, dan menu logout. |
| **Salam & Jam (Header Dashboard)** | Widget custom `DashboardGreetingWidget` dengan greeting dinamis sesuai waktu lokal, nama admin, tanggal, dan jam real-time. |
| **4 Kartu Metrik (Row 1)** | Widget custom `StatsOverview` / `CustomMetricCardsWidget`: Total Pages, Total News/Content, Total Media, Total Users (data asli dari database) + ikon kotak berwarna dan grafik tren mini. |
| **Hero Banner (Row 2 Kiri)** | Widget custom `HeroBannerWidget`: gambar operasional tambang nyata, teks "Mining · Trading · Nickel", dan tombol tautan beranda holding. |
| **Quick Actions (Row 2 Kanan)** | Widget custom `QuickActionsWidget`: kisi 6 tombol jalan pintas cepat (Kelola Halaman, Tambah Produk, Pesan Masuk, Media, Pengaturan, Kunjungi Situs). |
| **Website Overview Chart (Row 3 Kiri)** | Widget `WebsiteOverviewChartWidget` turunan `ChartWidget` tipe `line` kurva halus berspline, gradient fill biru, dilengkapi statistik ringkas pengunjung dan durasi sesi. |
| **Recent Activity (Row 3 Kanan)** | Widget custom `RecentActivityWidget`: linimasa vertikal data aktivitas nyata (pesan masuk terbaru, halaman yang baru diubah, dan log pengguna). |
| **Halaman Login Split-Screen** | Kelas halaman `App\Filament\Pages\Auth\Login` yang meng-override layout view login menjadi 50% banner operasional tambang dan 50% form login terpusat. |

---

## 7. Keputusan Mode Tampilan & Aksesibilitas

1. **Mode Terang Eksklusif:**
   Referensi menampilkan panel terang bersih dengan sidebar gelap permanen. Menghindari inkonsistensi kontras pada mode gelap generik, panel admin dikunci pada mode terang resmi dengan `$panel->darkMode(false)`.
2. **Kontras WCAG 2.2 AA:**
   Teks `#0F172A` di atas `#FFFFFF` memiliki rasio kontras 16.5:1 (melebihi standar 4.5:1). Teks sekunder `#64748B` di atas putih memiliki rasio 4.6:1 (lulus WCAG AA). Teks emas `#EDB856` hanya dipakai sebagai aksen tombol dengan teks gelap `#152238` (kontras 8.2:1) atau sebagai latar dengan kontras teruji.
3. **Navigasi Keyboard:**
   Semua input dan tombol aksi cepat memiliki ring fokus keyboard yang terlihat jelas (`:focus-visible: ring-2 ring-accent`).
