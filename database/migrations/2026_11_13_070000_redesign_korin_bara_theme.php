<?php

declare(strict_types=1);

use App\Domain\Company\Actions\SyncSubsidiaryPagesAction;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations for Korin Bara redesign.
     */
    public function up(): void
    {
        app(SyncSubsidiaryPagesAction::class)->execute();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe idempotent migration; down is no-op
    }
};
