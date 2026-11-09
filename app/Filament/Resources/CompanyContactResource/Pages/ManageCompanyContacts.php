<?php

declare(strict_types=1);

namespace App\Filament\Resources\CompanyContactResource\Pages;

use App\Filament\Resources\CompanyContactResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCompanyContacts extends ManageRecords
{
    protected static string $resource = CompanyContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
