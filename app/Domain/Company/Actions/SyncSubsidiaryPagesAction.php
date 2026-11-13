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
            // Update Hero with title_emphasis
            $heroBlock = $homePage->blocks()->where('type', BlockType::Hero)->first();
            if ($heroBlock) {
                $heroData = $heroBlock->data ?? [];
                $heroData['eyebrow'] = ['id' => 'PT Korin Bara Energy', 'en' => 'PT Korin Bara Energy'];
                $heroData['title'] = ['id' => 'Energi untuk', 'en' => 'Energy for'];
                $heroData['title_emphasis'] = ['id' => 'Masa Depan Negeri', 'en' => "the Nation's Future"];
                $heroData['description'] = [
                    'id' => 'Kami berkomitmen untuk menyediakan batubara berkualitas tinggi dengan mengedepankan praktik pertambangan yang aman, efisien, dan berkelanjutan. [PLACEHOLDER]',
                    'en' => 'We are committed to supplying high-grade coal with an emphasis on safe, efficient, and sustainable mining practices. [PLACEHOLDER]',
                ];
                $heroData['primary_cta_label'] = ['id' => 'Tentang Kami', 'en' => 'About Us'];
                $heroData['primary_cta_href'] = '/tentang-kami';
                $heroData['secondary_cta_label'] = ['id' => 'Hubungi Kami', 'en' => 'Contact Us'];
                $heroData['secondary_cta_href'] = '/kontak';
                $heroBlock->update(['data' => $heroData, 'is_visible' => true]);
            }

            // Ensure Values Strip (home_values) exists
            $valuesStrip = $homePage->blocks()->where('section_key', 'home_values')->first();
            if (! $valuesStrip) {
                PageBlock::query()->create([
                    'page_id' => $homePage->id,
                    'company_id' => $company->id,
                    'section_key' => 'home_values',
                    'type' => BlockType::ValuesGrid,
                    'sort_order' => 2,
                    'is_visible' => true,
                    'data' => [
                        'eyebrow' => ['id' => 'NILAI KAMI', 'en' => 'OUR VALUES'],
                        'title' => ['id' => 'Pilar Nilai Operasional [PLACEHOLDER]', 'en' => 'Operational Value Pillars [PLACEHOLDER]'],
                        'description' => ['id' => '', 'en' => ''],
                        'items' => [
                            [
                                'icon_name' => 'shield-check',
                                'title' => ['id' => 'Keselamatan Kerja Prioritas Utama [PLACEHOLDER]', 'en' => 'Workplace Safety Top Priority [PLACEHOLDER]'],
                                'description' => ['id' => 'Menerapkan standar K3 tambang secara ketat dan tanpa kompromi.', 'en' => 'Strictly implementing mining HSE standards without compromise.'],
                            ],
                            [
                                'icon_name' => 'leaf',
                                'title' => ['id' => 'Operasi Ramah Lingkungan [PLACEHOLDER]', 'en' => 'Eco-Friendly Operations [PLACEHOLDER]'],
                                'description' => ['id' => 'Reklamasi progresif dan pengelolaan limbah pertambangan terukur.', 'en' => 'Progressive reclamation and measured mine waste management.'],
                            ],
                            [
                                'icon_name' => 'cog',
                                'title' => ['id' => 'Efisiensi & Produktivitas [PLACEHOLDER]', 'en' => 'Efficiency & Productivity [PLACEHOLDER]'],
                                'description' => ['id' => 'Optimalisasi armada hauling dan ketersediaan alat berat tambang.', 'en' => 'Optimizing haul fleets and heavy mining equipment availability.'],
                            ],
                            [
                                'icon_name' => 'handshake',
                                'title' => ['id' => 'Kemitraan Jangka Panjang [PLACEHOLDER]', 'en' => 'Long-Term Partnership [PLACEHOLDER]'],
                                'description' => ['id' => 'Hubungan saling menguntungkan dengan seluruh pemangku kepentingan.', 'en' => 'Mutually beneficial relationships with all stakeholders.'],
                            ],
                        ],
                    ],
                ]);
            }

            // Ensure Tentang Perusahaan (home_about) exists
            $homeAbout = $homePage->blocks()->where('section_key', 'home_about')->first();
            if (! $homeAbout) {
                PageBlock::query()->create([
                    'page_id' => $homePage->id,
                    'company_id' => $company->id,
                    'section_key' => 'home_about',
                    'type' => BlockType::SplitContent,
                    'sort_order' => 3,
                    'is_visible' => true,
                    'data' => [
                        'eyebrow' => ['id' => 'Tentang Perusahaan', 'en' => 'About Company'],
                        'title' => ['id' => 'PT Korin Bara Energy', 'en' => 'PT Korin Bara Energy'],
                        'body' => [
                            'id' => 'Perusahaan pertambangan batubara yang berkomitmen untuk memberikan kontribusi terbaik bagi industri energi melalui operasi yang efisien, aman, dan berkelanjutan. [PLACEHOLDER]',
                            'en' => 'A coal mining enterprise dedicated to delivering optimal contributions to the energy sector through safe, efficient, and sustainable operations. [PLACEHOLDER]',
                        ],
                        'image_position' => 'left',
                        'cta_label' => ['id' => 'Selengkapnya →', 'en' => 'Learn More →'],
                        'cta_href' => '/tentang-kami',
                    ],
                ]);
            }

            // Deactivate sections not in reference (Prompt 0 & Section 3)
            $homePage->blocks()->whereIn('type', [
                BlockType::Stats,
                BlockType::ProductList,
                BlockType::OperationList,
                BlockType::CtaBanner,
            ])->update(['is_visible' => false]);
        }

        // 6. Update About Us sections
        $aboutPage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::About->value)
            ->first();

        if ($aboutPage) {
            // Update SplitContent (about_intro)
            $introBlock = $aboutPage->blocks()->where('type', BlockType::SplitContent)->first();
            if ($introBlock) {
                $introData = $introBlock->data ?? [];
                $introData['eyebrow'] = ['id' => 'Profil Perusahaan', 'en' => 'Company Profile'];
                $introData['title'] = ['id' => 'PT Korin Bara Energy', 'en' => 'PT Korin Bara Energy'];
                $introData['body'] = [
                    'id' => "PT Korin Bara Energy adalah perusahaan pertambangan batubara yang berkomitmen untuk memberikan kontribusi terbaik bagi industri energi nasional melalui operasi yang efisien, aman, dan berkelanjutan. [PLACEHOLDER]\n\nDengan pengalaman dan dedikasi tinggi, kami terus berupaya menjadi mitra terpercaya dalam penyediaan batubara berkualitas untuk mendukung kebutuhan energi di Indonesia. [PLACEHOLDER]",
                    'en' => "PT Korin Bara Energy is a coal mining company committed to providing the best contribution to the national energy industry through efficient, safe, and sustainable operations. [PLACEHOLDER]\n\nWith high experience and dedication, we continuously strive to be a trusted partner in supplying quality coal to support Indonesia's energy needs. [PLACEHOLDER]",
                ];
                $introData['image_position'] = 'right';
                $introData['cta_label'] = ['id' => 'Tentang Kami Lebih Lengkap →', 'en' => 'Explore Our Profile →'];
                $introData['cta_href'] = '/operasi';
                $introBlock->update(['data' => $introData, 'is_visible' => true]);
            }

            // Update MissionList (about_mission)
            $missionBlock = $aboutPage->blocks()->where('type', BlockType::MissionList)->first();
            if ($missionBlock) {
                $missionData = [
                    'eyebrow' => ['id' => 'ARAH STRATEGIS', 'en' => 'STRATEGIC DIRECTION'],
                    'title' => ['id' => 'Visi & Misi Perusahaan [PLACEHOLDER]', 'en' => 'Company Vision & Mission [PLACEHOLDER]'],
                    'vision' => [
                        'id' => '"Menjadi perusahaan tambang batubara terkemuka di Indonesia yang berkelanjutan." [PLACEHOLDER]',
                        'en' => '"To become a leading sustainable coal mining company in Indonesia." [PLACEHOLDER]',
                    ],
                    'items' => [
                        [
                            'icon_name' => 'check-circle-2',
                            'title' => ['id' => 'Menyediakan batubara berkualitas dengan standar tinggi. [PLACEHOLDER]', 'en' => 'Supplying high-quality coal with strict international standards. [PLACEHOLDER]'],
                            'description' => ['id' => 'Konsistensi kalori dan spesifikasi teknis.', 'en' => 'Consistency of calorific value and technical specs.'],
                        ],
                        [
                            'icon_name' => 'check-circle-2',
                            'title' => ['id' => 'Mengutamakan keselamatan kerja dan kelestarian lingkungan. [PLACEHOLDER]', 'en' => 'Prioritizing occupational safety and environmental conservation. [PLACEHOLDER]'],
                            'description' => ['id' => 'Nol kecelakaan fatal dan reklamasi tambang aktif.', 'en' => 'Zero fatal incidents and active mine reclamation.'],
                        ],
                        [
                            'icon_name' => 'check-circle-2',
                            'title' => ['id' => 'Memberikan nilai tambah bagi pemangku kepentingan. [PLACEHOLDER]', 'en' => 'Creating sustainable value for all stakeholders. [PLACEHOLDER]'],
                            'description' => ['id' => 'Kontribusi nyata bagi industri energi nasional.', 'en' => 'Real contribution to the national energy sector.'],
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
    }
}
