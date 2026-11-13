<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Models\Page;
use App\Domain\Page\Models\PageBlock;
use App\Domain\Page\Schema\SectionSchema;
use Illuminate\Support\Collection;

class CreateCompanyPages
{
    /**
     * Create the standard pages for a company and initialize default blocks from SectionSchema.
     *
     * @return Collection<int, Page>
     */
    public function execute(Company $company): Collection
    {
        $keys = $company->theme->pageKeys();
        $overrides = $company->theme->pageOverrides();

        $pages = collect();

        foreach ($keys as $index => $pageKey) {
            $titles = $pageKey->defaultTitles($company->theme);
            $slugs = $pageKey->defaultSlugs($company->theme);

            $pageOverride = $overrides[$pageKey->value] ?? [];
            $isPublished = $pageOverride['is_published'] ?? true;
            $showInNav = $pageOverride['show_in_nav'] ?? true;

            $page = Page::query()->firstOrCreate(
                [
                    'company_id' => $company->id,
                    'key' => $pageKey->value,
                ],
                [
                    'title' => $titles,
                    'slug' => $slugs,
                    'meta_title' => [
                        'id' => "{$titles['id']} — {$company->name}",
                        'en' => "{$titles['en']} — {$company->name}",
                    ],
                    'meta_description' => [
                        'id' => $company->translateField('tagline', 'id') ?: "{$titles['id']} {$company->name}",
                        'en' => $company->translateField('tagline', 'en') ?: "{$titles['en']} {$company->name}",
                    ],
                    'show_in_nav' => $showInNav,
                    'sort_order' => $index + 1,
                    'is_published' => $isPublished,
                ],
            );

            // Seed default blocks for this page from SectionSchema
            $sections = SectionSchema::getSectionsForPage($company->theme, $pageKey->value);
            foreach ($sections as $sectionIndex => $section) {
                $sectionKey = $section['section_key'];
                $blockType = $section['block_type'];
                $isCompanyScoped = $section['is_company_scoped'] ?? false;
                $defaultData = SectionSchema::getDefaultDataForSection($company->slug, $pageKey->value, $sectionKey, $blockType);

                if ($isCompanyScoped) {
                    PageBlock::query()->firstOrCreate(
                        [
                            'company_id' => $company->id,
                            'section_key' => $sectionKey,
                        ],
                        [
                            'page_id' => null,
                            'type' => $blockType,
                            'data' => $defaultData,
                            'is_visible' => true,
                            'sort_order' => $sectionIndex + 1,
                        ]
                    );
                } else {
                    PageBlock::query()->firstOrCreate(
                        [
                            'page_id' => $page->id,
                            'section_key' => $sectionKey,
                        ],
                        [
                            'company_id' => null,
                            'type' => $blockType,
                            'data' => $defaultData,
                            'is_visible' => true,
                            'sort_order' => $sectionIndex + 1,
                        ]
                    );
                }
            }

            $pages->push($page);
        }

        // Initialize default Footer pseudo-page block
        $footerDefault = SectionSchema::getDefaultDataForSection($company->slug, 'footer', 'footer', BlockType::Footer);
        PageBlock::query()->firstOrCreate(
            [
                'company_id' => $company->id,
                'section_key' => 'footer',
            ],
            [
                'page_id' => null,
                'type' => BlockType::Footer,
                'data' => $footerDefault,
                'is_visible' => true,
                'sort_order' => 99,
            ]
        );

        return $pages;
    }
}
