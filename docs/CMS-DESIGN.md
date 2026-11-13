# Spesifikasi Sistem Desain CMS Admin v2 (Filament)
## PT Derco Bara Energy & Tiga Anak Usaha

Dokumen acuan: [PRD.md](../PRD.md) · [DESIGN.md](../DESIGN.md) · Gambar referensi: `docs/reference/cms/editor.jpg`, `docs/reference/cms/dashboard.png`, dan `docs/reference/cms/login.png`.

---

## 1. Ringkasan Eksekutif & Analisis Referensi

Redesain tampilan CMS Admin v2 (`/admin`) bertujuan menyatukan estetika visual korporat profesional dari foto referensi dengan kebutuhan nyata pengguna awam:
1. **Editor Halaman Website** (`docs/reference/cms/editor.jpg`): Antarmuka editor bersih dengan sidebar navy (`#0F172A`), penanda aktif emas/aksen tema, bar atas penunjuk perusahaan aktif, header aksi *sticky* dengan tombol "Simpan Perubahan" dan "Preview Website", saklar dwibahasa (`ID` | `EN`) dengan indikator kolom kosong, serta kartu-kartu form vertikal per section.
2. **Dashboard & Kerangka** (`docs/reference/cms/dashboard.png`): Tata letak navigasi samping navy terpusat dengan tenant switcher di pojok atas, lencana status tayang (`Published` / `Draft`), lencana pesan baru, serta indikator profil pengguna di bagian bawah.
3. **Halaman Login** (`docs/reference/cms/login.png`): Tata letak *split-screen* 50:50 dengan latar visual operasional pertambangan di sisi kiri dan kartu login putih elegan di sisi kanan.

---

## 2. Dimensi Layar & Struktur Tata Letak (Layout)

| Parameter | Dimensi Referensi | Penerapan Filament CMS v2 |
|---|---|---|
| **Resolusi Viewport Master** | `1440 × 900 px` | Viewport desktop standar `1280px - 1920px`, mobile responsif `375px` |
| **Lebar Sidebar** | `280 px` (`17.5rem`) | `$panel->sidebarWidth('17.5rem')` |
| **Sidebar Desktop** | Menempel kiri (fixed rail navy) | `$panel->sidebarCollapsibleOnDesktop(false)` |
| **Sidebar Mobile / Tablet** | Tersembunyi otomatis, drawer slide-over | Bawaan responsif Filament (`md` & `sm`) |
| **Header Aksi Editor** | Sticky melayang dengan backdrop-blur | `sticky top-16 z-20 bg-white/95 backdrop-blur-md` |
| **Area Kerja Konten** | Form cards vertikal dua kolom | `$panel->maxContentWidth(Width::Full)` dengan grid 12-kolom |

---

## 3. Palet Warna (Color Tokens) per Entitas Perusahaan

Setiap entitas mengadopsi sidebar navy yang konsisten, dengan warna aksen tombol utama dan lencana aktif yang beradaptasi mengikuti identitas visual masing-masing:

| Token / Entitas | Nilai Hex | Peran pada Tampilan CMS |
|---|---|---|
| **Sidebar Base Navy** | `#0F172A` | Latar utama sidebar di seluruh perusahaan |
| **PT Derco Bara Energy** | `#C87A38` | Aksen tembaga (*copper*) untuk holding korporat |
| **PT Korin Berkat Energy** | `#EDB856` | Aksen emas (*gold*) untuk perdagangan dan ekspor batubara |
| **PT Korin Bara Energy** | `#F59E0B` | Aksen amber pertambangan untuk operasi pit-to-port |
| **PT Beramal Sukses Sejahtera** | `#0284C7` | Aksen cerulean bahari untuk logistik dan transshipment |
| **Border & Divider** | `rgba(255, 255, 255, 0.08)` | Pemisah kartu form dan baris navigasi |
| **Kartu Form Latar** | `#FFFFFF` (dark: `#0F172A`) | Wadah section form dengan rounded-2xl |

---

## 4. Evaluasi Komparatif: Referensi Visual vs Hasil Nyata

Perbandingan detail antara gambar referensi (`docs/reference/cms/`) dan implementasi CMS v2 nyata:

| Elemen Antarmuka | Gambar Referensi | Implementasi CMS v2 Nyata | Catatan & Alasan Penyesuaian |
|---|---|---|---|
| **Tenant / Perusahaan** | Terikat per akun / tunggal | Multi-Tenancy dinamis (1 panel, 1 admin untuk semua) | **Peningkatan Utama**. Memungkinkan admin holding mengelola holding dan ketiga anak usaha tanpa gonta-ganti akun. |
| **Menu Navigasi Sidebar** | Tabel entitas data generik | Halaman website aktual (`Beranda`, `Tentang Kami`, `Produk/Bisnis`, dll.) | **Keputusan Final**. Menghilangkan kebingungan pengguna awam; konten diedit langsung menurut halaman tujuan. |
| **Header Website** | Menu terpisah | Kartu pertama pada halaman Beranda | **Keputusan Final**. Header bersifat global perusahaan sehingga diedit di Beranda dengan penanda "Perubahan ini tampil di semua halaman". |
| **Pengalih Bahasa** | Tab di dalam form field | Saklar pil di header aksi (`ID` \| `EN`) | **Peningkatan UX**. Memudahkan penerjemahan menyeluruh sekaligus memberi tahu jumlah kolom yang belum diisi. |
| **Pratinjau (Preview)** | Gambar statis mockup | Drawer iframe responsif live (`?preview=1`) | **Peningkatan Teknis**. Menampilkan website publik sungguhan secara live dengan saklar viewport desktop, tablet, dan mobile. |
| **Simpan & Tayang** | Tombol simpan biasa | Simpan instan + Riwayat Snapshot 10 versi | **Pengaman Data**. Setiap penyimpanan membuat snapshot pemulihan dan dilindungi penguncian optimistis. |

---

## 5. Kepatuhan Aksesibilitas (WCAG 2.1 AA)

- **Kontras Teks**: Seluruh label dan nilai teks memiliki rasio kontras $\ge 4.5:1$ terhadap latar belakangnya.
- **Dukungan Keyboard**: Tombol simpan dapat dipicu melalui pintasan `Ctrl + S` atau `Cmd + S`. Navigasi form dapat dilalui dengan `Tab` secara berurutan.
- **Ukuran Target Sentuh**: Elemen interaktif pada perangkat layar sentuh memenuhi dimensi minimum $44 \times 44\text{ px}$.
