<?php

declare(strict_types=1);

namespace App\Filament\Resources\CompanyStatResource\Pages;

use App\Filament\Resources\CompanyStatResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyStats extends ManageRecords
{
    protected static string $resource = CompanyStatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
