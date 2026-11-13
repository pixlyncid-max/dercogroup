<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add content_version for optimistic concurrency locking
        if (! Schema::hasColumn('pages', 'content_version')) {
            Schema::table('pages', function (Blueprint $table): void {
                $table->unsignedInteger('content_version')->default(1)->after('is_published');
            });
        }

        if (! Schema::hasColumn('companies', 'content_version')) {
            Schema::table('companies', function (Blueprint $table): void {
                $table->unsignedInteger('content_version')->default(1)->after('sort_order');
            });
        }

        // 2. Create content_revisions table for change history & snapshot restore
        if (! Schema::hasTable('content_revisions')) {
            Schema::create('content_revisions', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
                $table->string('page_key');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->unsignedInteger('version')->default(1);
                $table->json('snapshot');
                $table->string('summary')->nullable();
                $table->timestamps();

                $table->index(['company_id', 'page_key', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('content_revisions');

        if (Schema::hasColumn('pages', 'content_version')) {
            Schema::table('pages', function (Blueprint $table): void {
                $table->dropColumn('content_version');
            });
        }

        if (Schema::hasColumn('companies', 'content_version')) {
            Schema::table('companies', function (Blueprint $table): void {
                $table->dropColumn('content_version');
            });
        }
    }
};
