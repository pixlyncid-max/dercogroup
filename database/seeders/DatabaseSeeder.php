<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Company\Actions\CreateCompanyPages;
use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyStat;
use App\Domain\Company\Models\Setting;
use App\Domain\Compliance\Enums\CertificationCategory;
use App\Domain\Compliance\Models\Certification;
use App\Domain\Contact\Enums\ContactType;
use App\Domain\Contact\Models\CompanyContact;
use App\Domain\Operation\Enums\OperationType;
use App\Domain\Operation\Models\Operation;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductSpec;
use App\Models\User;
use App\Support\SamplePdfGenerator;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seedRolesAndUsers();
        $this->seedSettings();

        $pdfId = SamplePdfGenerator::ensureDocument(
            'documents/sertifikat-kepatuhan-id.pdf',
            'Dokumen Sertifikasi & Izin Operasional [PLACEHOLDER]',
            'PT Derco Bara Energy Group - Salinan Verifikasi Kepatuhan (Bahasa Indonesia)',
        );
        $pdfEn = SamplePdfGenerator::ensureDocument(
            'documents/compliance-certificate-en.pdf',
            'Operational Permit & Compliance Certificate [PLACEHOLDER]',
            'PT Derco Bara Energy Group - Verification Copy (English)',
        );

        $holding = Company::query()->updateOrCreate(
            ['slug' => 'derco-bara-energy'],
            [
                'parent_id' => null,
                'name' => 'PT Derco Bara Energy',
                'theme' => CompanyTheme::Holding,
                'status' => CompanyStatus::Published,
                'sort_order' => 1,
                'tagline' => [
                    'id' => 'Induk usaha pertambangan batu bara, logistik pelabuhan, dan layanan operasional di Indonesia. [PLACEHOLDER]',
                    'en' => 'Holding company for coal mining, port logistics, and operational services in Indonesia. [PLACEHOLDER]',
                ],
                'about_short' => [
                    'id' => 'PT Derco Bara Energy mengoordinasikan kegiatan produksi tambang, pengelolaan stockpile, dan pengapalan batu bara melalui tiga entitas anak usaha dengan standar kepatuhan terintegrasi. [PLACEHOLDER]',
                    'en' => 'PT Derco Bara Energy coordinates mine production, stockpile management, and coal transshipment across three operating subsidiaries under unified compliance governance. [PLACEHOLDER]',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Derco Bara Energy [PLACEHOLDER]',
                    'en' => 'PT Derco Bara Energy Logo [PLACEHOLDER]',
                ],
            ],
        );

        $korinBerkat = Company::query()->updateOrCreate(
            ['slug' => 'korin-berkat-energy'],
            [
                'parent_id' => $holding->id,
                'name' => 'PT Korin Berkat Energy',
                'theme' => CompanyTheme::KorinBerkat,
                'status' => CompanyStatus::Published,
                'sort_order' => 1,
                'tagline' => [
                    'id' => 'Perdagangan batu bara termal dan manajemen pengapalan terukur untuk pembangkit dan industri B2B. [PLACEHOLDER]',
                    'en' => 'Thermal coal trading and measured shipment management for power utilities and industrial buyers. [PLACEHOLDER]',
                ],
                'about_short' => [
                    'id' => 'PT Korin Berkat Energy melayani kontrak pasokan batu bara kalori menengah dengan kepastian jadwal tongkang, uji laboratorium independen di titik muat, dan dokumentasi lengkap. [PLACEHOLDER]',
                    'en' => 'PT Korin Berkat Energy fulfills medium-calorific coal supply contracts with scheduled barge loading, independent laboratory verification at loading ports, and complete documentation. [PLACEHOLDER]',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Korin Berkat Energy [PLACEHOLDER]',
                    'en' => 'PT Korin Berkat Energy Logo [PLACEHOLDER]',
                ],
            ],
        );

        $beramal = Company::query()->updateOrCreate(
            ['slug' => 'beramal-sukses-sejahtera'],
            [
                'parent_id' => $holding->id,
                'name' => 'PT Beramal Sukses Sejahtera',
                'theme' => CompanyTheme::Beramal,
                'status' => CompanyStatus::Published,
                'sort_order' => 2,
                'tagline' => [
                    'id' => 'Dukungan logistik maritim, pengelolaan fasilitas pelabuhan, dan layanan operasional terpadu. [PLACEHOLDER]',
                    'en' => 'Maritime logistics support, port facility management, and integrated operational services. [PLACEHOLDER]',
                ],
                'about_short' => [
                    'id' => 'PT Beramal Sukses Sejahtera mengoperasikan fasilitas pendukung rantai pasok, koordinasi sandar tongkang, dan layanan teknis lapangan bagi mitra grup maupun pihak ketiga. [PLACEHOLDER]',
                    'en' => 'PT Beramal Sukses Sejahtera operates supply-chain support facilities, barge berthing coordination, and field technical services for group entities and third-party partners. [PLACEHOLDER]',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Beramal Sukses Sejahtera [PLACEHOLDER]',
                    'en' => 'PT Beramal Sukses Sejahtera Logo [PLACEHOLDER]',
                ],
            ],
        );

        $korinBara = Company::query()->updateOrCreate(
            ['slug' => 'korin-bara-energy'],
            [
                'parent_id' => $holding->id,
                'name' => 'PT Korin Bara Energy',
                'theme' => CompanyTheme::KorinBara,
                'status' => CompanyStatus::Draft,
                'sort_order' => 3,
                'tagline' => [
                    'id' => 'Operasi penambangan terbuka, pengangkutan darat, dan pengolahan batu bara di konsesi tambang. [PLACEHOLDER]',
                    'en' => 'Open-pit coal extraction, overland hauling, and crushing operations at concession sites. [PLACEHOLDER]',
                ],
                'about_short' => [
                    'id' => 'PT Korin Bara Energy mengelola kegiatan pengupasan lapisan tanah, penambangan pit, pengangkutan jalan angkut khusus, serta reklamasi pascatambang sesuai RKAB. [PLACEHOLDER]',
                    'en' => 'PT Korin Bara Energy manages overburden removal, pit extraction, dedicated haul-road transport, and progressive mine reclamation in accordance with RKAB approvals. [PLACEHOLDER]',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Korin Bara Energy [PLACEHOLDER]',
                    'en' => 'PT Korin Bara Energy Logo [PLACEHOLDER]',
                ],
            ],
        );

        $createPages = app(CreateCompanyPages::class);

        foreach ([$holding, $korinBerkat, $beramal, $korinBara] as $company) {
            $createPages->execute($company);
            $this->seedCompanyStats($company);
            $this->seedCompanyOperations($company);
            $this->seedCompanyCertifications($company, $pdfId, $pdfEn);
            $this->seedCompanyContacts($company);
        }

        foreach ([$korinBerkat, $beramal, $korinBara] as $subsidiary) {
            $this->seedSubsidiaryProducts($subsidiary);
            $this->seedSubsidiaryPageBlocks($subsidiary);
        }

        $this->seedHoldingPageBlocks($holding);
    }

    private function seedRolesAndUsers(): void
    {
        $superAdminRole = Role::findOrCreate(User::ROLE_SUPER_ADMIN, 'web');
        $editorRole = Role::findOrCreate(User::ROLE_EDITOR, 'web');

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@dercobara.co.id'],
            [
                'name' => 'Super Admin Derco',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles([$superAdminRole]);

        $editor = User::query()->updateOrCreate(
            ['email' => 'editor@dercobara.co.id'],
            [
                'name' => 'Editor Konten Derco',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );
        $editor->syncRoles([$editorRole]);
    }

    private function seedSettings(): void
    {
        Setting::setValue('seo_default_title', [
            'id' => 'PT Derco Bara Energy — Grup Pertambangan & Infrastruktur Energi [PLACEHOLDER]',
            'en' => 'PT Derco Bara Energy — Mining & Energy Infrastructure Group [PLACEHOLDER]',
        ], 'seo');

        Setting::setValue('seo_default_description', [
            'id' => 'Profil grup usaha PT Derco Bara Energy beserta unit bisnis pertambangan, perdagangan, dan logistik energi di Indonesia. [PLACEHOLDER]',
            'en' => 'Corporate profile of PT Derco Bara Energy and its coal mining, trading, and energy logistics subsidiaries in Indonesia. [PLACEHOLDER]',
        ], 'seo');

        Setting::setValue('contact_default_email', [
            'email' => 'corsec@dercobara.co.id',
        ], 'contact');

        Setting::setValue('analytics_code', [
            'id' => '',
        ], 'analytics');
    }

    private function seedCompanyStats(Company $company): void
    {
        CompanyStat::query()->where('company_id', $company->id)->delete();

        $statsBySlug = [
            'derco-bara-energy' => [
                ['value' => 3, 'suffix' => '', 'unit' => ['id' => 'Entitas Anak Usaha', 'en' => 'Operating Subsidiaries'], 'label' => ['id' => 'Struktur Operasional Grup [PLACEHOLDER]', 'en' => 'Group Operational Structure [PLACEHOLDER]']],
                ['value' => 2400000, 'suffix' => '', 'unit' => ['id' => 'MT / Tahun', 'en' => 'MT / Year'], 'label' => ['id' => 'Kapasitas Rantai Pasok Terkelola [PLACEHOLDER]', 'en' => 'Managed Supply Chain Capacity [PLACEHOLDER]']],
                ['value' => 2, 'suffix' => '', 'unit' => ['id' => 'Pelabuhan Muat', 'en' => 'Loading Ports'], 'label' => ['id' => 'Titik Pengapalan Kalimantan & Sumatera [PLACEHOLDER]', 'en' => 'Kalimantan & Sumatra Loading Points [PLACEHOLDER]']],
                ['value' => 2016, 'suffix' => '', 'unit' => ['id' => 'Tahun Berdiri', 'en' => 'Year Established'], 'label' => ['id' => 'Konsolidasi Operasional Grup [PLACEHOLDER]', 'en' => 'Group Operational Consolidation [PLACEHOLDER]']],
            ],
            'korin-berkat-energy' => [
                ['value' => 1500000, 'suffix' => '', 'unit' => ['id' => 'MT / Tahun', 'en' => 'MT / Year'], 'label' => ['id' => 'Volume Kontrak Perdagangan [PLACEHOLDER]', 'en' => 'Contracted Trading Volume [PLACEHOLDER]']],
                ['value' => 4200, 'suffix' => '', 'unit' => ['id' => 'kcal/kg GAR', 'en' => 'kcal/kg GAR'], 'label' => ['id' => 'Spesifikasi Utama Pengapalan [PLACEHOLDER]', 'en' => 'Primary Shipment Specification [PLACEHOLDER]']],
                ['value' => 300, 'suffix' => ' ft', 'unit' => ['id' => 'Ukuran Tongkang', 'en' => 'Barge Class'], 'label' => ['id' => 'Kapasitas Muat Standar [PLACEHOLDER]', 'en' => 'Standard Loading Capacity [PLACEHOLDER]']],
                ['value' => 24, 'suffix' => ' jam', 'unit' => ['id' => 'Turnaround Muat', 'en' => 'Loading Turnaround'], 'label' => ['id' => 'Rata-rata Waktu Sandar Jetty [PLACEHOLDER]', 'en' => 'Average Jetty Berthing Time [PLACEHOLDER]']],
            ],
            'beramal-sukses-sejahtera' => [
                ['value' => 18, 'suffix' => '', 'unit' => ['id' => 'Unit Armada & Alat', 'en' => 'Fleet & Equipment Units'], 'label' => ['id' => 'Fasilitas Pendukung Operasional [PLACEHOLDER]', 'en' => 'Operational Support Assets [PLACEHOLDER]']],
                ['value' => 96, 'suffix' => '%', 'unit' => ['id' => 'Ketersediaan Jadwal', 'en' => 'Schedule Availability'], 'label' => ['id' => 'Kepatuhan SLA Layanan Lapangan [PLACEHOLDER]', 'en' => 'Field Service SLA Compliance [PLACEHOLDER]']],
                ['value' => 4, 'suffix' => '', 'unit' => ['id' => 'Titik Operasional', 'en' => 'Operational Hubs'], 'label' => ['id' => 'Jaringan Layanan & Koordinasi [PLACEHOLDER]', 'en' => 'Service & Coordination Network [PLACEHOLDER]']],
                ['value' => 0, 'suffix' => '', 'unit' => ['id' => 'Insiden Fatal (LTI)', 'en' => 'Fatal Incidents (LTI)'], 'label' => ['id' => 'Catatan Keselamatan Kerja [PLACEHOLDER]', 'en' => 'Occupational Safety Record [PLACEHOLDER]']],
            ],
            'korin-bara-energy' => [
                ['value' => 1850, 'suffix' => ' Ha', 'unit' => ['id' => 'Luas Konsesi IUP', 'en' => 'IUP Concession Area'], 'label' => ['id' => 'Wilayah Izin Usaha Pertambangan [PLACEHOLDER]', 'en' => 'Mining Permit Area [PLACEHOLDER]']],
                ['value' => 900000, 'suffix' => '', 'unit' => ['id' => 'MT / Tahun', 'en' => 'MT / Year'], 'label' => ['id' => 'Target Produksi RKAB [PLACEHOLDER]', 'en' => 'RKAB Production Target [PLACEHOLDER]']],
                ['value' => 28, 'suffix' => ' km', 'unit' => ['id' => 'Jalan Angkut Khusus', 'en' => 'Dedicated Haul Road'], 'label' => ['id' => 'Koridor Pit ke Stockpile Pelabuhan [PLACEHOLDER]', 'en' => 'Pit-to-Port Haulage Corridor [PLACEHOLDER]']],
                ['value' => 450, 'suffix' => ' TPH', 'unit' => ['id' => 'Kapasitas Crushing', 'en' => 'Crushing Capacity'], 'label' => ['id' => 'Instalasi Pengolahan Batu Bara [PLACEHOLDER]', 'en' => 'Coal Processing Plant [PLACEHOLDER]']],
            ],
        ];

        $items = $statsBySlug[$company->slug] ?? $statsBySlug['derco-bara-energy'];

        foreach ($items as $index => $stat) {
            CompanyStat::query()->create([
                'company_id' => $company->id,
                'value' => $stat['value'],
                'prefix' => null,
                'suffix' => $stat['suffix'],
                'unit' => $stat['unit'],
                'label' => $stat['label'],
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function seedCompanyOperations(Company $company): void
    {
        Operation::query()->where('company_id', $company->id)->delete();

        $operations = [
            [
                'type' => OperationType::Mine,
                'name' => [
                    'id' => "Blok Tambang Terbuka {$company->name} [PLACEHOLDER]",
                    'en' => "{$company->name} Open-Pit Mining Block [PLACEHOLDER]",
                ],
                'location_label' => [
                    'id' => 'Kutai Kartanegara, Kalimantan Timur [PLACEHOLDER]',
                    'en' => 'Kutai Kartanegara, East Kalimantan [PLACEHOLDER]',
                ],
                'description' => [
                    'id' => 'Operasi pengupasan lapisan penutup dan pengambilan batu bara dengan pemantauan geoteknik lereng harian serta sistem drainase kolam pengendap. [PLACEHOLDER]',
                    'en' => 'Overburden stripping and coal extraction with daily slope geotechnical monitoring and settling-pond drainage management. [PLACEHOLDER]',
                ],
                'latitude' => -0.4218000,
                'longitude' => 116.9824000,
            ],
            [
                'type' => OperationType::Stockpile,
                'name' => [
                    'id' => 'Fasilitas Crushing & Stockpile ROM [PLACEHOLDER]',
                    'en' => 'Crushing & ROM Stockpile Facility [PLACEHOLDER]',
                ],
                'location_label' => [
                    'id' => 'KM 28 Jalur Hauling Khusus, Kalimantan Timur [PLACEHOLDER]',
                    'en' => 'KM 28 Dedicated Haul Road, East Kalimantan [PLACEHOLDER]',
                ],
                'description' => [
                    'id' => 'Unit peremukan batu bara kapasitas 450 ton/jam dengan pemisahan ukuran otomatis dan area timbun terpisah berdasarkan spesifikasi kalori. [PLACEHOLDER]',
                    'en' => '450 TPH coal crushing plant with automated sizing screen and segregated stockpile pads organized by calorific grade. [PLACEHOLDER]',
                ],
                'latitude' => -0.5112000,
                'longitude' => 117.1045000,
            ],
            [
                'type' => OperationType::Port,
                'name' => [
                    'id' => 'Terminal Khusus Muat Tongkang (Jetty) [PLACEHOLDER]',
                    'en' => 'Dedicated Barge Loading Terminal (Jetty) [PLACEHOLDER]',
                ],
                'location_label' => [
                    'id' => 'Alur Sungai Mahakam, Kalimantan Timur [PLACEHOLDER]',
                    'en' => 'Mahakam River Channel, East Kalimantan [PLACEHOLDER]',
                ],
                'description' => [
                    'id' => 'Pelabuhan muat conveyor untuk tongkang 270–300 kaki lengkap dengan jembatan timbang terkalibrasi dan pengambilan sampel mekanis. [PLACEHOLDER]',
                    'en' => 'Conveyor barge-loading terminal serving 270–300 ft barges, equipped with calibrated weighbridges and mechanical sampling stations. [PLACEHOLDER]',
                ],
                'latitude' => -0.5689000,
                'longitude' => 117.2130000,
            ],
            [
                'type' => OperationType::Fleet,
                'name' => [
                    'id' => 'Armada Hauling & Koordinasi Transshipment [PLACEHOLDER]',
                    'en' => 'Hauling Fleet & Transshipment Coordination [PLACEHOLDER]',
                ],
                'location_label' => [
                    'id' => 'Koridor Logistik Kalimantan – Jawa [PLACEHOLDER]',
                    'en' => 'Kalimantan – Java Logistics Corridor [PLACEHOLDER]',
                ],
                'description' => [
                    'id' => 'Pengendalian jadwal truk angkut dump truck dan pemantauan posisi tongkang secara berkala hingga titik bongkar pembeli. [PLACEHOLDER]',
                    'en' => 'Dispatch control for dump-truck haulage and scheduled barge tracking through to buyer discharge ports. [PLACEHOLDER]',
                ],
                'latitude' => -0.6010000,
                'longitude' => 117.3020000,
            ],
        ];

        foreach ($operations as $index => $op) {
            Operation::query()->create([
                'company_id' => $company->id,
                'type' => $op['type'],
                'name' => $op['name'],
                'location_label' => $op['location_label'],
                'description' => $op['description'],
                'latitude' => $op['latitude'],
                'longitude' => $op['longitude'],
                'image_alt' => $op['name'],
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function seedCompanyCertifications(Company $company, string $pdfId, string $pdfEn): void
    {
        Certification::query()->where('company_id', $company->id)->delete();

        $certifications = [
            [
                'category' => CertificationCategory::Permit,
                'name' => [
                    'id' => 'Izin Usaha Pertambangan / Operasi Produksi & Pengangkutan [PLACEHOLDER]',
                    'en' => 'Mining Business Permit / Production & Transport License [PLACEHOLDER]',
                ],
                'issuer' => [
                    'id' => 'Kementerian Energi dan Sumber Daya Mineral RI [PLACEHOLDER]',
                    'en' => 'Ministry of Energy and Mineral Resources of Indonesia [PLACEHOLDER]',
                ],
                'number' => 'SK-IUP-OP/2022/0841 [PLACEHOLDER]',
                'issued_at' => '2022-03-15',
                'expires_at' => '2032-03-15',
            ],
            [
                'category' => CertificationCategory::Certificate,
                'name' => [
                    'id' => 'Sertifikat Sistem Manajemen Mutu ISO 9001:2015 [PLACEHOLDER]',
                    'en' => 'ISO 9001:2015 Quality Management System Certificate [PLACEHOLDER]',
                ],
                'issuer' => [
                    'id' => 'Badan Sertifikasi Akreditasi Nasional [PLACEHOLDER]',
                    'en' => 'National Accredited Certification Body [PLACEHOLDER]',
                ],
                'number' => 'QMS-ID-2024-1192 [PLACEHOLDER]',
                'issued_at' => '2024-01-10',
                'expires_at' => '2027-01-10',
            ],
            [
                'category' => CertificationCategory::Certificate,
                'name' => [
                    'id' => 'Sistem Manajemen Keselamatan Pertambangan (SMKP) & ISO 45001 [PLACEHOLDER]',
                    'en' => 'Mining Safety Management System (SMKP) & ISO 45001 [PLACEHOLDER]',
                ],
                'issuer' => [
                    'id' => 'Direktorat Teknik dan Lingkungan Minerba [PLACEHOLDER]',
                    'en' => 'Directorate of Mineral and Coal Engineering and Environment [PLACEHOLDER]',
                ],
                'number' => 'SMKP-2024-0418 [PLACEHOLDER]',
                'issued_at' => '2024-05-20',
                'expires_at' => '2027-05-20',
            ],
            [
                'category' => CertificationCategory::Policy,
                'name' => [
                    'id' => 'Kebijakan K3LH, Anti-Penyuapan, dan Kepatuhan Rantai Pasok [PLACEHOLDER]',
                    'en' => 'HSE, Anti-Bribery, and Supply Chain Compliance Policy [PLACEHOLDER]',
                ],
                'issuer' => [
                    'id' => "Direksi {$company->name} [PLACEHOLDER]",
                    'en' => "Board of Directors of {$company->name} [PLACEHOLDER]",
                ],
                'number' => 'POL-GOV-2025-01 [PLACEHOLDER]',
                'issued_at' => '2025-01-02',
                'expires_at' => null,
            ],
            [
                'category' => CertificationCategory::Report,
                'name' => [
                    'id' => 'Laporan Pemantauan Lingkungan & Reklamasi Berkala (RKL-RPL) [PLACEHOLDER]',
                    'en' => 'Periodic Environmental Monitoring & Reclamation Report (RKL-RPL) [PLACEHOLDER]',
                ],
                'issuer' => [
                    'id' => 'Dinas Lingkungan Hidup Provinsi [PLACEHOLDER]',
                    'en' => 'Provincial Environmental Agency [PLACEHOLDER]',
                ],
                'number' => 'RKL-RPL/2025/II [PLACEHOLDER]',
                'issued_at' => '2025-07-01',
                'expires_at' => '2026-07-01',
            ],
        ];

        foreach ($certifications as $index => $cert) {
            Certification::query()->create([
                'company_id' => $company->id,
                'category' => $cert['category'],
                'name' => $cert['name'],
                'issuer' => $cert['issuer'],
                'number' => $cert['number'],
                'issued_at' => $cert['issued_at'],
                'expires_at' => $cert['expires_at'],
                'file_path_id' => $pdfId,
                'file_path_en' => $pdfEn,
                'sort_order' => $index + 1,
            ]);
        }
    }

    private function seedCompanyContacts(Company $company): void
    {
        CompanyContact::query()->where('company_id', $company->id)->delete();

        CompanyContact::query()->create([
            'company_id' => $company->id,
            'type' => ContactType::HeadOffice,
            'label' => [
                'id' => "Kantor Pusat {$company->name} [PLACEHOLDER]",
                'en' => "{$company->name} Head Office [PLACEHOLDER]",
            ],
            'address' => [
                'id' => 'Gedung Office 8 Lantai 18, Kawasan SCBD Lot 28, Jl. Jend. Sudirman Kav. 52–53, Jakarta Selatan 12190 [PLACEHOLDER]',
                'en' => 'Office 8 Building 18th Floor, SCBD Lot 28, Jl. Jend. Sudirman Kav. 52–53, South Jakarta 12190 [PLACEHOLDER]',
            ],
            'phone' => '+62 21 5088 7400',
            'email' => "info@{$company->slug}.co.id",
            'notify_email' => "corsec@{$company->slug}.co.id",
            'whatsapp' => '+62 811 8807 400',
            'operating_hours' => [
                'id' => 'Senin – Jumat: 08.30 – 17.30 WIB · Sabtu: 09.00 – 13.00 WIB [PLACEHOLDER]',
                'en' => 'Monday – Friday: 08:30 – 17:30 WIB · Saturday: 09:00 – 13:00 WIB [PLACEHOLDER]',
            ],
            'socials' => [
                'linkedin' => "https://www.linkedin.com/company/{$company->slug}",
            ],
            'latitude' => -6.2286000,
            'longitude' => 106.8075000,
        ]);
    }

    private function seedSubsidiaryProducts(Company $company): void
    {
        Product::query()->where('company_id', $company->id)->delete();

        $catalog = [
            [
                'slug' => 'thermal-coal-gar-4200',
                'name' => [
                    'id' => 'Batu Bara Termal GAR 4.200 [PLACEHOLDER]',
                    'en' => 'Thermal Coal GAR 4,200 [PLACEHOLDER]',
                ],
                'category_label' => [
                    'id' => 'Kalori Menengah · GAR 4.200 kcal/kg',
                    'en' => 'Medium Calorific · GAR 4,200 kcal/kg',
                ],
                'summary' => [
                    'id' => 'Batu bara sub-bituminus bersulfur rendah untuk PLTU sirkulasi fluidized bed dan boiler industri. [PLACEHOLDER]',
                    'en' => 'Low-sulfur sub-bituminous coal suited for circulating fluidized-bed power plants and industrial boilers. [PLACEHOLDER]',
                ],
                'description' => [
                    'id' => 'Diproduksi melalui proses peremukan terkontrol ukuran 0–50 mm dengan uji kualitas independen (COA) pada setiap pengapalan tongkang. [PLACEHOLDER]',
                    'en' => 'Produced via controlled 0–50 mm crushing with independent Certificate of Analysis (COA) verification on every barge shipment. [PLACEHOLDER]',
                ],
                'specs' => [
                    ['code' => 'CV', 'label' => ['id' => 'Nilai Kalori (GAR)', 'en' => 'Calorific Value (GAR)'], 'value' => '4.200', 'unit' => 'kcal/kg', 'is_highlight' => true],
                    ['code' => 'TM', 'label' => ['id' => 'Total Moisture (ARB)', 'en' => 'Total Moisture (ARB)'], 'value' => '34,0 – 36,0', 'unit' => '%', 'is_highlight' => true],
                    ['code' => 'ASH', 'label' => ['id' => 'Kadar Abu / Ash (ADB)', 'en' => 'Ash Content (ADB)'], 'value' => '5,5 – 7,0', 'unit' => '%', 'is_highlight' => true],
                    ['code' => 'TS', 'label' => ['id' => 'Total Sulfur (ADB)', 'en' => 'Total Sulfur (ADB)'], 'value' => '≤ 0,45', 'unit' => '%', 'is_highlight' => false],
                    ['code' => 'SIZE', 'label' => ['id' => 'Ukuran Butir (0–50 mm)', 'en' => 'Size Distribution (0–50 mm)'], 'value' => '≥ 90', 'unit' => '%', 'is_highlight' => false],
                ],
            ],
            [
                'slug' => 'thermal-coal-gar-4800',
                'name' => [
                    'id' => 'Batu Bara Termal GAR 4.800 [PLACEHOLDER]',
                    'en' => 'Thermal Coal GAR 4,800 [PLACEHOLDER]',
                ],
                'category_label' => [
                    'id' => 'Kalori Menengah-Tinggi · GAR 4.800 kcal/kg',
                    'en' => 'Medium-High Calorific · GAR 4,800 kcal/kg',
                ],
                'summary' => [
                    'id' => 'Pasokan batu bara dengan efisiensi pembakaran stabil untuk pabrik semen, tekstil, dan pengolahan kimia. [PLACEHOLDER]',
                    'en' => 'Consistent combustion-efficiency coal supply for cement kilns, textile mills, and chemical processing plants. [PLACEHOLDER]',
                ],
                'description' => [
                    'id' => 'Dikelola pada area stockpile terpisah guna menjaga kestabilan kelembapan dan keseragaman nilai kalori antar-pengapalan. [PLACEHOLDER]',
                    'en' => 'Managed on dedicated stockpile pads to preserve moisture stability and calorific uniformity across shipments. [PLACEHOLDER]',
                ],
                'specs' => [
                    ['code' => 'CV', 'label' => ['id' => 'Nilai Kalori (GAR)', 'en' => 'Calorific Value (GAR)'], 'value' => '4.800', 'unit' => 'kcal/kg', 'is_highlight' => true],
                    ['code' => 'TM', 'label' => ['id' => 'Total Moisture (ARB)', 'en' => 'Total Moisture (ARB)'], 'value' => '26,0 – 28,0', 'unit' => '%', 'is_highlight' => true],
                    ['code' => 'ASH', 'label' => ['id' => 'Kadar Abu / Ash (ADB)', 'en' => 'Ash Content (ADB)'], 'value' => '7,0 – 9,0', 'unit' => '%', 'is_highlight' => true],
                    ['code' => 'TS', 'label' => ['id' => 'Total Sulfur (ADB)', 'en' => 'Total Sulfur (ADB)'], 'value' => '≤ 0,65', 'unit' => '%', 'is_highlight' => false],
                    ['code' => 'HGI', 'label' => ['id' => 'Hardgrove Grindability Index', 'en' => 'Hardgrove Grindability Index'], 'value' => '48 – 52', 'unit' => 'HGI', 'is_highlight' => false],
                ],
            ],
            [
                'slug' => 'thermal-coal-gar-3800',
                'name' => [
                    'id' => 'Batu Bara Termal GAR 3.800 [PLACEHOLDER]',
                    'en' => 'Thermal Coal GAR 3,800 [PLACEHOLDER]',
                ],
                'category_label' => [
                    'id' => 'Abu & Sulfur Sangat Rendah · GAR 3.800 kcal/kg',
                    'en' => 'Ultra-Low Ash & Sulfur · GAR 3,800 kcal/kg',
                ],
                'summary' => [
                    'id' => 'Batu bara ramah emisi dengan kadar abu dan sulfur sangat rendah untuk blending pembangkit listrik. [PLACEHOLDER]',
                    'en' => 'Low-emission coal featuring minimal ash and sulfur content for power-generation blending programs. [PLACEHOLDER]',
                ],
                'description' => [
                    'id' => 'Tersedia untuk pengiriman kontrak jangka panjang maupun spot dengan pengapalan tongkang 300 kaki atau transshipment mother vessel. [PLACEHOLDER]',
                    'en' => 'Available under term or spot contracts via 300-ft barge delivery or mother-vessel transshipment anchorage. [PLACEHOLDER]',
                ],
                'specs' => [
                    ['code' => 'CV', 'label' => ['id' => 'Nilai Kalori (GAR)', 'en' => 'Calorific Value (GAR)'], 'value' => '3.800', 'unit' => 'kcal/kg', 'is_highlight' => true],
                    ['code' => 'TM', 'label' => ['id' => 'Total Moisture (ARB)', 'en' => 'Total Moisture (ARB)'], 'value' => '38,0 – 40,0', 'unit' => '%', 'is_highlight' => true],
                    ['code' => 'ASH', 'label' => ['id' => 'Kadar Abu / Ash (ADB)', 'en' => 'Ash Content (ADB)'], 'value' => '4,0 – 5,5', 'unit' => '%', 'is_highlight' => true],
                    ['code' => 'TS', 'label' => ['id' => 'Total Sulfur (ADB)', 'en' => 'Total Sulfur (ADB)'], 'value' => '≤ 0,30', 'unit' => '%', 'is_highlight' => false],
                ],
            ],
        ];

        foreach ($catalog as $index => $item) {
            $product = Product::query()->create([
                'company_id' => $company->id,
                'slug' => $item['slug'],
                'name' => $item['name'],
                'category_label' => $item['category_label'],
                'summary' => $item['summary'],
                'description' => $item['description'],
                'image_alt' => $item['name'],
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);

            foreach ($item['specs'] as $specIndex => $spec) {
                ProductSpec::query()->create([
                    'product_id' => $product->id,
                    'code' => $spec['code'],
                    'label' => $spec['label'],
                    'value' => $spec['value'],
                    'unit' => $spec['unit'],
                    'is_highlight' => $spec['is_highlight'],
                    'sort_order' => $specIndex + 1,
                ]);
            }
        }
    }

    private function seedHoldingPageBlocks(Company $holding): void
    {
        $pages = $holding->pages()->get()->keyBy(fn (Page $page): string => $page->key->value);

        if (isset($pages[PageKey::Home->value])) {
            $home = $pages[PageKey::Home->value];
            $home->blocks()->delete();

            $this->createBlock($home, 1, BlockType::Hero, [
                'eyebrow' => ['id' => 'GRUP PERTAMBANGAN & INFRASTRUKTUR ENERGI', 'en' => 'MINING & ENERGY INFRASTRUCTURE GROUP'],
                'title' => ['id' => 'PT Derco Bara Energy [PLACEHOLDER]', 'en' => 'PT Derco Bara Energy [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Induk usaha yang mengelola kegiatan penambangan batu bara, perdagangan komoditas energi, dan logistik pelabuhan melalui tiga entitas anak usaha di Indonesia. [PLACEHOLDER]',
                    'en' => 'Holding company overseeing coal mining operations, energy commodity trading, and port logistics across three operating subsidiaries in Indonesia. [PLACEHOLDER]',
                ],
                'primary_cta_label' => ['id' => 'Lihat Bisnis Kami', 'en' => 'Explore Our Business'],
                'primary_cta_href' => '/id/bisnis-kami',
            ]);

            $this->createBlock($home, 2, BlockType::SubsidiaryGrid, [
                'eyebrow' => ['id' => 'UNIT USAHA GRUP', 'en' => 'GROUP BUSINESS UNITS'],
                'title' => ['id' => 'Bisnis Kami [PLACEHOLDER]', 'en' => 'Our Business [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Entitas anak usaha dengan spesialisasi penambangan, perdagangan batu bara, dan operasional rantai pasok pelabuhan. [PLACEHOLDER]',
                    'en' => 'Operating subsidiaries specializing in coal extraction, trading, and port supply-chain operations. [PLACEHOLDER]',
                ],
                'layout_variant' => 'cards',
                'cta_label' => ['id' => 'Lihat Seluruh Unit Bisnis', 'en' => 'See All Business Units'],
                'cta_href' => '/id/bisnis-kami',
            ]);

            $this->createBlock($home, 3, BlockType::StackedImages, [
                'eyebrow' => ['id' => 'TENTANG KAMI', 'en' => 'WHO WE ARE'],
                'title' => ['id' => 'Tata kelola tambang dan rantai pasok yang terukur [PLACEHOLDER]', 'en' => 'Measured mining governance and supply chain execution [PLACEHOLDER]'],
                'lead' => [
                    'id' => 'Sejak tahun 2016, PT Derco Bara Energy menyatukan kendali mutu, kepatuhan izin, dan keandalan logistik di seluruh rantai pasok grup. [PLACEHOLDER]',
                    'en' => 'Since 2016, PT Derco Bara Energy has unified quality control, permit compliance, and logistics reliability across the group supply chain. [PLACEHOLDER]',
                ],
                'body' => [
                    'id' => 'Setiap pengapalan melalui verifikasi laboratorium independen dan pengawasan operasional dari tambang hingga titik muat tongkang guna memastikan kesesuaian kontrak pembeli B2B. [PLACEHOLDER]',
                    'en' => 'Every shipment undergoes independent laboratory verification and operational oversight from pit to barge loading terminal to uphold B2B contract specifications. [PLACEHOLDER]',
                ],
                'mirrored' => false,
                'cta_label' => ['id' => 'Pelajari Profil Grup', 'en' => 'Read Group Profile'],
                'cta_href' => '/id/tentang-kami',
            ]);

            $this->createBlock($home, 4, BlockType::ColorBand, [
                'title' => ['id' => 'Kepatuhan Izin & Standar Keselamatan Pertambangan [PLACEHOLDER]', 'en' => 'Permit Compliance & Mining Safety Standards [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Seluruh entitas grup beroperasi di bawah izin resmi (IUP-OP/IPP), penerapan SMKP, serta pemantauan lingkungan berkala. [PLACEHOLDER]',
                    'en' => 'All group entities operate under valid mining and trading licenses (IUP-OP/IPP), SMKP safety protocols, and periodic environmental monitoring. [PLACEHOLDER]',
                ],
                'icon_name' => 'award',
                'cta_label' => ['id' => 'Hubungi Tim Kepatuhan', 'en' => 'Contact Compliance Team'],
                'cta_href' => '/id/kontak',
            ]);

            $this->createBlock($home, 5, BlockType::MissionList, [
                'eyebrow' => ['id' => 'STRATEGI & ARAH GRUP', 'en' => 'GROUP STRATEGY & DIRECTION'],
                'title' => ['id' => 'Apa misi operasional kami? [PLACEHOLDER]', 'en' => 'What is our operational mission? [PLACEHOLDER]'],
                'vision' => [
                    'id' => 'Menjadi grup pertambangan dan logistik energi yang tepercaya dalam ketepatan spesifikasi, jadwal pengapalan, dan kepatuhan regulasi di Indonesia. [PLACEHOLDER]',
                    'en' => 'To be a trusted mining and energy logistics group recognized for specification accuracy, shipment punctuality, and regulatory compliance in Indonesia. [PLACEHOLDER]',
                ],
                'body' => [
                    'id' => 'Kami memprioritaskan kepastian pasokan jangka panjang melalui integrasi antara unit tambang, fasilitas pengolahan, dan pelabuhan muat. [PLACEHOLDER]',
                    'en' => 'We prioritize long-term supply certainty through tight integration between mining pits, crushing plants, and barge-loading terminals. [PLACEHOLDER]',
                ],
                'cta_label' => ['id' => 'Lihat Struktur Bisnis', 'en' => 'View Business Structure'],
                'cta_href' => '/id/bisnis-kami',
                'items' => [
                    [
                        'title' => ['id' => 'Kepastian mutu produk melalui uji sampel berlapis di stockpile dan jetty [PLACEHOLDER]', 'en' => 'Product quality assurance via multi-stage sampling at stockpile and jetty [PLACEHOLDER]'],
                        'description' => ['id' => 'Pengujian parameter kalori, kelembapan, abu, dan sulfur sebelum pemuatan. [PLACEHOLDER]', 'en' => 'Verification of calorific value, moisture, ash, and sulfur prior to barge loading. [PLACEHOLDER]'],
                        'icon_name' => 'check-circle-2',
                    ],
                    [
                        'title' => ['id' => 'Ketepatan jadwal pengapalan tongkang dan koordinasi pelabuhan 24 jam [PLACEHOLDER]', 'en' => 'Punctual barge loading schedules and 24-hour port coordination [PLACEHOLDER]'],
                        'description' => ['id' => 'Manajemen slot sandar dan kapasitas muat conveyor yang terukur. [PLACEHOLDER]', 'en' => 'Measured berth slot management and conveyor loading throughput. [PLACEHOLDER]'],
                        'icon_name' => 'check-circle-2',
                    ],
                    [
                        'title' => ['id' => 'Kepatuhan penuh terhadap RKAB, SMKP, dan pengelolaan lingkungan tambang [PLACEHOLDER]', 'en' => 'Strict adherence to RKAB quotas, SMKP safety, and environmental stewardship [PLACEHOLDER]'],
                        'description' => ['id' => 'Pelaporan rutin dan audit kepatuhan regulasi pertambangan nasional. [PLACEHOLDER]', 'en' => 'Routine reporting and compliance audits under national mining regulations. [PLACEHOLDER]'],
                        'icon_name' => 'check-circle-2',
                    ],
                ],
            ]);

            $this->createBlock($home, 6, BlockType::CtaBanner, [
                'eyebrow' => ['id' => 'HUBUNGI KANTOR PUSAT', 'en' => 'CONTACT HEAD OFFICE'],
                'title' => ['id' => 'Ada pertanyaan mengenai pasokan atau kemitraan? [PLACEHOLDER]', 'en' => 'Have questions regarding coal supply or partnership? [PLACEHOLDER]'],
                'subtitle' => ['id' => 'Tim komersial dan kepatuhan kami siap memberikan spesifikasi teknis serta dokumen verifikasi perusahaan. [PLACEHOLDER]', 'en' => 'Our commercial and compliance teams are ready to provide technical specifications and corporate verification documents. [PLACEHOLDER]'],
                'phone' => '+62 21 5088 7400',
                'cta_label' => ['id' => 'Kirim Pesan', 'en' => 'Send a Message'],
                'cta_href' => '/id/kontak',
            ]);
        }

        if (isset($pages[PageKey::About->value])) {
            $about = $pages[PageKey::About->value];
            $about->blocks()->delete();

            $this->createBlock($about, 1, BlockType::Hero, [
                'eyebrow' => ['id' => 'PROFIL HOLDING', 'en' => 'HOLDING PROFILE'],
                'title' => ['id' => 'Tentang PT Derco Bara Energy [PLACEHOLDER]', 'en' => 'About PT Derco Bara Energy [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Struktur induk usaha yang menyatukan kegiatan penambangan, perdagangan, dan logistik batu bara di bawah satu standar tata kelola korporat. [PLACEHOLDER]',
                    'en' => 'Corporate holding structure uniting coal mining, trading, and logistics operations under a single governance standard. [PLACEHOLDER]',
                ],
            ]);

            $this->createBlock($about, 2, BlockType::Timeline, [
                'eyebrow' => ['id' => 'REKAM JEJAK', 'en' => 'CORPORATE MILESTONES'],
                'title' => ['id' => 'Sejarah Perkembangan Grup [PLACEHOLDER]', 'en' => 'Group Development History [PLACEHOLDER]'],
                'orientation' => 'vertical',
                'items' => [
                    ['period' => '2016', 'title' => ['id' => 'Pendirian PT Derco Bara Energy [PLACEHOLDER]', 'en' => 'Establishment of PT Derco Bara Energy [PLACEHOLDER]'], 'description' => ['id' => 'Memulai kegiatan koordinasi perdagangan dan pasokan batu bara domestik di Jakarta. [PLACEHOLDER]', 'en' => 'Commenced domestic coal supply and trading coordination from Jakarta. [PLACEHOLDER]']],
                    ['period' => '2019', 'title' => ['id' => 'Pengembangan Infrastruktur Stockpile & Jetty [PLACEHOLDER]', 'en' => 'Stockpile & Jetty Infrastructure Expansion [PLACEHOLDER]'], 'description' => ['id' => 'Peningkatan kapasitas muat tongkang 300 kaki dan fasilitas crushing di Kalimantan Timur. [PLACEHOLDER]', 'en' => 'Upgraded 300-ft barge loading capacity and crushing facilities in East Kalimantan. [PLACEHOLDER]']],
                    ['period' => '2022', 'title' => ['id' => 'Konsolidasi Tiga Entitas Anak Usaha [PLACEHOLDER]', 'en' => 'Consolidation of Three Operating Subsidiaries [PLACEHOLDER]'], 'description' => ['id' => 'Penataan fokus operasional antara unit penambangan, perdagangan B2B, dan layanan logistik pelabuhan. [PLACEHOLDER]', 'en' => 'Structured dedicated operational mandates across mining, B2B trading, and port logistics services. [PLACEHOLDER]']],
                    ['period' => '2025', 'title' => ['id' => 'Standarisasi Mutu & Kepatuhan Terintegrasi [PLACEHOLDER]', 'en' => 'Integrated Quality & Compliance Standardization [PLACEHOLDER]'], 'description' => ['id' => 'Penerapan sistem manajemen mutu ISO 9001:2015 dan audit keselamatan kerja di seluruh lini grup. [PLACEHOLDER]', 'en' => 'Implemented ISO 9001:2015 quality management and occupational safety audits group-wide. [PLACEHOLDER]']],
                ],
            ]);

            $this->createBlock($about, 3, BlockType::ValuesGrid, [
                'eyebrow' => ['id' => 'PRINSIP KERJA', 'en' => 'OPERATING PRINCIPLES'],
                'title' => ['id' => 'Nilai Utama Perusahaan [PLACEHOLDER]', 'en' => 'Core Corporate Values [PLACEHOLDER]'],
                'items' => [
                    ['title' => ['id' => 'Akurasi Spesifikasi [PLACEHOLDER]', 'en' => 'Specification Accuracy [PLACEHOLDER]'], 'description' => ['id' => 'Setiap tonase yang dikirim mengacu pada parameter laboratorium terverifikasi. [PLACEHOLDER]', 'en' => 'Every shipped metric ton adheres to verified laboratory parameters. [PLACEHOLDER]'], 'icon_name' => 'shield-check'],
                    ['title' => ['id' => 'Disiplin Operasional [PLACEHOLDER]', 'en' => 'Operational Discipline [PLACEHOLDER]'], 'description' => ['id' => 'Pengelolaan jadwal hauling, crushing, dan pemuatan tongkang dengan prosedur baku. [PLACEHOLDER]', 'en' => 'Standardized procedures across hauling, crushing, and barge loading schedules. [PLACEHOLDER]'], 'icon_name' => 'clock'],
                    ['title' => ['id' => 'Kepatuhan Hukum & K3LH [PLACEHOLDER]', 'en' => 'Legal & HSE Compliance [PLACEHOLDER]'], 'description' => ['id' => 'Menempatkan izin resmi dan keselamatan kerja sebagai syarat mutlak operasi. [PLACEHOLDER]', 'en' => 'Treating valid licensing and workplace safety as non-negotiable operational prerequisites. [PLACEHOLDER]'], 'icon_name' => 'scale'],
                ],
            ]);
        }

        if (isset($pages[PageKey::Business->value])) {
            $business = $pages[PageKey::Business->value];
            $business->blocks()->delete();

            $this->createBlock($business, 1, BlockType::Hero, [
                'eyebrow' => ['id' => 'PORTOFOLIO ANAK USAHA', 'en' => 'SUBSIDIARY PORTFOLIO'],
                'title' => ['id' => 'Bisnis Kami [PLACEHOLDER]', 'en' => 'Our Business [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Tiga pilar operasional yang saling terhubung dari hulu tambang hingga pengapalan akhir kepada pelanggan industri. [PLACEHOLDER]',
                    'en' => 'Three interconnected operational pillars spanning upstream extraction through final shipment to industrial buyers. [PLACEHOLDER]',
                ],
            ]);

            $this->createBlock($business, 2, BlockType::SubsidiaryGrid, [
                'eyebrow' => ['id' => 'ENTITAS AKTIF', 'en' => 'ACTIVE ENTITIES'],
                'title' => ['id' => 'Unit Usaha di Bawah PT Derco Bara Energy [PLACEHOLDER]', 'en' => 'Operating Units Under PT Derco Bara Energy [PLACEHOLDER]'],
                'layout_variant' => 'alternating',
            ]);
        }

        if (isset($pages[PageKey::Contact->value])) {
            $contact = $pages[PageKey::Contact->value];
            $contact->blocks()->delete();

            $this->createBlock($contact, 1, BlockType::ContactInfo, [
                'eyebrow' => ['id' => 'JALUR KOMUNIKASI RESMI', 'en' => 'OFFICIAL COMMUNICATION CHANNELS'],
                'title' => ['id' => 'Hubungi PT Derco Bara Energy [PLACEHOLDER]', 'en' => 'Contact PT Derco Bara Energy [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Silakan hubungi kantor pusat kami di Jakarta atau kirimkan pertanyaan kemitraan dan permintaan spesifikasi melalui formulir di samping. [PLACEHOLDER]',
                    'en' => 'Reach our Jakarta head office or submit partnership and specification inquiries using the form alongside. [PLACEHOLDER]',
                ],
                'show_map' => true,
            ]);

            $this->createBlock($contact, 2, BlockType::ContactForm, [
                'eyebrow' => ['id' => 'FORMULIR PESAN B2B', 'en' => 'B2B INQUIRY FORM'],
                'title' => ['id' => 'Kirim Permintaan Informasi [PLACEHOLDER]', 'en' => 'Submit an Inquiry [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Tim kami akan menanggapi pesan Anda pada jam kerja operasional. [PLACEHOLDER]',
                    'en' => 'Our team will respond to your inquiry during regular operating hours. [PLACEHOLDER]',
                ],
                'submit_label' => ['id' => 'Kirim Pesan', 'en' => 'Send Message'],
            ]);
        }
    }

    private function seedSubsidiaryPageBlocks(Company $company): void
    {
        $pages = $company->pages()->get()->keyBy(fn (Page $page): string => $page->key->value);

        if (isset($pages[PageKey::Home->value])) {
            $home = $pages[PageKey::Home->value];
            $home->blocks()->delete();

            $this->createBlock($home, 1, BlockType::Hero, [
                'eyebrow' => ['id' => 'ENTITAS ANAK USAHA DERCO GROUP', 'en' => 'DERCO GROUP OPERATING SUBSIDIARY'],
                'title' => ['id' => "{$company->name} [PLACEHOLDER]", 'en' => "{$company->name} [PLACEHOLDER]"],
                'description' => [
                    'id' => $company->translateField('tagline', 'id'),
                    'en' => $company->translateField('tagline', 'en'),
                ],
                'primary_cta_label' => ['id' => 'Lihat Spesifikasi Produk', 'en' => 'View Product Specifications'],
                'primary_cta_href' => 'produk',
                'secondary_cta_label' => ['id' => 'Hubungi Tim Komersial', 'en' => 'Contact Commercial Team'],
                'secondary_cta_href' => 'kontak',
            ]);

            $this->createBlock($home, 2, BlockType::Stats, [
                'eyebrow' => ['id' => 'SEKILAS ANGKA OPERASIONAL', 'en' => 'OPERATIONAL FIGURES AT A GLANCE'],
                'title' => ['id' => 'Kapasitas dan Kinerja Terukur [PLACEHOLDER]', 'en' => 'Measured Capacity and Performance [PLACEHOLDER]'],
            ]);

            $this->createBlock($home, 3, BlockType::ProductList, [
                'eyebrow' => ['id' => 'KATALOG SPESIFIKASI', 'en' => 'SPECIFICATION CATALOG'],
                'title' => ['id' => 'Produk & Layanan Utama [PLACEHOLDER]', 'en' => 'Primary Products & Services [PLACEHOLDER]'],
                'limit' => 3,
                'show_download_button' => true,
            ]);

            $this->createBlock($home, 4, BlockType::CtaBanner, [
                'eyebrow' => ['id' => 'KONSULTASI PASOKAN', 'en' => 'SUPPLY CONSULTATION'],
                'title' => ['id' => 'Diskusikan Kebutuhan Kontrak Anda [PLACEHOLDER]', 'en' => 'Discuss Your Contract Requirements [PLACEHOLDER]'],
                'phone' => '+62 21 5088 7400',
                'cta_label' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'],
                'cta_href' => 'kontak',
            ]);
        }

        if (isset($pages[PageKey::About->value])) {
            $about = $pages[PageKey::About->value];
            $about->blocks()->delete();

            $this->createBlock($about, 1, BlockType::SplitContent, [
                'eyebrow' => ['id' => 'PROFIL ENTITAS', 'en' => 'ENTITY PROFILE'],
                'title' => ['id' => "Tentang {$company->name} [PLACEHOLDER]", 'en' => "About {$company->name} [PLACEHOLDER]"],
                'lead' => [
                    'id' => $company->translateField('tagline', 'id'),
                    'en' => $company->translateField('tagline', 'en'),
                ],
                'body' => [
                    'id' => $company->translateField('about_short', 'id'),
                    'en' => $company->translateField('about_short', 'en'),
                ],
                'image_position' => 'right',
            ]);

            $this->createBlock($about, 2, BlockType::Timeline, [
                'eyebrow' => ['id' => 'TAHAPAN OPERASIONAL', 'en' => 'OPERATIONAL MILESTONES'],
                'title' => ['id' => 'Perkembangan & Kapabilitas [PLACEHOLDER]', 'en' => 'Growth & Capabilities [PLACEHOLDER]'],
                'orientation' => 'horizontal',
                'items' => [
                    ['period' => '01', 'title' => ['id' => 'Pembentukan Tim & Izin Operasi [PLACEHOLDER]', 'en' => 'Team Formation & Operational Licensing [PLACEHOLDER]'], 'description' => ['id' => 'Penyelesaian izin usaha dan perjanjian kerja sama infrastruktur pelabuhan. [PLACEHOLDER]', 'en' => 'Completion of business permits and port infrastructure agreements. [PLACEHOLDER]']],
                    ['period' => '02', 'title' => ['id' => 'Pengapalan Komersial Perdana [PLACEHOLDER]', 'en' => 'First Commercial Shipment [PLACEHOLDER]'], 'description' => ['id' => 'Pelaksanaan kontrak pasokan perdana untuk industri domestik dengan verifikasi surveyor independen. [PLACEHOLDER]', 'en' => 'Execution of inaugural domestic industrial supply contract verified by independent surveyors. [PLACEHOLDER]']],
                    ['period' => '03', 'title' => ['id' => 'Ekspansi Kapasitas Rantai Pasok [PLACEHOLDER]', 'en' => 'Supply Chain Capacity Expansion [PLACEHOLDER]'], 'description' => ['id' => 'Penambahan armada angkut dan peningkatan kapasitas stockpile terkelola. [PLACEHOLDER]', 'en' => 'Expansion of hauling fleet and managed stockpile capacity. [PLACEHOLDER]']],
                ],
            ]);

            $this->createBlock($about, 3, BlockType::ValuesGrid, [
                'eyebrow' => ['id' => 'STANDAR KERJA', 'en' => 'OPERATING STANDARDS'],
                'title' => ['id' => 'Prinsip Pelaksanaan Operasional [PLACEHOLDER]', 'en' => 'Operational Execution Principles [PLACEHOLDER]'],
                'items' => [
                    ['title' => ['id' => 'Kontrol Kualitas Berlapis [PLACEHOLDER]', 'en' => 'Multi-Stage Quality Control [PLACEHOLDER]'], 'description' => ['id' => 'Pengambilan sampel berkala dari pit hingga pemuatan tongkang. [PLACEHOLDER]', 'en' => 'Periodic sampling from pit face through barge loading. [PLACEHOLDER]'], 'icon_name' => 'check-circle-2'],
                    ['title' => ['id' => 'Transparansi Dokumen [PLACEHOLDER]', 'en' => 'Document Transparency [PLACEHOLDER]'], 'description' => ['id' => 'Setiap pengiriman dilengkapi LHV, SKAB, dan COA resmi. [PLACEHOLDER]', 'en' => 'Every shipment is accompanied by official LHV, SKAB, and COA records. [PLACEHOLDER]'], 'icon_name' => 'file-check'],
                    ['title' => ['id' => 'Keselamatan Kerja (K3) [PLACEHOLDER]', 'en' => 'Occupational Safety (HSE) [PLACEHOLDER]'], 'description' => ['id' => 'Penerapan prosedur keselamatan operasional tanpa kompromi di seluruh fasilitas. [PLACEHOLDER]', 'en' => 'Uncompromising safety procedures enforced across all operational facilities. [PLACEHOLDER]'], 'icon_name' => 'shield'],
                ],
            ]);
        }

        if (isset($pages[PageKey::Products->value])) {
            $products = $pages[PageKey::Products->value];
            $products->blocks()->delete();

            $this->createBlock($products, 1, BlockType::ProductList, [
                'eyebrow' => ['id' => 'SPESIFIKASI TEKNIS', 'en' => 'TECHNICAL SPECIFICATIONS'],
                'title' => ['id' => "Katalog Produk {$company->name} [PLACEHOLDER]", 'en' => "{$company->name} Product Catalog [PLACEHOLDER]"],
                'description' => [
                    'id' => 'Parameter spesifikasi di bawah ini merujuk pada standar kontrak pengapalan dan diverifikasi oleh surveyor independen pada titik muat. [PLACEHOLDER]',
                    'en' => 'The specification parameters below reflect standard shipment contract grades verified by independent surveyors at loading ports. [PLACEHOLDER]',
                ],
                'limit' => 0,
                'show_download_button' => true,
            ]);
        }

        if (isset($pages[PageKey::Operations->value])) {
            $operations = $pages[PageKey::Operations->value];
            $operations->blocks()->delete();

            $this->createBlock($operations, 1, BlockType::OperationList, [
                'eyebrow' => ['id' => 'INFRASTRUKTUR LAPANGAN', 'en' => 'FIELD INFRASTRUCTURE'],
                'title' => ['id' => "Jaringan Operasi {$company->name} [PLACEHOLDER]", 'en' => "{$company->name} Operational Network [PLACEHOLDER]"],
                'description' => [
                    'id' => 'Rantai operasional terpadu mulai dari area penambangan, fasilitas pengolahan, stockpile, hingga pelabuhan muat tongkang. [PLACEHOLDER]',
                    'en' => 'Integrated operational chain spanning mining pits, crushing plants, stockpiles, and barge-loading terminals. [PLACEHOLDER]',
                ],
                'show_map' => true,
            ]);
        }

        if (isset($pages[PageKey::Compliance->value])) {
            $compliance = $pages[PageKey::Compliance->value];
            $compliance->blocks()->delete();

            $this->createBlock($compliance, 1, BlockType::CertificationList, [
                'eyebrow' => ['id' => 'VERIFIKASI LEGALITAS & MUTU', 'en' => 'LEGALITY & QUALITY VERIFICATION'],
                'title' => ['id' => 'Mutu, Izin, dan Kepatuhan Regulasi [PLACEHOLDER]', 'en' => 'Quality, Permits, and Regulatory Compliance [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Daftar dokumen izin usaha, sertifikat manajemen mutu, kebijakan K3LH, dan laporan pemantauan yang dapat diunduh oleh calon mitra maupun auditor. [PLACEHOLDER]',
                    'en' => 'Directory of business licenses, quality management certificates, HSE policies, and monitoring reports available for download by prospective partners and auditors. [PLACEHOLDER]',
                ],
                'group_by_category' => true,
            ]);
        }

        if (isset($pages[PageKey::Contact->value])) {
            $contact = $pages[PageKey::Contact->value];
            $contact->blocks()->delete();

            $this->createBlock($contact, 1, BlockType::ContactInfo, [
                'eyebrow' => ['id' => 'HUBUNGI TIM KAMI', 'en' => 'REACH OUR TEAM'],
                'title' => ['id' => "Kontak {$company->name} [PLACEHOLDER]", 'en' => "Contact {$company->name} [PLACEHOLDER]"],
                'description' => [
                    'id' => 'Hubungi kantor pusat atau tim operasional kami untuk permintaan penawaran spesifikasi dan jadwal pengapalan. [PLACEHOLDER]',
                    'en' => 'Contact our head office or operations team for specification quotations and shipment scheduling. [PLACEHOLDER]',
                ],
                'show_map' => true,
            ]);

            $this->createBlock($contact, 2, BlockType::ContactForm, [
                'eyebrow' => ['id' => 'FORMULIR KONTAK', 'en' => 'CONTACT FORM'],
                'title' => ['id' => 'Kirim Pesan [PLACEHOLDER]', 'en' => 'Send a Message [PLACEHOLDER]'],
                'submit_label' => ['id' => 'Kirim Pesan', 'en' => 'Send Message'],
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function createBlock(Page $page, int $sortOrder, BlockType $type, array $data): void
    {
        PageBlock::query()->create([
            'page_id' => $page->id,
            'type' => $type,
            'sort_order' => $sortOrder,
            'is_visible' => true,
            'data' => $data,
        ]);
    }
}
