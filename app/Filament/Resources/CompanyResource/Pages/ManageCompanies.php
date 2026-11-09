<?php

declare(strict_types=1);

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Domain\Company\Actions\CreateCompanyPages;
use App\Domain\Company\Models\Company;
use App\Filament\Resources\CompanyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanies extends ManageRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->after(function (Company $record): void {
                    app(CreateCompanyPages::class)->execute($record);
                }),
        ];
    }
}
