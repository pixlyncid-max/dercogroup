<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia as Assert;

it('redirects root path to /id by default when no Accept-Language header is provided', function (): void {
    $response = $this->get('/', ['Accept-Language' => '']);

    $response->assertRedirect('/id');
});

it('redirects root path to /en when Accept-Language prefers English', function (): void {
    $response = $this->get('/', ['Accept-Language' => 'en-US,en;q=0.9']);

    $response->assertRedirect('/en');
});

it('falls back to /id when Accept-Language is an unsupported locale', function (): void {
    $response = $this->get('/', ['Accept-Language' => 'fr-FR,fr;q=0.9']);

    $response->assertRedirect('/id');
});

it('renders the Indonesian home page with shared Inertia props', function (): void {
    $response = $this->get('/id');

    $response->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('Home')
            ->where('locale', 'id')
            ->where('locales', ['id', 'en'])
            ->where('company.theme', 'holding')
            ->where('translations.foundation.eyebrow', 'GRUP PERTAMBANGAN & INFRASTRUKTUR ENERGI')
        );
});

it('renders the English home page with shared Inertia props', function (): void {
    $response = $this->get('/en');

    $response->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('Home')
            ->where('locale', 'en')
            ->where('locales', ['id', 'en'])
            ->where('company.theme', 'holding')
            ->where('translations.foundation.eyebrow', 'MINING & ENERGY INFRASTRUCTURE GROUP')
        );
});

it('returns 404 for unsupported locale prefix', function (): void {
    $response = $this->get('/de');

    $response->assertNotFound();
});
