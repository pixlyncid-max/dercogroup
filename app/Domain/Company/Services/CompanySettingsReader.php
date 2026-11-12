<?php

declare(strict_types=1);

namespace App\Domain\Company\Services;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\Setting;

class CompanySettingsReader
{
    public function get(string $key, mixed $default = null, Company|int|null $company = null): mixed
    {
        $companyId = $company instanceof Company ? $company->id : $company;

        if ($companyId !== null) {
            $companySetting = Setting::query()
                ->where('company_id', $companyId)
                ->where('key', $key)
                ->first();

            if ($companySetting !== null && $companySetting->value !== null) {
                return $companySetting->value;
            }
        }

        $globalSetting = Setting::query()
            ->whereNull('company_id')
            ->where('key', $key)
            ->first();

        return $globalSetting !== null ? $globalSetting->value : $default;
    }

    public function set(string $key, mixed $value, string $group = 'general', Company|int|null $company = null): Setting
    {
        $companyId = $company instanceof Company ? $company->id : $company;

        return Setting::query()->updateOrCreate(
            [
                'company_id' => $companyId,
                'key' => $key,
            ],
            [
                'group' => $group,
                'value' => is_array($value) ? $value : ['value' => $value],
            ],
        );
    }
}

