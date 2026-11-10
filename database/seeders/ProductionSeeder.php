<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Company\Actions\CreateCompanyPages;
use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;
use App\Domain\Contact\Enums\ContactType;
use App\Domain\Contact\Models\CompanyContact;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ProductionSeeder extends Seeder
{
    /**
     * Run the minimal production database seeds.
     * Seeds super admin, basic settings, company structure (holding + 3 subsidiaries),
     * and the canonical pages WITHOUT placeholder copy.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seedRolesAndSuperAdmin();
        $this->seedSettings();

        $holding = Company::query()->updateOrCreate(
            ['slug' => 'derco-bara-energy'],
            [
                'parent_id' => null,
                'name' => 'PT Derco Bara Energy',
                'theme' => CompanyTheme::Holding,
                'status' => CompanyStatus::Published,
                'sort_order' => 1,
                'tagline' => [
                    'id' => 'Induk usaha pertambangan batu bara, logistik pelabuhan, dan layanan energi terpadu di Indonesia.',
                    'en' => 'Holding company for coal mining, port logistics, and integrated energy services in Indonesia.',
                ],
                'about_short' => [
                    'id' => 'PT Derco Bara Energy mengoordinasikan kegiatan operasional pertambangan, perdagangan, dan logistik maritim melalui tiga entitas anak usaha dengan standar kepatuhan terintegrasi.',
                    'en' => 'PT Derco Bara Energy coordinates mining operations, trading, and maritime logistics through three operating subsidiaries under unified compliance governance.',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Derco Bara Energy',
                    'en' => 'PT Derco Bara Energy Logo',
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
                    'id' => 'Perdagangan batu bara termal dan manajemen pengapalan untuk pembangkit dan industri B2B.',
                    'en' => 'Thermal coal trading and shipment management for power utilities and industrial buyers.',
                ],
                'about_short' => [
                    'id' => 'PT Korin Berkat Energy melayani kontrak pasokan batu bara dengan kepastian jadwal tongkang, verifikasi mutu independen, dan dokumentasi lengkap.',
                    'en' => 'PT Korin Berkat Energy fulfills coal supply agreements with reliable barge scheduling, independent quality assurance, and complete shipping documentation.',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Korin Berkat Energy',
                    'en' => 'PT Korin Berkat Energy Logo',
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
                    'id' => 'Dukungan logistik maritim, pengelolaan fasilitas pelabuhan, dan layanan operasional terpadu.',
                    'en' => 'Maritime logistics support, port facility management, and integrated operational services.',
                ],
                'about_short' => [
                    'id' => 'PT Beramal Sukses Sejahtera mengoperasikan fasilitas pendukung rantai pasok, koordinasi sandar tongkang, dan layanan teknis lapangan.',
                    'en' => 'PT Beramal Sukses Sejahtera operates supply-chain support facilities, barge berthing coordination, and field technical services.',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Beramal Sukses Sejahtera',
                    'en' => 'PT Beramal Sukses Sejahtera Logo',
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
                    'id' => 'Operasi penambangan terbuka, pengangkutan darat, dan pengolahan batu bara di konsesi tambang.',
                    'en' => 'Open-pit coal extraction, overland hauling, and crushing operations at concession sites.',
                ],
                'about_short' => [
                    'id' => 'PT Korin Bara Energy mengelola kegiatan penambangan terbuka, jalan angkut khusus, dan program reklamasi lingkungan berkala.',
                    'en' => 'PT Korin Bara Energy manages open-pit extraction, dedicated haul roads, and progressive environmental reclamation.',
                ],
                'logo_alt' => [
                    'id' => 'Logo PT Korin Bara Energy',
                    'en' => 'PT Korin Bara Energy Logo',
                ],
            ],
        );

        $createPages = app(CreateCompanyPages::class);

        foreach ([$holding, $korinBerkat, $beramal, $korinBara] as $company) {
            $createPages->execute($company);
            $this->seedProductionCompanyContacts($company);
            $this->seedProductionPageBlocks($company);
        }
    }

    private function seedRolesAndSuperAdmin(): void
    {
        $superAdminRole = Role::findOrCreate(User::ROLE_SUPER_ADMIN, 'web');
        Role::findOrCreate(User::ROLE_EDITOR, 'web');

        $adminEmail = env('ADMIN_EMAIL', 'admin@dercobara.co.id');
        $adminPassword = env('ADMIN_PASSWORD', 'DercoAdmin2026!');
        $adminName = env('ADMIN_NAME', 'Super Administrator');

        $admin = User::query()->updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminName,
                'password' => $adminPassword,
                'email_verified_at' => now(),
            ],
        );
        $admin->syncRoles([$superAdminRole]);
    }

    private function seedSettings(): void
    {
        Setting::setValue('seo_default_title', [
            'id' => 'PT Derco Bara Energy — Grup Pertambangan & Infrastruktur Energi',
            'en' => 'PT Derco Bara Energy — Mining & Energy Infrastructure Group',
        ], 'seo');

        Setting::setValue('seo_default_description', [
            'id' => 'Profil resmi PT Derco Bara Energy dan kelompok usaha pertambangan, perdagangan, dan logistik energi di Indonesia.',
            'en' => 'Official corporate profile of PT Derco Bara Energy and its coal mining, trading, and energy logistics subsidiaries in Indonesia.',
        ], 'seo');

        Setting::setValue('contact_default_email', [
            'email' => env('ADMIN_EMAIL', 'corsec@dercobara.co.id'),
        ], 'contact');

        Setting::setValue('analytics_code', [
            'id' => '',
        ], 'analytics');
    }

    private function seedProductionCompanyContacts(Company $company): void
    {
        CompanyContact::query()->where('company_id', $company->id)->delete();

        CompanyContact::query()->create([
            'company_id' => $company->id,
            'type' => ContactType::HeadOffice,
            'label' => [
                'id' => "Kantor Pusat {$company->name}",
                'en' => "{$company->name} Head Office",
            ],
            'address' => [
                'id' => 'Gedung Office 8 Lantai 18, Kawasan SCBD Lot 28, Jl. Jend. Sudirman Kav. 52–53, Jakarta Selatan 12190',
                'en' => 'Office 8 Building 18th Floor, SCBD Lot 28, Jl. Jend. Sudirman Kav. 52–53, South Jakarta 12190',
            ],
            'phone' => '+62 21 5088 7400',
            'email' => "info@{$company->slug}.co.id",
            'notify_email' => "corsec@{$company->slug}.co.id",
            'whatsapp' => '+62 811 8807 400',
            'operating_hours' => [
                'id' => 'Senin – Jumat: 08.30 – 17.30 WIB',
                'en' => 'Monday – Friday: 08:30 – 17:30 WIB (UTC+7)',
            ],
            'socials' => [
                'linkedin' => "https://www.linkedin.com/company/{$company->slug}",
            ],
            'latitude' => -6.2286000,
            'longitude' => 106.8075000,
        ]);
    }

    private function seedProductionPageBlocks(Company $company): void
    {
        $contactPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Contact->value)
            ->first();

        if ($contactPage) {
            PageBlock::query()->where('page_id', $contactPage->id)->delete();

            PageBlock::query()->create([
                'page_id' => $contactPage->id,
                'type' => BlockType::ContactInfo,
                'sort_order' => 1,
                'is_visible' => true,
                'data' => [
                    'eyebrow' => [
                        'id' => 'JALUR KOMUNIKASI RESMI',
                        'en' => 'OFFICIAL COMMUNICATION CHANNELS',
                    ],
                    'title' => [
                        'id' => "Hubungi {$company->name}",
                        'en' => "Contact {$company->name}",
                    ],
                    'description' => [
                        'id' => 'Silakan hubungi kantor pusat kami atau kirimkan pertanyaan kemitraan melalui formulir di samping.',
                        'en' => 'Reach our office directly or submit partnership inquiries using the contact form.',
                    ],
                    'show_map' => true,
                ],
            ]);

            PageBlock::query()->create([
                'page_id' => $contactPage->id,
                'type' => BlockType::ContactForm,
                'sort_order' => 2,
                'is_visible' => true,
                'data' => [
                    'eyebrow' => [
                        'id' => 'FORMULIR PESAN B2B',
                        'en' => 'B2B INQUIRY FORM',
                    ],
                    'title' => [
                        'id' => 'Kirim Permintaan Informasi',
                        'en' => 'Submit an Inquiry',
                    ],
                    'description' => [
                        'id' => 'Tim kami akan merespons pesan Anda dalam 1x24 jam kerja.',
                        'en' => 'Our team will respond within 1 business day.',
                    ],
                    'submit_label' => [
                        'id' => 'Kirim Pesan',
                        'en' => 'Send Message',
                    ],
                ],
            ]);
        }

        // For Holding Home, About, and Business, ensure Hero blocks with slides exist
        if ($company->isHolding()) {
            $homePage = Page::query()
                ->where('company_id', $company->id)
                ->where('key', PageKey::Home->value)
                ->first();

            if ($homePage && ! PageBlock::query()->where('page_id', $homePage->id)->where('type', BlockType::Hero)->exists()) {
                PageBlock::query()->create([
                    'page_id' => $homePage->id,
                    'type' => BlockType::Hero,
                    'sort_order' => 1,
                    'is_visible' => true,
                    'data' => [
                        'eyebrow' => [
                            'id' => 'PT DERCO BARA ENERGY',
                            'en' => 'PT DERCO BARA ENERGY',
                        ],
                        'title' => [
                            'id' => 'Energi & Logistik Pertambangan Terintegrasi',
                            'en' => 'Integrated Mining Energy & Logistics',
                        ],
                        'description' => [
                            'id' => 'Mengoordinasikan operasi penambangan, perdagangan batu bara, dan logistik maritim melalui sinergi entitas anak usaha terpercaya.',
                            'en' => 'Coordinating mining operations, coal trading, and maritime logistics through trusted subsidiary synergy.',
                        ],
                        'primary_cta_label' => [
                            'id' => 'Lihat Bisnis Kami',
                            'en' => 'Explore Our Business',
                        ],
                        'primary_cta_href' => 'page:business',
                    ],
                ]);
            }

            $aboutPage = Page::query()
                ->where('company_id', $company->id)
                ->where('key', PageKey::About->value)
                ->first();

            if ($aboutPage && ! PageBlock::query()->where('page_id', $aboutPage->id)->where('type', BlockType::Hero)->exists()) {
                PageBlock::query()->create([
                    'page_id' => $aboutPage->id,
                    'type' => BlockType::Hero,
                    'sort_order' => 1,
                    'is_visible' => true,
                    'data' => [
                        'eyebrow' => [
                            'id' => 'PROFIL HOLDING',
                            'en' => 'HOLDING PROFILE',
                        ],
                        'title' => [
                            'id' => 'Tentang PT Derco Bara Energy',
                            'en' => 'About PT Derco Bara Energy',
                        ],
                        'description' => [
                            'id' => 'Struktur induk usaha yang menyatukan kegiatan penambangan, perdagangan, dan logistik batu bara di bawah satu standar tata kelola korporat.',
                            'en' => 'Corporate holding structure uniting coal mining, trading, and logistics operations under a single corporate governance standard.',
                        ],
                        'slides' => [
                            [
                                'image' => '/images/placeholders/pit-benches-1280.webp',
                                'image_alt' => [
                                    'id' => 'Teras konsesi tambang terbuka batu bara PT Derco Bara Energy',
                                    'en' => 'Open-pit coal concession benches at PT Derco Bara Energy',
                                ],
                            ],
                            [
                                'image' => '/images/placeholders/conveyor-loading-1280.webp',
                                'image_alt' => [
                                    'id' => 'Instalasi pemuatan conveyor batu bara PT Derco Bara Energy',
                                    'en' => 'Conveyor coal loading installation at PT Derco Bara Energy',
                                ],
                            ],
                            [
                                'image' => '/images/placeholders/barge-aerial-1280.webp',
                                'image_alt' => [
                                    'id' => 'Armada tongkang pengangkutan batu bara di perairan sungai',
                                    'en' => 'River barge fleet transport operations for coal logistics',
                                ],
                            ],
                        ],
                    ],
                ]);
            }

            $businessPage = Page::query()
                ->where('company_id', $company->id)
                ->where('key', PageKey::Business->value)
                ->first();

            if ($businessPage && ! PageBlock::query()->where('page_id', $businessPage->id)->where('type', BlockType::Hero)->exists()) {
                PageBlock::query()->create([
                    'page_id' => $businessPage->id,
                    'type' => BlockType::Hero,
                    'sort_order' => 1,
                    'is_visible' => true,
                    'data' => [
                        'eyebrow' => [
                            'id' => 'PORTOFOLIO BISNIS',
                            'en' => 'BUSINESS PORTFOLIO',
                        ],
                        'title' => [
                            'id' => 'Bisnis & Operasi Terintegrasi',
                            'en' => 'Integrated Business & Operations',
                        ],
                        'description' => [
                            'id' => 'Sinergi tiga entitas anak usaha dalam rantai pasok batu bara nasional dari tambang hingga titik serah.',
                            'en' => 'Synergy of three subsidiary entities across the coal supply chain from mine mouth to point of delivery.',
                        ],
                        'slides' => [
                            [
                                'image' => '/images/placeholders/excavator-pit-1280.webp',
                                'image_alt' => [
                                    'id' => 'Operasi alat berat penambangan batu bara',
                                    'en' => 'Heavy equipment mining operations',
                                ],
                            ],
                            [
                                'image' => '/images/placeholders/barge-aerial-1280.webp',
                                'image_alt' => [
                                    'id' => 'Logistik maritim dan pengapalan tongkang',
                                    'en' => 'Maritime logistics and barge shipping',
                                ],
                            ],
                            [
                                'image' => '/images/placeholders/coal-stockpile-1280.webp',
                                'image_alt' => [
                                    'id' => 'Manajemen persediaan batu bara di stockpile pelabuhan',
                                    'en' => 'Coal stockpile inventory management at port terminal',
                                ],
                            ],
                        ],
                    ],
                ]);
            }
        }
    }
}
