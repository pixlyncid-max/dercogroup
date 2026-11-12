<?php

declare(strict_types=1);

use App\Support\FilamentThemeResolver;

it('returns Vite html when Vite resolves the theme successfully', function (): void {
    $resolver = FilamentThemeResolver::make();
    $html = $resolver->toHtml();

    // In testing or built environment, it returns a valid link tag
    expect($html)->toBeString()
        ->and($html)->toContain('<link rel="stylesheet"');
});

it('gracefully falls back to static css file when Vite throws an exception', function (): void {
    // Points to non-existent Vite entry to trigger ViteException
    $resolver = new FilamentThemeResolver(
        vitePath: 'resources/css/non-existent-theme-entry.css',
        staticPath: 'css/filament/admin/theme.css',
    );

    // Must NOT throw ViteException
    $html = $resolver->toHtml();

    expect($html)->toBeString()
        ->and($html)->toContain('css/filament/admin/theme.css')
        ->and($html)->toContain('rel="stylesheet"');
});

it('gracefully returns empty string when neither Vite nor static file exists without crashing', function (): void {
    $resolver = new FilamentThemeResolver(
        vitePath: 'resources/css/non-existent-theme-entry.css',
        staticPath: 'css/non-existent-file.css',
    );

    $html = $resolver->toHtml();

    expect($html)->toBe('');
});

