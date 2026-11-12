<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Company\Actions\GetGroupSummaryAction;
use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Models\Company;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

class GroupSummaryWidget extends Widget
{
    protected string $view = 'filament.widgets.group-summary';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    /**
     * @return Collection<int, array{
     *     id: int,
     *     name: string,
     *     slug: string,
     *     theme: string,
     *     status: CompanyStatus,
     *     is_published: bool,
     *     is_holding: bool,
     *     new_messages_count: int,
     *     draft_pages_count: int,
     *     total_pages_count: int,
     *     switch_url: string
     * }>
     */
    public function getSummary(): Collection
    {
        return app(GetGroupSummaryAction::class)->execute();
    }

    public function getCurrentTenantSlug(): ?string
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Company ? $tenant->slug : null;
    }
}
