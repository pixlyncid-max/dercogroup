<?php

declare(strict_types=1);

use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;

it('negotiates id or en from Accept-Language header with id fallback', function (): void {
    $idRequest = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'id-ID,id;q=0.9,en;q=0.8']);
    expect(SetLocale::negotiateFromRequest($idRequest))->toBe('id');

    $enRequest = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'en-GB,en;q=0.9']);
    expect(SetLocale::negotiateFromRequest($enRequest))->toBe('en');

    $jaRequest = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => 'ja-JP']);
    expect(SetLocale::negotiateFromRequest($jaRequest))->toBe('id');
});
