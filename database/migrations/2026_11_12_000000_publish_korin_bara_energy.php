<?php

declare(strict_types=1);

use App\Domain\Company\Actions\PublishKorinBaraAction;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        app(PublishKorinBaraAction::class)->execute();
    }

    public function down(): void
    {
        // Irreversible company status transition.
    }
};

