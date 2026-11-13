<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Turns a stored image reference into a public URL plus an optional responsive srcset.
 *
 * - Paths starting with "/" are public assets (e.g. committed placeholders in public/images).
 *   When the file follows the "<name>-<width>.webp" convention, sibling widths that exist
 *   on disk are emitted as a srcset.
 * - Absolute URLs are passed through unchanged.
 * - Anything else is a CMS upload stored on the "public" disk.
 * - If path is empty and $allowFallback is true, resolves to the local SVG placeholder.
 */
final class ImageSource
{
    /** @var list<int> */
    public const RESPONSIVE_WIDTHS = [640, 1280];

    public const FALLBACK_PLACEHOLDER = '/images/placeholders/placeholder-image.svg';

    /**
     * @return array{url: string|null, srcset: string|null}
     */
    public static function resolve(?string $path, bool $allowFallback = true): array
    {
        $path = $path !== null ? trim($path) : '';

        if ($path === '') {
            return [
                'url' => $allowFallback ? self::FALLBACK_PLACEHOLDER : null,
                'srcset' => null,
            ];
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return ['url' => $path, 'srcset' => null];
        }

        if (str_starts_with($path, '/')) {
            return ['url' => $path, 'srcset' => self::publicSrcset($path)];
        }

        return ['url' => Storage::disk('public')->url($path), 'srcset' => null];
    }

    private static function publicSrcset(string $path): ?string
    {
        if (preg_match('/^(?<base>.+)-\d+\.webp$/', $path, $matches) !== 1) {
            return null;
        }

        $candidates = [];
        foreach (self::RESPONSIVE_WIDTHS as $width) {
            $candidate = "{$matches['base']}-{$width}.webp";
            if (is_file(public_path(ltrim($candidate, '/')))) {
                $candidates[] = "{$candidate} {$width}w";
            }
        }

        return count($candidates) > 1 ? implode(', ', $candidates) : null;
    }
}
