<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;

class SyncSubsidiaryPagesAction
{
    /**
     * Synchronize subsidiary page structures and blocks according to Revision 2.
     * Idempotent and safe to run multiple times.
     */
    public function execute(): void
    {
        $this->syncKorinBerkat();
        $this->syncBeramal();
        $this->syncKorinBara();
    }

    private function syncKorinBerkat(): void
    {
        $company = Company::query()->where('slug', 'korin-berkat-energy')->first();
        if (! $company) {
            return;
        }

        // 1. Delete compliance page and its blocks for Korin Berkat
        $compliancePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Compliance->value)
            ->first();

        if ($compliancePage) {
            $compliancePage->blocks()->delete();
            $compliancePage->delete();
        }

        // 2. Remove CertificationList (Kepatuhan & Mutu) from Beranda
        $homePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Home->value)
            ->first();

        if ($homePage) {
            $homePage->blocks()->where('type', BlockType::CertificationList)->delete();
        }

        // 3. Remove Timeline (Tahapan Operasional) from Tentang Kami
        $aboutPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::About->value)
            ->first();

        if ($aboutPage) {
            $aboutPage->blocks()->where('type', BlockType::Timeline)->delete();

            // 4. Update ValuesGrid block from "Standar Kerja" to "Nilai Perusahaan"
            $valuesBlock = $aboutPage->blocks()->where('type', BlockType::ValuesGrid)->first();
            if ($valuesBlock) {
                $valuesBlock->update([
                    'data' => [
                        'eyebrow' => ['id' => 'PRINSIP KORPORASI', 'en' => 'CORPORATE PRINCIPLES'],
                        'title' => ['id' => 'Nilai Perusahaan [PLACEHOLDER]', 'en' => 'Our Values [PLACEHOLDER]'],
                        'description' => [
                            'id' => 'Prinsip tata kelola komersial dan operasional yang mendasari setiap kontrak pasokan batu bara kami. [PLACEHOLDER]',
                            'en' => 'Commercial and operational governance principles underpinning each of our coal supply agreements. [PLACEHOLDER]',
                        ],
                        'items' => [
                            [
                                'title' => ['id' => 'Integritas Pasokan [PLACEHOLDER]', 'en' => 'Supply Integrity [PLACEHOLDER]'],
                                'description' => [
                                    'id' => 'Verifikasi berkala parameter kalori dan spesifikasi teknis pada setiap titik serah terima kargo bersama surveyor independen. [PLACEHOLDER]',
                                    'en' => 'Periodic verification of calorific parameters and technical specifications at each cargo handover point with independent surveyors. [PLACEHOLDER]',
                                ],
                            ],
                            [
                                'title' => ['id' => 'Ketepatan Spesifikasi [PLACEHOLDER]', 'en' => 'Specification Precision [PLACEHOLDER]'],
                                'description' => [
                                    'id' => 'Pengawasan ketat kesesuaian nilai kalori, kadar abu, dan kelembapan sesuai kontrak perjanjian jual beli. [PLACEHOLDER]',
                                    'en' => 'Strict oversight of calorific value, ash content, and moisture in compliance with purchase agreement terms. [PLACEHOLDER]',
                                ],
                            ],
                            [
                                'title' => ['id' => 'Keandalan Pengapalan [PLACEHOLDER]', 'en' => 'Shipment Reliability [PLACEHOLDER]'],
                                'description' => [
                                    'id' => 'Pengelolaan jadwal tongkang dan slot sandar jetty secara terukur untuk meminimalkan waktu tunggu dan demurrage. [PLACEHOLDER]',
                                    'en' => 'Measured management of barge laycan schedules and jetty berthing slots to minimize turnaround and demurrage. [PLACEHOLDER]',
                                ],
                            ],
                            [
                                'title' => ['id' => 'Kepatuhan Berkelanjutan [PLACEHOLDER]', 'en' => 'Sustainable Compliance [PLACEHOLDER]'],
                                'description' => [
                                    'id' => 'Pemenuhan penuh legalitas perizinan pertambangan, dokumen angkut resmi, dan kepatuhan standar K3LH berkelanjutan. [PLACEHOLDER]',
                                    'en' => 'Full fulfillment of mining permits, formal shipping manifests, and sustainable occupational HSE standards. [PLACEHOLDER]',
                                ],
                            ],
                        ],
                    ],
                ]);
            }
        }
    }

    private function syncBeramal(): void
    {
        $company = Company::query()->where('slug', 'beramal-sukses-sejahtera')->first();
        if (! $company) {
            return;
        }

        // 1. Delete Compliance page (Section 0 Item 9)
        $compliancePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Compliance->value)
            ->first();

        if ($compliancePage) {
            $compliancePage->blocks()->delete();
            $compliancePage->delete();
        }

        // 2. Update Contact page title & slug to "Hubungi Kami" / "hubungi-kami"
        $contactPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Contact->value)
            ->first();

        if ($contactPage) {
            $contactPage->update([
                'title' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'],
                'slug' => ['id' => 'hubungi-kami', 'en' => 'contact-us'],
                'meta_title' => [
                    'id' => "Hubungi Kami — {$company->name}",
                    'en' => "Contact Us — {$company->name}",
                ],
            ]);

            $contactInfoBlock = $contactPage->blocks()->where('type', BlockType::ContactInfo)->first();
            if ($contactInfoBlock) {
                $blockData = $contactInfoBlock->data;
                $blockData['title'] = [
                    'id' => "Hubungi {$company->name} [PLACEHOLDER]",
                    'en' => "Contact {$company->name} [PLACEHOLDER]",
                ];
                $contactInfoBlock->update(['data' => $blockData]);
            }
        }

        // 3. Remove CertificationList and OperationList (Jejak Infrastruktur) from Beranda
        $homePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Home->value)
            ->first();

        if ($homePage) {
            $homePage->blocks()->whereIn('type', [
                BlockType::CertificationList,
                BlockType::OperationList,
            ])->delete();
        }

        // 4. Remove Timeline (Tahapan Operasional) from Tentang Kami
        $aboutPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::About->value)
            ->first();

        if ($aboutPage) {
            $aboutPage->blocks()->where('type', BlockType::Timeline)->delete();
        }
    }

    private function syncKorinBara(): void
    {
        $company = Company::query()->where('slug', 'korin-bara-energy')->first();
        if (! $company) {
            return;
        }

        // 1. Delete Compliance page (Section 0 Item 9)
        $compliancePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Compliance->value)
            ->first();

        if ($compliancePage) {
            $compliancePage->blocks()->delete();
            $compliancePage->delete();
        }

        // 2. Remove CertificationList from Beranda if present
        $homePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Home->value)
            ->first();

        if ($homePage) {
            $homePage->blocks()->where('type', BlockType::CertificationList)->delete();
        }

        // 3. Reorder pages per client reference: Beranda (1), Tentang Kami (2), Operasi (3), Produk (4), Kontak (5)
        $pageOrders = [
            PageKey::Home->value => 1,
            PageKey::About->value => 2,
            PageKey::Operations->value => 3,
            PageKey::Products->value => 4,
            PageKey::Contact->value => 5,
        ];

        foreach ($pageOrders as $key => $order) {
            Page::query()
                ->where('company_id', $company->id)
                ->where('key', $key)
                ->update(['sort_order' => $order]);
        }

        // 4. Update Header block with CTA button
        $headerBlock = PageBlock::query()
            ->where('company_id', $company->id)
            ->where('section_key', 'header')
            ->first();

        if ($headerBlock) {
            $headerData = $headerBlock->data ?? [];
            $headerData['cta_label'] = ['id' => 'Hubungi Kami', 'en' => 'Contact Us'];
            $headerData['cta_href'] = '/kontak';
            $headerBlock->update(['data' => $headerData]);
        }

        // 5. Update Home sections & seed reference blocks
        if ($homePage) {
            // Update Hero to match Korin Berkat content
            $heroBlock = $homePage->blocks()->where('type', BlockType::Hero)->first();
            if ($heroBlock) {
                $heroData = $heroBlock->data ?? [];
                $heroData['eyebrow'] = ['id' => 'ENTITAS ANAK USAHA DERCO GROUP', 'en' => 'DERCO GROUP OPERATING SUBSIDIARY'];
                $heroData['title'] = ['id' => 'PT Korin Bara Energy [PLACEHOLDER]', 'en' => 'PT Korin Bara Energy [PLACEHOLDER]'];
                $heroData['title_emphasis'] = ['id' => '', 'en' => ''];
                $heroData['description'] = [
                    'id' => 'Perdagangan batu bara termal dan manajemen pengapalan terukur untuk pembangkit dan industri B2B. [PLACEHOLDER]',
                    'en' => 'Thermal coal trading and measured shipment management for power utilities and industrial buyers. [PLACEHOLDER]',
                ];
                $heroData['primary_cta_label'] = ['id' => 'Lihat Spesifikasi Produk', 'en' => 'View Product Specifications'];
                $heroData['primary_cta_href'] = '/produk';
                $heroData['secondary_cta_label'] = ['id' => 'Hubungi Tim Komersial', 'en' => 'Contact Commercial Team'];
                $heroData['secondary_cta_href'] = '/kontak';
                $heroBlock->update(['data' => $heroData, 'is_visible' => true]);
            }

            // Ensure Values Strip (home_values) matches Korin Berkat values
            $valuesStrip = $homePage->blocks()->where('section_key', 'home_values')->first();
            $valuesData = [
                'eyebrow' => ['id' => 'PRINSIP KORPORASI', 'en' => 'CORPORATE PRINCIPLES'],
                'title' => ['id' => 'Nilai Perusahaan [PLACEHOLDER]', 'en' => 'Our Values [PLACEHOLDER]'],
                'description' => [
                    'id' => 'Prinsip tata kelola komersial dan operasional yang mendasari setiap kontrak pasokan batu bara kami. [PLACEHOLDER]',
                    'en' => 'Commercial and operational governance principles underpinning each of our coal supply agreements. [PLACEHOLDER]',
                ],
                'items' => [
                    [
                        'icon_name' => 'shield-check',
                        'title' => ['id' => 'Integritas Pasokan [PLACEHOLDER]', 'en' => 'Supply Integrity [PLACEHOLDER]'],
                        'description' => [
                            'id' => 'Verifikasi berkala parameter kalori dan spesifikasi teknis pada setiap titik serah terima kargo bersama surveyor independen. [PLACEHOLDER]',
                            'en' => 'Periodic verification of calorific parameters and technical specifications at each cargo handover point with independent surveyors. [PLACEHOLDER]',
                        ],
                    ],
                    [
                        'icon_name' => 'leaf',
                        'title' => ['id' => 'Ketepatan Spesifikasi [PLACEHOLDER]', 'en' => 'Specification Precision [PLACEHOLDER]'],
                        'description' => [
                            'id' => 'Pengawasan ketat kesesuaian nilai kalori, kadar abu, dan kelembapan sesuai kontrak perjanjian jual beli. [PLACEHOLDER]',
                            'en' => 'Strict oversight of calorific value, ash content, and moisture in compliance with purchase agreement terms. [PLACEHOLDER]',
                        ],
                    ],
                    [
                        'icon_name' => 'cog',
                        'title' => ['id' => 'Keandalan Pengapalan [PLACEHOLDER]', 'en' => 'Shipment Reliability [PLACEHOLDER]'],
                        'description' => [
                            'id' => 'Pengelolaan jadwal tongkang dan slot sandar jetty secara terukur untuk meminimalkan waktu tunggu dan demurrage. [PLACEHOLDER]',
                            'en' => 'Measured management of barge laycan schedules and jetty berthing slots to minimize turnaround and demurrage. [PLACEHOLDER]',
                        ],
                    ],
                    [
                        'icon_name' => 'handshake',
                        'title' => ['id' => 'Kepatuhan Berkelanjutan [PLACEHOLDER]', 'en' => 'Sustainable Compliance [PLACEHOLDER]'],
                        'description' => [
                            'id' => 'Pemenuhan penuh legalitas perizinan pertambangan, dokumen angkut resmi, dan kepatuhan standar K3LH berkelanjutan. [PLACEHOLDER]',
                            'en' => 'Full fulfillment of mining permits, formal shipping manifests, and sustainable occupational HSE standards. [PLACEHOLDER]',
                        ],
                    ],
                ],
            ];

            if (! $valuesStrip) {
                PageBlock::query()->create([
                    'page_id' => $homePage->id,
                    'company_id' => $company->id,
                    'section_key' => 'home_values',
                    'type' => BlockType::ValuesGrid,
                    'sort_order' => 2,
                    'is_visible' => true,
                    'data' => $valuesData,
                ]);
            } else {
                $valuesStrip->update(['data' => $valuesData, 'is_visible' => true]);
            }

            // Ensure Tentang Perusahaan (home_about) matches Korin Berkat profile
            $homeAbout = $homePage->blocks()->where('section_key', 'home_about')->first();
            $homeAboutData = [
                'eyebrow' => ['id' => 'PROFIL ENTITAS', 'en' => 'ENTITY PROFILE'],
                'title' => ['id' => 'Tentang PT Korin Bara Energy [PLACEHOLDER]', 'en' => 'About PT Korin Bara Energy [PLACEHOLDER]'],
                'lead' => [
                    'id' => 'Perdagangan batu bara termal dan manajemen pengapalan terukur untuk pembangkit dan industri B2B. [PLACEHOLDER]',
                    'en' => 'Thermal coal trading and measured shipment management for power utilities and industrial buyers. [PLACEHOLDER]',
                ],
                'body' => [
                    'id' => 'PT Korin Bara Energy melayani kontrak pasokan batu bara kalori menengah dengan kepastian jadwal tongkang, uji laboratorium independen di titik muat, dan dokumentasi lengkap. [PLACEHOLDER]',
                    'en' => 'PT Korin Bara Energy fulfills medium-calorific coal supply contracts with scheduled barge loading, independent laboratory verification at loading ports, and complete documentation. [PLACEHOLDER]',
                ],
                'image_position' => 'left',
                'image' => '/images/placeholders/coal-stockpile-1280.webp',
                'image_alt' => [
                    'id' => 'Area stockpile dan fasilitas pemuatan PT Korin Bara Energy [PLACEHOLDER]',
                    'en' => 'Stockpile yard and loading infrastructure of PT Korin Bara Energy [PLACEHOLDER]',
                ],
                'cta_label' => ['id' => 'Selengkapnya →', 'en' => 'Learn More →'],
                'cta_href' => '/tentang-kami',
            ];

            if (! $homeAbout) {
                PageBlock::query()->create([
                    'page_id' => $homePage->id,
                    'company_id' => $company->id,
                    'section_key' => 'home_about',
                    'type' => BlockType::SplitContent,
                    'sort_order' => 3,
                    'is_visible' => true,
                    'data' => $homeAboutData,
                ]);
            } else {
                $homeAbout->update(['data' => $homeAboutData, 'is_visible' => true]);
            }

            // Deactivate sections not in reference (Prompt 0 & Section 3)
            $homePage->blocks()->whereIn('type', [
                BlockType::Stats,
                BlockType::ProductList,
                BlockType::OperationList,
                BlockType::CtaBanner,
            ])->update(['is_visible' => false]);
        }

        // 6. Update About Us sections to match Korin Berkat content
        $aboutPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::About->value)
            ->first();

        if ($aboutPage) {
            // Update SplitContent (about_intro)
            $introBlock = $aboutPage->blocks()->where('type', BlockType::SplitContent)->first();
            if ($introBlock) {
                $introData = [
                    'eyebrow' => ['id' => 'PROFIL ENTITAS', 'en' => 'ENTITY PROFILE'],
                    'title' => ['id' => 'Tentang PT Korin Bara Energy [PLACEHOLDER]', 'en' => 'About PT Korin Bara Energy [PLACEHOLDER]'],
                    'lead' => [
                        'id' => 'Perdagangan batu bara termal dan manajemen pengapalan terukur untuk pembangkit dan industri B2B. [PLACEHOLDER]',
                        'en' => 'Thermal coal trading and measured shipment management for power utilities and industrial buyers. [PLACEHOLDER]',
                    ],
                    'body' => [
                        'id' => 'PT Korin Bara Energy melayani kontrak pasokan batu bara kalori menengah dengan kepastian jadwal tongkang, uji laboratorium independen di titik muat, dan dokumentasi lengkap. [PLACEHOLDER]',
                        'en' => 'PT Korin Bara Energy fulfills medium-calorific coal supply contracts with scheduled barge loading, independent laboratory verification at loading ports, and complete documentation. [PLACEHOLDER]',
                    ],
                    'image_position' => 'right',
                    'image' => '/images/placeholders/coal-stockpile-1280.webp',
                    'image_alt' => [
                        'id' => 'Area stockpile dan fasilitas pemuatan PT Korin Bara Energy [PLACEHOLDER]',
                        'en' => 'Stockpile yard and loading infrastructure of PT Korin Bara Energy [PLACEHOLDER]',
                    ],
                    'cta_label' => ['id' => 'Lihat Jaringan Operasi →', 'en' => 'View Operational Network →'],
                    'cta_href' => '/operasi',
                ];
                $introBlock->update(['data' => $introData, 'is_visible' => true]);
            }

            // Update MissionList (about_mission)
            $missionBlock = $aboutPage->blocks()->where('type', BlockType::MissionList)->first();
            if ($missionBlock) {
                $missionData = [
                    'eyebrow' => ['id' => 'VISI, MISI & KEPEMILIKAN', 'en' => 'VISION, MISSION & OWNERSHIP'],
                    'title' => ['id' => 'Mandat Strategis dalam Grup Derco Bara Energy [PLACEHOLDER]', 'en' => 'Strategic Mandate Within Derco Bara Energy Group [PLACEHOLDER]'],
                    'vision' => [
                        'id' => 'Menjadi mitra pasokan dan operasional energi yang unggul dalam kepastian mutu spesifikasi, keandalan jadwal pengapalan, serta kepatuhan hukum. [PLACEHOLDER]',
                        'en' => 'To be a premier energy supply and operational partner distinguished by specification certainty, shipment reliability, and legal compliance. [PLACEHOLDER]',
                    ],
                    'body' => [
                        'id' => 'Struktur Kepemilikan: PT Korin Bara Energy merupakan entitas anak usaha di bawah pengendalian langsung PT Derco Bara Energy (Holding), yang menyelaraskan tata kelola risiko, audit kepatuhan, dan alokasi infrastruktur grup. [PLACEHOLDER]',
                        'en' => 'Ownership Structure: PT Korin Bara Energy is a directly controlled operating subsidiary of PT Derco Bara Energy (Holding), aligning risk governance, compliance auditing, and group infrastructure allocation. [PLACEHOLDER]',
                    ],
                    'items' => [
                        [
                            'icon_name' => 'check-circle-2',
                            'title' => [
                                'id' => 'Menjaga konsistensi parameter kalori, moisture, dan kadar abu sesuai kontrak [PLACEHOLDER]',
                                'en' => 'Maintain strict calorific, moisture, and ash consistency against contract grades [PLACEHOLDER]',
                            ],
                            'description' => [
                                'id' => 'Pengawasan mutu berlapis dari pit tambang hingga sertifikasi surveyor independen di pelabuhan muat. [PLACEHOLDER]',
                                'en' => 'Multi-stage quality supervision from mine pit face through independent surveyor certification at loading port. [PLACEHOLDER]',
                            ],
                        ],
                        [
                            'icon_name' => 'check-circle-2',
                            'title' => [
                                'id' => 'Mengoptimalkan waktu putar tongkang dan efisiensi rantai logistik [PLACEHOLDER]',
                                'en' => 'Optimize barge turnaround times and logistics chain efficiency [PLACEHOLDER]',
                            ],
                            'description' => [
                                'id' => 'Koordinasi terjadwal antara armada hauling, kesiapan stockpile, dan slot sandar jetty. [PLACEHOLDER]',
                                'en' => 'Scheduled coordination across hauling fleets, stockpile readiness, and jetty berthing slots. [PLACEHOLDER]',
                            ],
                        ],
                        [
                            'icon_name' => 'check-circle-2',
                            'title' => [
                                'id' => 'Mematuhi seluruh ketentuan perizinan pertambangan dan keselamatan K3LH [PLACEHOLDER]',
                                'en' => 'Uphold all mining license obligations and occupational HSE standards [PLACEHOLDER]',
                            ],
                            'description' => [
                                'id' => 'Pelaksanaan operasi yang transparan, terdokumentasi, dan teraudit secara berkala. [PLACEHOLDER]',
                                'en' => 'Transparent, documented, and periodically audited operational execution. [PLACEHOLDER]',
                            ],
                        ],
                    ],
                ];
                $missionBlock->update(['data' => $missionData, 'is_visible' => true]);
            }

            // Deactivate timeline and values on About page (Prompt Section 3)
            $aboutPage->blocks()->whereIn('type', [
                BlockType::Timeline,
                BlockType::ValuesGrid,
            ])->update(['is_visible' => false]);
        }

        // 7. Update Products page to match Korin Berkat content
        $productsPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Products->value)
            ->first();

        if ($productsPage) {
            $productListBlock = $productsPage->blocks()->where('type', BlockType::ProductList)->first();
            if ($productListBlock) {
                $productListBlock->update([
                    'data' => [
                        'eyebrow' => ['id' => 'SPESIFIKASI TEKNIS', 'en' => 'TECHNICAL SPECIFICATIONS'],
                        'title' => ['id' => 'Katalog Produk PT Korin Bara Energy [PLACEHOLDER]', 'en' => 'PT Korin Bara Energy Product Catalog [PLACEHOLDER]'],
                        'description' => [
                            'id' => 'Parameter spesifikasi di bawah ini merujuk pada standar kontrak pengapalan dan diverifikasi oleh surveyor independen pada titik muat. [PLACEHOLDER]',
                            'en' => 'The specification parameters below reflect standard shipment contract grades verified by independent surveyors at loading ports. [PLACEHOLDER]',
                        ],
                        'limit' => 0,
                        'show_download_button' => false,
                    ],
                    'is_visible' => true,
                ]);
            }
        }

        // 8. Update Operations page to match Korin Berkat content
        $operationsPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Operations->value)
            ->first();

        if ($operationsPage) {
            $operationListBlock = $operationsPage->blocks()->where('type', BlockType::OperationList)->first();
            if ($operationListBlock) {
                $operationListBlock->update([
                    'data' => [
                        'eyebrow' => ['id' => 'INFRASTRUKTUR LAPANGAN', 'en' => 'FIELD INFRASTRUCTURE'],
                        'title' => ['id' => 'Jaringan Operasi PT Korin Bara Energy [PLACEHOLDER]', 'en' => 'PT Korin Bara Energy Operational Network [PLACEHOLDER]'],
                        'description' => [
                            'id' => 'Rantai operasional terpadu mulai dari area penambangan, fasilitas pengolahan, stockpile, hingga pelabuhan muat tongkang. [PLACEHOLDER]',
                            'en' => 'Integrated operational chain spanning mining pits, crushing plants, stockpiles, and barge-loading terminals. [PLACEHOLDER]',
                        ],
                        'show_map' => true,
                    ],
                    'is_visible' => true,
                ]);
            }
        }

        // 9. Sync Company Profile (tagline & about_short) and Stats
        $company->update([
            'tagline' => [
                'id' => 'Perdagangan batu bara termal dan manajemen pengapalan terukur untuk pembangkit dan industri B2B. [PLACEHOLDER]',
                'en' => 'Thermal coal trading and measured shipment management for power utilities and industrial buyers. [PLACEHOLDER]',
            ],
            'about_short' => [
                'id' => 'PT Korin Bara Energy melayani kontrak pasokan batu bara kalori menengah dengan kepastian jadwal tongkang, uji laboratorium independen di titik muat, dan dokumentasi lengkap. [PLACEHOLDER]',
                'en' => 'PT Korin Bara Energy fulfills medium-calorific coal supply contracts with scheduled barge loading, independent laboratory verification at loading ports, and complete documentation. [PLACEHOLDER]',
            ],
        ]);
    }
}
