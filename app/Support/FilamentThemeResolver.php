<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Foundation\Vite;
use Stringable;
use Throwable;

class FilamentThemeResolver implements Htmlable, Stringable
{
    public function __construct(
        protected string $vitePath = 'resources/css/filament/admin/theme.css',
        protected string $staticPath = 'css/filament/admin/theme.css',
    ) {}

    public static function make(
        string $vitePath = 'resources/css/filament/admin/theme.css',
        string $staticPath = 'css/filament/admin/theme.css',
    ): static {
        return new static($vitePath, $staticPath);
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }

    public function toHtml(): string
    {
        // 1. Try resolving via Vite (HMR dev server or production manifest)
        try {
            return (string) app(Vite::class)($this->vitePath);
        } catch (Throwable) {
            // 2. Resilient fallback: If Vite manifest is missing this entry on the server,
            // serve the compiled static stylesheet directly without throwing ViteException.
            $fullStaticPath = public_path($this->staticPath);

            if (file_exists($fullStaticPath)) {
                $version = (string) filemtime($fullStaticPath);

                return '<link rel="stylesheet" href="'.asset($this->staticPath).'?v='.$version.'" data-navigate-track />';
            }

            // 3. Graceful fallback: return empty string so Filament loads default theme
            return '';
        }
    }
}
