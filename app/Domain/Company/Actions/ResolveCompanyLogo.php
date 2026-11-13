<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Models\PageBlock;
use Illuminate\Support\Facades\Storage;

class ResolveCompanyLogo
{
    /**
     * @return array{
     *     light: string,
     *     dark: string,
     *     compact: ?string,
     *     width: int,
     *     height: int,
     *     alt: string
     * }|null
     */
    public function execute(Company|CompanyTheme|string $themeOrCompany): ?array
    {
        $themeKey = match (true) {
            $themeOrCompany instanceof Company => $themeOrCompany->theme->value,
            $themeOrCompany instanceof CompanyTheme => $themeOrCompany->value,
            default => (string) $themeOrCompany,
        };

        // 1. Check if a custom logo was uploaded in the Header block for this company
        $company = match (true) {
            $themeOrCompany instanceof Company => $themeOrCompany,
            $themeOrCompany instanceof CompanyTheme => Company::query()->where('theme', $themeOrCompany)->first(),
            default => Company::query()->where('slug', $themeOrCompany)->orWhere('theme', $themeOrCompany)->first(),
        };

        if ($company instanceof Company) {
            $headerBlock = PageBlock::query()
                ->where('company_id', $company->id)
                ->where(function ($q): void {
                    $q->where('section_key', 'header')
                        ->orWhere('type', BlockType::Header);
                })
                ->first();

            $customLogo = $headerBlock?->data['logo_path'] ?? null;
            if (is_string($customLogo) && trim($customLogo) !== '') {
                $customPath = trim($customLogo);
                $fileExists = false;
                $url = '';

                if (str_starts_with($customPath, 'http://') || str_starts_with($customPath, 'https://')) {
                    $fileExists = true;
                    $url = $customPath;
                } elseif (str_starts_with($customPath, '/')) {
                    $localPath = public_path(ltrim($customPath, '/'));
                    if (file_exists($localPath)) {
                        $fileExists = true;
                        $url = $customPath;
                    }
                } else {
                    if (Storage::disk('public')->exists($customPath)) {
                        $fileExists = true;
                        $url = Storage::disk('public')->url($customPath);
                    } elseif (file_exists(public_path($customPath))) {
                        $fileExists = true;
                        $url = '/'.ltrim($customPath, '/');
                    }
                }

                if ($fileExists && $url !== '') {
                    $altData = $headerBlock->data['logo_path_alt'] ?? null;
                    $alt = is_array($altData)
                        ? ($altData['id'] ?? $altData['en'] ?? $company->name)
                        : (is_string($altData) && $altData !== '' ? $altData : $company->name);

                    return [
                        'light' => $url,
                        'dark' => $url,
                        'compact' => $url,
                        'width' => 240,
                        'height' => 64,
                        'alt' => (string) $alt,
                    ];
                }
            }
        }

        // 2. Fall back to config/branding.php
        $config = config("branding.logos.{$themeKey}");

        if (! is_array($config) || empty($config['light'])) {
            return null;
        }

        $lightPath = public_path(ltrim((string) $config['light'], '/'));
        if (! file_exists($lightPath)) {
            return null;
        }

        $darkRel = ! empty($config['dark']) ? (string) $config['dark'] : null;
        $darkPath = $darkRel ? public_path(ltrim($darkRel, '/')) : null;
        $darkFile = ($darkPath && file_exists($darkPath)) ? $darkRel : (string) $config['light'];

        $compactRel = ! empty($config['compact']) ? (string) $config['compact'] : null;
        $compactPath = $compactRel ? public_path(ltrim($compactRel, '/')) : null;
        $compactFile = ($compactPath && file_exists($compactPath)) ? $compactRel : null;

        return [
            'light' => (string) $config['light'],
            'dark' => $darkFile,
            'compact' => $compactFile,
            'width' => (int) ($config['width'] ?? 0),
            'height' => (int) ($config['height'] ?? 0),
            'alt' => (string) ($config['alt'] ?? ''),
        ];
    }
}
