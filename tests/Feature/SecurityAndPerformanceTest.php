<?php

declare(strict_types=1);

use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

it('attaches standard security headers to all web responses', function (): void {
    $response = $this->get('/id');

    $response->assertStatus(200);
    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    expect($response->headers->get('Content-Security-Policy'))->not->toBeEmpty();
});

it('caches public responses and disables cache for draft preview', function (): void {
    $publicResponse = $this->get('/id');
    $publicResponse->assertStatus(200);
    expect($publicResponse->headers->get('Cache-Control'))->toContain('public');
    expect($publicResponse->headers->get('Cache-Control'))->toContain('max-age=300');

    $korinBerkat = Company::query()->where('theme', CompanyTheme::KorinBerkat)->firstOrFail();
    $adminUser = User::query()->where('email', 'admin@dercobara.co.id')->firstOrFail();

    $previewResponse = $this->actingAs($adminUser)->get('/id/bisnis-kami/korin-bara-energy?preview=1');
    $previewResponse->assertStatus(200);
    expect($previewResponse->headers->get('Cache-Control'))->toContain('no-store');
    expect($previewResponse->headers->get('X-Robots-Tag'))->toBe('noindex, nofollow');
});

it('renders a branded 404 error page matching the last visited theme', function (): void {
    // Visit Korin Berkat to establish session theme
    $this->get('/id/bisnis-kami/korin-berkat-energy')->assertStatus(200);

    // Request non-existent page
    $response = $this->get('/id/bisnis-kami/korin-berkat-energy/halaman-tidak-ada');
    $response->assertStatus(404);
    $response->assertInertia(fn ($page) => $page
        ->component('Error')
        ->where('status', 404)
        ->where('theme', 'korin-berkat')
    );
});
