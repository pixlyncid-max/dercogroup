<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Domain\Company\Models\Company;
use App\Filament\Pages\Tenancy\EditCompanyProfile;
use App\Filament\Resources\CertificationResource;
use App\Filament\Resources\ContactMessageResource;
use App\Filament\Resources\OperationResource;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\ProductResource;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected string $view = 'filament.widgets.quick-actions';

    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'lg' => 4,
    ];

    public function getCompany(): ?Company
    {
        $tenant = Filament::getTenant();

        return $tenant instanceof Company ? $tenant : null;
    }

    public function getProductUrl(): ?string
    {
        $company = $this->getCompany();
        if ($company && ! $company->hasPage('products')) {
            return null;
        }

        try {
            return ProductResource::getUrl('index');
        } catch (\Throwable) {
            return null;
        }
    }

    public function getPagesUrl(): string
    {
        try {
            return PageResource::getUrl('index');
        } catch (\Throwable) {
            return '#';
        }
    }

    public function getOperationsUrl(): ?string
    {
        $company = $this->getCompany();
        if ($company && ! $company->hasPage('operations')) {
            return null;
        }

        try {
            return OperationResource::getUrl('index');
        } catch (\Throwable) {
            return null;
        }
    }

    public function getComplianceUrl(): ?string
    {
        $company = $this->getCompany();
        if ($company && ! $company->hasPage('compliance')) {
            return null;
        }

        try {
            return CertificationResource::getUrl('index');
        } catch (\Throwable) {
            return null;
        }
    }

    public function getMessagesUrl(): string
    {
        try {
            return ContactMessageResource::getUrl('index');
        } catch (\Throwable) {
            return '#';
        }
    }

    public function getProfileUrl(): string
    {
        try {
            return EditCompanyProfile::getUrl();
        } catch (\Throwable) {
            return '#';
        }
    }
}
