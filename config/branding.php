<?php

declare(strict_types=1);

/**
 * Konfigurasi Branding & Logo Perusahaan.
 *
 * PERHATIAN PENAMAAN:
 * - 'light': Berkas logo untuk permukaan TERANG (background putih/terang). Berisi logo asli bertinta gelap.
 * - 'dark': Berkas logo untuk permukaan GELAP (background charcoal/navy/dark water). Berisi varian bertinta terang/putih.
 * - 'compact': Berkas logo ringkas / ikon untuk penempatan sempit (opsional, mis. rail vertikal 88px).
 */
$korinBerkatLogo = [
    'light' => '/images/logo/Logo-KorinBerkatEnergy.png',
    'dark' => '/images/logo/Logo-KorinBerkatEnergy-light.png',
    'compact' => '/images/logo/Logo-KorinBerkatEnergy-compact.png',
    'width' => 559,
    'height' => 446,
    'alt' => 'PT Korin Berkat Energy',
];

return [
    'logos' => [
        'holding' => [
            'light' => '/images/logo/Logo-DercoBaraEnergy.png',
            'dark' => '/images/logo/Logo-DercoBaraEnergy-light.png',
            'compact' => '/images/logo/Logo-DercoBaraEnergy-compact.png',
            'width' => 676,
            'height' => 369,
            'alt' => 'PT Derco Bara Energy',
        ],
        'korin-berkat' => $korinBerkatLogo,
        'korin-bara' => [
            ...$korinBerkatLogo,
            'alt' => 'PT Korin Bara Energy',
        ],
        'beramal' => [
            'light' => '/images/logo/Logo-BeramalSuksesSejahtera.png',
            'dark' => '/images/logo/Logo-BeramalSuksesSejahtera-light.png',
            'compact' => '/images/logo/Logo-BeramalSuksesSejahtera-compact.png',
            'width' => 693,
            'height' => 360,
            'alt' => 'PT Beramal Sukses Sejahtera',
        ],
    ],
];
