# Inventarisasi Konten & Arsitektur CMS (CMS Inventory) — Revisi 6

Dokumen ini merupakan hasil inventarisasi menyeluruh tanpa perubahan kode pada **Tahap 1 (Revisi 6 — CMS v2)**. Dokumen ini menjadi rujukan tunggal (*single source of truth*) dalam menyusun Skema Konten (Tahap 2) dan migrasi frontend (Tahap 3).

---

## A. Inventarisasi Konten per Perusahaan & Halaman Publik

### 1. PT Derco Bara Energy (Holding / `derco-bara-energy`)
* **Tema Visual:** `holding` (*Corporate Industrial* — Dark Navy `#121E31`, Amber Gold `#EDB856`, Warm Canvas `#F8FAFC`).
* **Header Perusahaan:**
  * Logo: `Logo-DercoBaraEnergy.png` (versi terang) / `Logo-DercoBaraEnergy-light.png` (versi gelap) / `Logo-DercoBaraEnergy-compact.png` (lambang).
  * Menu Navigasi: Beranda, Tentang Kami, Bisnis Kami, Hubungi Kami.
  * Teks Header: `PT Derco Bara Energy`, `Corporate Group`, Tombol Bahasa ID/EN.

#### Halaman 1.1: Beranda (`/id`, `/en`)
1. **Section 1 (Hero — `hero`):**
   * Teks: Eyebrow (`PT DERCO BARA ENERGY`), Title (`Membangun Masa Depan Energi Berkelanjutan`), Description (`Grup energi dan pertambangan terintegrasi...`), Primary CTA Label (`Eksplorasi Bisnis Kami`), Primary CTA Link (`/id/bisnis-kami`).
   * Media: `hero-excavator-1280.webp`, Alt: `Operasi Penambangan dan Alat Berat PT Derco Bara Energy`.
2. **Section 2 (Unit Bisnis — `subsidiary_grid`):**
   * Teks: Eyebrow (`UNIT BISNIS`), Title (`Entitas Operasional Terintegrasi`), Description (`Menghubungkan rantai nilai komoditas energi dari hulu penambangan hingga logistik ekspor.`), CTA Label (`Lihat Profil Entitas`).
   * Media: Lambang anak usaha (`Logo-KorinBerkatEnergy.png`, `Logo-BeramalSuksesSejahtera.png`, `Logo-KorinBaraEnergy-compact.png`).
3. **Section 3 (Fokus Strategis — `stacked_images`):**
   * Teks: Eyebrow (`FOKUS STRATEGIS`), Title (`Standar Penambangan Bertaraf Internasional`), Lead (`Komitmen teguh terhadap keselamatan, efisiensi operasional, dan kepatuhan lingkungan.`), Body (`Derco Group mengoperasikan konsesi penambangan dengan tata kelola teknis yang teruji...`), CTA Label (`Pelajari Tata Kelola Kami`), CTA Link (`/id/tentang-kami`).
   * Media: `aerial-pit-1280.webp` (Primary), `haul-trucks-1280.webp` (Secondary).
4. **Section 4 (Pilar Keunggulan — `color_band`):**
   * Teks: Title (`Keandalan Rantai Pasok Terintegrasi`), Description (`Dari pit tambang, crushing plant, stockpile pelabuhan, hingga pemuatan tongkang transshipment.`), Icon: `shield-check`, CTA Label (`Hubungi Divisi Pemasaran`), CTA Link (`/id/kontak`).
   * Media: `coal-stockpile-1280.webp`.
5. **Section 5 (Visi & Misi — `mission_list`):**
   * Teks: Eyebrow (`TATA NILAI PERUSAHAAN`), Title (`Visi & Misi Masa Depan`), Vision (`Menjadi konglomerasi energi dan mineral terkemuka di Asia Tenggara...`), Body (`Mewujudkan operasional yang bertanggung jawab...`), Items: Integritas & Keselamatan, Keunggulan Operasional, Kemitraan Strategis Berkelanjutan.
   * Media: `pit-benches-1280.webp` (Primary), `excavator-loading-1280.webp` (Secondary).
6. **Section 6 (CTA Banner — `cta_banner`):**
   * Teks: Eyebrow (`KEMITRAAN BISNIS`), Title (`Siap Membangun Sinergi Energi Bersama Kami?`), Subtitle (`Tim pemasaran dan pengembangan bisnis kami siap mendiskusikan kebutuhan pasokan komoditas Anda.`), Phone (`+62 21 5088 0123`), CTA Label (`Kirim Penawaran Bisnis`), CTA Link (`/id/kontak`).

#### Halaman 1.2: Tentang Kami (`/id/tentang-kami`, `/en/about-us`)
1. **Section 1 (Hero — `hero`):**
   * Teks: Eyebrow (`TENTANG KAMI`), Title (`Integritas, Pengalaman, dan Komitmen Energi`), Description (`Profil korporasi, kepemimpinan, dan perjalanan grup penambangan terpadu.`), Slides: 3 foto konsesi (`hero-excavator-1280.webp`, `crushing-plant-1280.webp`, `barge-jetty-1280.webp`).
2. **Section 2 (Profil & Sejarah — `stacked_images`):**
   * Teks: Eyebrow (`JEJAK LANGKAH`), Title (`Dedikasi Puluhan Tahun Mengelola Sumber Daya Alam`), Lead (`Didirikan dengan visi memperkuat ketahanan pasokan komoditas industri dalam negeri dan ekspor.`), Body (`Perusahaan terus bertransformasi mengadopsi teknologi penambangan presisi...`).
   * Media: `aerial-pit-1280.webp`, `pit-benches-1280.webp`.
3. **Section 3 (Visi & Misi Korporasi — `mission_list`):**
   * Teks: Visi Korporat, Misi 4 Pilar, Poin Komitmen Sosial Lingkungan (CSR).
4. **Section 4 (Nilai Inti — `values_grid`):**
   * Teks: 4 Kartu Nilai (Integritas, Keselamatan Kerja, Keberlanjutan, Kolaborasi Andal).
5. **Section 5 (Struktur Entitas — `subsidiary_grid`):**
   * Teks: Eyebrow (`STRUKTUR GRUP`), Title (`Anak Perusahaan & Entitas Afiliasi`), Grid 3 Anak Perusahaan.
6. **Section 6 (CTA Banner — `cta_banner`):**
   * Teks: Banner kontak korporat & investor relations.

#### Halaman 1.3: Bisnis Kami (`/id/bisnis-kami`, `/en/our-business`)
1. **Section 1 (Hero — `hero`):**
   * Teks: Eyebrow (`PORTFOLIO BISNIS`), Title (`Ekosistem Energi dari Hulu ke Hilir`), Description (`Portofolio komoditas batubara, bijih nikel, dan layanan penunjang logistik maritim.`), Slides: 3 gambar portofolio bisnis.
2. **Section 2 (Daftar Anak Perusahaan Terperinci — `subsidiary_grid`):**
   * Teks: Ringkasan profil lengkap PT Korin Berkat Energy, PT Beramal Sukses Sejahtera, dan PT Korin Bara Energy (spesifikasi komoditas, lokasi tambang, kapasitas tahunan, tautan ke website anak usaha).

#### Halaman 1.4: Hubungi Kami (`/id/kontak`, `/en/contact`)
1. **Section 1 (Informasi Kontak — `contact_info`):**
   * Teks: Eyebrow (`HUBUNGI KAMI`), Title (`Kantor Pusat & Perwakilan`), Alamat Kantor Holding, Telepon, Email Resmi, WhatsApp Bisnis, Jam Operasional, Embed Peta Koordinat.
2. **Section 2 (Formulir Kontak — `contact_form`):**
   * Teks: Eyebrow (`KIRIM PESAN`), Title (`Hubungi Tim Manajemen`), Label Field (Nama Lengkap, Perusahaan, Email, Nomor Telepon, Subjek, Pesan), Placeholder, Label Tombol Kirim (`Kirim Pesan Sekarang`).

#### Footer Perusahaan Holding:
* Logo: `Logo-DercoBaraEnergy-light.png`
* Teks: Deskripsi singkat holding, Alamat kantor pusat, Kontak cepat (telepon, email), Link Navigasi Cepat, Link Anak Usaha, Hak Cipta (`© 2026 PT Derco Bara Energy. Seluruh hak cipta dilindungi.`).

---

### 2. PT Korin Berkat Energy (`korin-berkat-energy`)
* **Tema Visual:** `korin-berkat` (*Editorial Index* — Deep Navy `#121E31`, Amber Gold `#EDB856`, Clean Architectural Layout).
* **Header Perusahaan:**
  * Logo: `Logo-KorinBerkatEnergy.png` / `Logo-KorinBerkatEnergy-light.png` / `Logo-KorinBerkatEnergy-compact.png`.
  * Menu Navigasi: Beranda, Tentang Kami, Produk, Operasi, Kontak.

#### Halaman 2.1: Beranda (`/id/bisnis-kami/korin-berkat-energy`)
1. **Section 1 (Hero — `hero`):**
   * Teks: Eyebrow (`PT KORIN BERKAT ENERGY`), Title (`Produsen Batubara Termal Kualitas Ekspor Berkalori Tinggi`), Description (`Spesifikasi kalori andal untuk pembangkit listrik dan manufaktur Asia.`), Primary CTA (`Katalog Spesifikasi`), Secondary CTA (`Wilayah Kerja Tambang`).
   * Media: `hero-excavator-1280.webp`.
2. **Section 2 (Statistik Kinerja — `stats`):**
   * Teks: Eyebrow (`RINGKASAN OPERASIONAL`), Title (`Kapasitas & Kapabilitas Produksi`), Angka Metrik (Kapasitas Tahunan 5.0 MT, Cadangan Terbukti, Nilai Kalori Rata-rata 5,800 GAR, Ketepatan Waktu Pengiriman 99.2%).
3. **Section 3 (Katalog Produk Unggulan — `product_list`):**
   * Teks: Eyebrow (`KATALOG KOMODITAS`), Title (`Batubara Sub-Bituminous & Bituminous`), Tabel Spesifikasi Ringkas (CV, TM, Ash, TS), Tombol Unduh Spesifikasi PDF.
4. **Section 4 (Alur & Titik Operasi — `operation_list`):**
   * Teks: Eyebrow (`INFRASTRUKTUR`), Title (`Lokasi Penambangan, Stockpile, dan Jetty Muat`), Peta Operasional Kalimantan Timur, 4 Tahapan Rantai Pasok (Pit Mining $\rightarrow$ Crushing Plant $\rightarrow$ Barge Jetty $\rightarrow$ Vessel Transshipment).
5. **Section 5 (CTA Banner — `cta_banner`):**
   * Teks: Ajakan kerjasama kontrak pasokan batubara industri.

#### Halaman 2.2: Tentang Kami (`.../tentang-kami`)
1. **Section 1 (Split Content — `split_content`):** Profil operasional, latar belakang IUP OP, standar K3L penambangan.
2. **Section 2 (Misi & Tata Kelola — `mission_list`):** Prioritas keselamatan penambangan & reklamasi pit.
3. **Section 3 (Nilai Perusahaan — `values_grid`):** 4 nilai kerja Korin Berkat.

#### Halaman 2.3: Produk (`.../produk`)
1. **Section 1 (Daftar Produk Lengkap — `product_list`):**
   * Menampilkan semua grade produk terbit: *Korin High-CV Thermal Coal 6300*, *Korin Medium-CV Eco Coal 5500*, dsb.
   * Parameter spesifikasi detail (GAR, NAR, Total Moisture, Ash Content, Volatile Matter, Total Sulfur, HGI).
   * Tombol Permintaan Kargo / Inquiry & Unduh Lembar Spesifikasi.

#### Halaman 2.4: Operasi (`.../operasi`)
1. **Section 1 (Infrastruktur & Rantai Pasok — `operation_list`):**
   * Peta koordinat PIT Muara Teweh, Jetty Muat Sungai Barito, Armada Tongkang 300ft, Transshipment Taboneo.

#### Halaman 2.5: Kontak (`.../kontak`)
1. **Section 1 (Kontak Kantor Lapangan & Jakarta — `contact_info`):** Alamat kantor operasional Samarinda & Balikpapan.
2. **Section 2 (Formulir Penawaran Kargo — `contact_form`):** Form pemesanan spesifikasi batubara.

#### Footer PT Korin Berkat Energy:
* Logo terang Korin Berkat, Alamat Samarinda & Jakarta, Navigasi Halaman, Tautan Induk Holding, Hak Cipta.

---

### 3. PT Korin Bara Energy (`korin-bara-energy`)
* **Tema Visual:** `korin-bara` (*Field Rail* — Dark Coal Charcoal `#101826`, Safety Amber `#F59E0B`, High Contrast).
* **Header Perusahaan:**
  * Logo: `Logo-KorinBaraEnergy-compact.png` / `Logo-KorinBerkatEnergy-light.png`.
  * Menu Navigasi: Beranda, Tentang Kami, Produk, Operasi, Kontak (Catatan: *Mutu & Kepatuhan dihapus di Revisi 6*).
* **Struktur Halaman:**
  * **Beranda:** Hero, Stats, Product List, Operation List, CTA Banner.
  * **Tentang Kami:** Split Content, Timeline Sejarah Penambangan, Mission List, Values Grid.
  * **Produk:** Product List (Batubara Termal Kalori Sedang).
  * **Operasi:** Operation List (Pit Penambangan & Hauling Road).
  * **Kontak:** Contact Info & Contact Form.
* **Footer:** Identitas Korin Bara, Alamat Site & Kantor Perwakilan, Hak Cipta.

---

### 4. PT Beramal Sukses Sejahtera (`beramal-sukses-sejahtera`)
* **Tema Visual:** `beramal` (*Calm Flow* — Deep Marine `#0E2A47`, Ocean Cerulean `#0284C7`, Modern Clean Surface).
* **Header Perusahaan:**
  * Logo: `Logo-BeramalSuksesSejahtera.png` / `Logo-BeramalSuksesSejahtera-light.png` / `Logo-BeramalSuksesSejahtera-compact.png`.
  * Menu Navigasi: Beranda, Tentang Kami, Layanan & Produk, Operasi, Kontak (Catatan: *Mutu & Kepatuhan dihapus di Revisi 6*).
* **Struktur Halaman:**
  * **Beranda:** Hero (Layanan Niaga & Logistik Maritim), Stats, Layanan & Produk List, CTA Banner.
  * **Tentang Kami:** Split Content, Mission List, Values Grid.
  * **Layanan & Produk:** Product List (Trading Batubara, Nikel, Sewa Tongkang Tugboat).
  * **Operasi:** Operation List (Depo Stockpile Cilegon, Dermaga Muat Maritim).
  * **Kontak:** Contact Info & Contact Form.
* **Footer:** Identitas Beramal, Alamat Jakarta & Banten, Hak Cipta.

---

## B. Sumber Teks: CMS, Lang, dan Literal Hardcoded di Kode

### 1. Teks Bersumber dari Database CMS (`pages`, `page_blocks`, tabel relasi)
* Judul Halaman (`title`), Slug URL (`slug`), Meta Title & Meta Description.
* Isi field blok: `eyebrow`, `title`, `description`, `lead`, `body`, `vision`, `primary_cta_label`, `secondary_cta_label`, `cta_label`, `cta_href`.
* Data entitas khusus:
  * Katalog Produk (`products`, `product_specs`): Nama, kode, deskripsi, parameter gar/nar/ash/sulfur.
  * Titik Operasi (`operations`): Nama lokasi, tipe, koordinat lintang/bujur, deskripsi.
  * Statistik (`company_stats`): Label metrik, angka nilai, awalan/akhiran satuan.
  * Kontak (`company_contacts`): Tipe, label, nilai alamat/telepon/email, jam kerja.

### 2. Teks Bersumber dari Berkas Bahasa (`lang/id/ui.php`, `lang/en/ui.php`)
* Navigasi Teknis: `skip_to_content`, `open_menu`, `close_menu`, `back_to_top`, `back_to_holding`, `holding_short`.
* Heading Footer: `footer.address_heading`, `footer.hours_heading`, `footer.subsidiaries_heading`, `footer.rights_reserved`, `footer.parent_group_label`.
* Parameter Produk Teknis: `products.specifications`, `products.parameter`, `products.code`, `products.value`, `products.unit`, `products.basis`, `products.download_spec`, `products.inquire_product`, `products.catalog_index`.
* Alur Rantai Pasok: `operations.flow_eyebrow`, `operations.flow_heading`, `operations.map_heading`, `operations.locations_heading`, `operations.flow_steps.*` (Pit, Crushing, Jetty, Vessel).
* Form Pesan: `contact.form_heading`, `contact.submit_button`, `contact.success_title`, `contact.success_message`.

### 3. Teks Literal / Hardcoded di JSX (`resources/js/`) yang Wajib Dimigrasikan ke CMS (Tahap 3)
* Nomor Urut dan Indikator Statis: `"01"`, `"02"`, `"03"`, `"04"` pada `MissionListBlock.tsx`, `ValuesGridBlock.tsx`, `OperationListBlock.tsx`.
* Label Badge Statis: `"Holding"`, `"Anak Perusahaan"`, `"Spesifikasi Lengkap"` pada beberapa komponen blok.
* Teks fallback default ketika relasi kosong pada footer dan header (`"PT Derco Bara Energy"`, `"Mining · Trading · Nickel"`).
* Pesan copyright bawaan bila data company null.

---

## C. Daftar Gambar Statis & Aset Tertulis di Kode

| Nama Berkas Aset | Path Fisik | Lokasi Pemanggilan Saat Ini | Status Target (Revisi 6) |
|---|---|---|---|
| `Logo-DercoBaraEnergy.png` | `public/images/logo/` | `config/branding.php`, Seeder | Dipindah ke Media Library CMS (Header Beranda) |
| `Logo-DercoBaraEnergy-light.png` | `public/images/logo/` | `config/branding.php`, Header/Footer | Dibuat otomatis / diunggah di CMS |
| `Logo-DercoBaraEnergy-compact.png` | `public/images/logo/` | `config/branding.php`, Sidebar | Media Library CMS |
| `Logo-KorinBerkatEnergy.png` | `public/images/logo/` | `config/branding.php`, Seeder | Media Library CMS |
| `Logo-KorinBerkatEnergy-light.png` | `public/images/logo/` | `config/branding.php`, Footer | Dibuat otomatis / diunggah di CMS |
| `Logo-KorinBerkatEnergy-compact.png` | `public/images/logo/` | `config/branding.php`, Sidebar | Media Library CMS |
| `Logo-BeramalSuksesSejahtera.png` | `public/images/logo/` | `config/branding.php`, Seeder | Media Library CMS |
| `Logo-BeramalSuksesSejahtera-light.png` | `public/images/logo/` | `config/branding.php`, Footer | Dibuat otomatis / diunggah di CMS |
| `Logo-BeramalSuksesSejahtera-compact.png` | `public/images/logo/` | `config/branding.php`, Sidebar | Media Library CMS |
| `hero-excavator-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Hero Banner CMS |
| `aerial-pit-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Stacked Images CMS |
| `coal-stockpile-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Color Band CMS |
| `barge-jetty-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Hero Slides CMS |
| `crushing-plant-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Hero Slides CMS |
| `haul-trucks-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Stacked Images CMS |
| `pit-benches-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Mission List CMS |
| `excavator-loading-1280.webp` | `public/images/placeholders/` | `DatabaseSeeder.php` JSON | Slot Mission List CMS |
| `dashboard-hero-banner.jpg` | `public/images/cms/` | `hero-banner.blade.php` | CMS Dashboard Widget / Media |
| `derco-login-banner.jpg` | `public/images/cms/` | `auth.blade.php` | CMS Login Background |

---

## D. Keadaan CMS Saat Ini

1. **Filament Version:** `v5.11.2` (Filament v5 Panels, Actions, Forms, Tables, Schemas).
2. **Tenancy:**
   * Multi-tenancy terdaftar di `AdminPanelProvider` dengan model `App\Domain\Company\Models\Company` (slugAttribute: `slug`).
   * Akses tenant: Satu admin mengelola seluruh perusahaan tanpa tabel `company_user`. Method `getTenants()` mengembalikan semua perusahaan, dan `canAccessTenant()` mengembalikan `true`.
3. **Peran & Hak Akses:**
   * Model `User` memiliki method `isSuperAdmin()` dan `isEditor()`.
   * Kebijakan (*Policies*) terpusat pada `CmsBasePolicy`, `CompanyPolicy`, `UserPolicy`, `PagePolicy`, `SettingPolicy`.
4. **Set Halaman per Tema Saat Ini:**
   * Holding: 4 halaman (`home`, `about`, `business`, `contact`).
   * Korin Berkat: 5 halaman (`home`, `about`, `products`, `operations`, `contact`).
   * Beramal: 6 halaman (termasuk `compliance` yang disembunyikan / draft).
   * Korin Bara: 6 halaman (termasuk `compliance` terbit).
5. **Status Revisi Sebelumnya (1 s.d. 5):**
   * Revisi 1 (Blok & Layout Dasar): Selesai & tes lulus.
   * Revisi 2 (Set Halaman per Tema): Selesai & tes lulus.
   * Revisi 3 (Branding Logo & Variant): Selesai & tes lulus.
   * Revisi 4 (Redesain CMS Layout & Auth): Selesai sebagian (Dashboard widgets & auth layout).
   * Revisi 5 (Multi-tenancy SATU CMS): Selesai diimplementasikan, seluruh 79 Pest test hijau.
   * **Revisi 6 menggantikan seluruh Revisi 5 dan Bagian C Revisi 4.**

---

## E. Pemetaan Halaman CMS Target (Revisi 6 Butir 3 & 9)

Pada CMS v2 (Revisi 6), sidebar tidak lagi menampilkan resource tabel terpisah (*Halaman, Produk, Operasi, Statistik, Kontak*), melainkan langsung **Halaman Website Milik Perusahaan Terpilih**:

| Perusahaan Aktif | Menu Sidebar Halaman Website (Target) | Section & Blok Penyusun | Status Mutu & Kepatuhan |
|---|---|---|---|
| **PT Derco Bara Energy** (Holding) | 1. **Beranda** (termasuk Header)<br>2. **Tentang Kami**<br>3. **Bisnis Kami**<br>4. **Kontak**<br>5. **Footer** | 1. Header (Logo, Navigasi) + Hero + Unit Bisnis + Fokus Strategis + Pilar Keunggulan + Visi Misi + CTA<br>2. Hero + Jejak Langkah + Visi Misi + Nilai Inti + Struktur Grup + CTA<br>3. Hero + Entitas Anak Usaha<br>4. Kontak Info + Form Pesan<br>5. Identitas, Alamat, Jam, Navigasi Cepat, Hak Cipta | Tidak ada sejak awal |
| **PT Korin Berkat Energy** | 1. **Beranda** (termasuk Header)<br>2. **Tentang Kami**<br>3. **Produk**<br>4. **Operasi**<br>5. **Kontak**<br>6. **Footer** | 1. Header + Hero + Stats + Produk Unggulan + Titik Operasi + CTA<br>2. Split Content + Visi Misi + Nilai Inti<br>3. Katalog Produk Lengkap & Spesifikasi Tambang<br>4. Titik Operasi, Peta Wilayah, Alur Rantai Pasok<br>5. Kontak Kantor Lapangan + Form Pemesanan Kargo<br>6. Identitas, Alamat, Jam, Navigasi Cepat, Hak Cipta | Dihapus permanen (Revisi 2) |
| **PT Korin Bara Energy** | 1. **Beranda** (termasuk Header)<br>2. **Tentang Kami**<br>3. **Produk**<br>4. **Operasi**<br>5. **Kontak**<br>6. **Footer** | Sama dengan Korin Berkat (disesuaikan dengan data Korin Bara) | **Dihapus/Dinonaktifkan** (Revisi 6 Butir 9: halaman compliance dinonaktifkan, blok certification_list pada home disembunyikan/dihapus, modul dorman) |
| **PT Beramal Sukses Sejahtera** | 1. **Beranda** (termasuk Header)<br>2. **Tentang Kami**<br>3. **Produk** (Layanan & Produk)<br>4. **Operasi**<br>5. **Kontak**<br>6. **Footer** | Sama dengan Korin Berkat (disesuaikan dengan layanan niaga & maritim) | **Dihapus/Dinonaktifkan** (Revisi 6 Butir 9: data compliance dinonaktifkan permanen, modul dorman) |

### Menu Grup "Pengaturan" di Bawah Daftar Halaman (Semua Perusahaan):
1. **Pesan Masuk** (dengan badge jumlah pesan baru)
2. **Pengaturan Website** (Tenant profile: SEO default, deskripsi singkat, email kontak; Super Admin: tema & status tayang)
3. **Pengguna** (Hanya Super Admin)
4. **Perusahaan** (Hanya Super Admin)
5. **Logout**

---

### Catatan Penting untuk Tahap 2:
- Pseudo-halaman **Header** diedit sebagai kartu pertama pada **Beranda**, dengan label peringatan: *"Perubahan ini tampil di semua halaman"*.
- Pseudo-halaman **Footer** menjadi item terpisah di bagian bawah daftar halaman perusahaan.
- Halaman **Mutu & Kepatuhan** resmi dihapus dari seluruh entitas anak usaha sesuai Butir 9 Bagian 0; kode `Certification` dipertahankan sebagai modul dorman tanpa menu di CMS.
