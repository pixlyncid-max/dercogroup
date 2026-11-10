<?php

declare(strict_types=1);

use App\Domain\Company\Actions\SyncSubsidiaryPagesAction;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        app(SyncSubsidiaryPagesAction::class)->execute();
    }

    public function down(): void
    {
        // Irreversible content structure update.
    }
};
