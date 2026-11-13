<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table): void {
            if (! Schema::hasColumn('contact_messages', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table): void {
            if (Schema::hasColumn('contact_messages', 'internal_notes')) {
                $table->dropColumn('internal_notes');
            }
        });
    }
};
