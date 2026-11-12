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

    public static function getSubfolder(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        if ($scriptName !== '') {
            $scriptDir = str_replace('\\', '/', dirname($scriptName));
            if ($scriptDir !== '/' && $scriptDir !== '.') {
                return rtrim($scriptDir, '/');
            }
        }

        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        if (preg_match('#^(/[^/]+/(?:public/)?)#', $requestUri, $matches)) {
            $candidate = rtrim($matches[1], '/');
            $docRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
            if ($docRoot !== '' && is_dir($docRoot.$candidate)) {
                return $candidate;
            }
        }

        return '';
    }

    public static function resolveAssetUrl(string $path): string
    {
        $cleanPath = ltrim($path, '/');
        $subfolder = static::getSubfolder();

        return ($subfolder !== '' ? $subfolder : '').'/'.$cleanPath;
    }

    public function toHtml(): string
    {
        $html = '';

        // 1. Try resolving via Vite (HMR dev server or production manifest)
        try {
            $html = (string) app(Vite::class)($this->vitePath);
        } catch (Throwable) {
            // 2. Resilient fallback: If Vite manifest is missing this entry on the server,
            // serve the compiled static stylesheet directly without throwing ViteException.
            $fullStaticPath = public_path($this->staticPath);

            if (file_exists($fullStaticPath)) {
                $version = (string) filemtime($fullStaticPath);
                $url = static::resolveAssetUrl($this->staticPath);

                $html = '<link rel="stylesheet" href="'.$url.'?v='.$version.'" data-navigate-track />';
            }
        }

        if ($html === '') {
            return '';
        }

        // 3. Ensure URLs are root-relative (or match current request scheme/host and subfolder),
        // preventing mixed-content blocks or localhost hardcoding when APP_URL in .env
        // is set to http://localhost while accessed on subfolder or custom port/domain.
        return $this->normalizeToRootRelative($html);
    }

    protected function normalizeToRootRelative(string $html): string
    {
        $subfolder = static::getSubfolder();

        return (string) preg_replace_callback(
            '#(href|src)=["\']https?://[^/]+(:\d+)?(/[^"\']+)["\']#i',
            function (array $matches) use ($subfolder): string {
                $attr = $matches[1];
                $port = $matches[2] ?? '';
                $path = $matches[3];

                // If this is Vite HMR server on port 5173, keep full host so client can connect
                if ($port === ':5173') {
                    return $matches[0];
                }

                // If running in subfolder (e.g. /derco-app/public) and path does not have it yet
                if ($subfolder !== '' && ! str_starts_with($path, $subfolder)) {
                    $path = $subfolder.$path;
                }

                return sprintf('%s="%s"', $attr, $path);
            },
            $html
        );
    }
}
