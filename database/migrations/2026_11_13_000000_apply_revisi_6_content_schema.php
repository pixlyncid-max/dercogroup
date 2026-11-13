<?php

declare(strict_types=1);

use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add company_id and section_key to page_blocks, make page_id nullable
        Schema::table('page_blocks', function (Blueprint $table): void {
            if (! Schema::hasColumn('page_blocks', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('page_id')->constrained('companies')->cascadeOnDelete();
            }

            if (! Schema::hasColumn('page_blocks', 'section_key')) {
                $table->string('section_key', 60)->nullable()->after('type')->index();
            }
        });

        // For SQLite and MySQL: adjust page_id to be nullable if needed
        // In SQLite, altering columns with foreign keys requires change()
        try {
            Schema::table('page_blocks', function (Blueprint $table): void {
                $table->foreignId('page_id')->nullable()->change();
            });
        } catch (Throwable) {
            // Some drivers do not require or allow foreignId change
        }

        // 2. Data migration: backfill company_id and section_key for existing blocks
        $blocks = DB::table('page_blocks')
            ->leftJoin('pages', 'page_blocks.page_id', '=', 'pages.id')
            ->select('page_blocks.id', 'page_blocks.page_id', 'page_blocks.type', 'page_blocks.sort_order', 'page_blocks.company_id', 'page_blocks.section_key', 'pages.company_id as parent_company_id', 'pages.key as page_key')
            ->get();

        foreach ($blocks as $block) {
            $updates = [];

            if ($block->company_id === null && $block->parent_company_id !== null) {
                $updates['company_id'] = $block->parent_company_id;
            }

            if ($block->section_key === null) {
                $prefix = $block->page_key ?? 'general';
                $updates['section_key'] = "{$prefix}_{$block->type}_{$block->sort_order}";
            }

            if ($updates !== []) {
                DB::table('page_blocks')->where('id', $block->id)->update($updates);
            }
        }

        // 3. Remove Compliance / Mutu & Kepatuhan pages and blocks across all companies (Section 0 Item 9)
        $compliancePages = DB::table('pages')->where('key', PageKey::Compliance->value)->get();
        foreach ($compliancePages as $page) {
            DB::table('page_blocks')->where('page_id', $page->id)->delete();
            DB::table('pages')->where('id', $page->id)->delete();
        }

        // Also clean up any CertificationList blocks from Beranda across all companies
        DB::table('page_blocks')
            ->where('type', BlockType::CertificationList->value)
            ->delete();

        // 4. Seed default company-scoped Header and Footer blocks if missing
        $companies = DB::table('companies')->get();
        foreach ($companies as $company) {
            // Header block
            $hasHeader = DB::table('page_blocks')
                ->where('company_id', $company->id)
                ->where('section_key', 'header')
                ->exists();

            if (! $hasHeader) {
                DB::table('page_blocks')->insert([
                    'company_id' => $company->id,
                    'page_id' => null,
                    'type' => 'header',
                    'section_key' => 'header',
                    'data' => json_encode([
                        'logo_path' => null,
                        'logo_alt' => [
                            'id' => "Logo {$company->name}",
                            'en' => "{$company->name} Logo",
                        ],
                        'show_company_badge' => true,
                        'show_language_switch' => true,
                        'nav_items' => [],
                    ]),
                    'sort_order' => 0,
                    'is_visible' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Footer block
            $hasFooter = DB::table('page_blocks')
                ->where('company_id', $company->id)
                ->where('section_key', 'footer')
                ->exists();

            if (! $hasFooter) {
                DB::table('page_blocks')->insert([
                    'company_id' => $company->id,
                    'page_id' => null,
                    'type' => 'footer',
                    'section_key' => 'footer',
                    'data' => json_encode([
                        'description' => [
                            'id' => 'Grup energi terintegrasi dan berkelanjutan.',
                            'en' => 'Integrated and sustainable energy group.',
                        ],
                        'copyright_text' => [
                            'id' => "© 2026 {$company->name}. Seluruh hak cipta dilindungi.",
                            'en' => "© 2026 {$company->name}. All rights reserved.",
                        ],
                        'quick_links' => [],
                        'disclaimer' => [
                            'id' => null,
                            'en' => null,
                        ],
                    ]),
                    'sort_order' => 999,
                    'is_visible' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('page_blocks', function (Blueprint $table): void {
            if (Schema::hasColumn('page_blocks', 'company_id')) {
                $table->dropForeign(['company_id']);
                $table->dropColumn('company_id');
            }

            if (Schema::hasColumn('page_blocks', 'section_key')) {
                $table->dropColumn('section_key');
            }
        });
    }
};
