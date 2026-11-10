<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;

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

        // 1. Deactivate Compliance page (is_published = false, show_in_nav = false)
        $compliancePage = Page::query()
            ->where('company_id', $company->id)
            ->where('key', PageKey::Compliance->value)
            ->first();

        if ($compliancePage) {
            $compliancePage->update([
                'is_published' => false,
                'show_in_nav' => false,
            ]);
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
}
