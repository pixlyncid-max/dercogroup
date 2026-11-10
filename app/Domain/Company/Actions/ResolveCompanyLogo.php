<?php

declare(strict_types=1);

namespace App\Domain\Company\Actions;

use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;

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
