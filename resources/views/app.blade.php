<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'PT Derco Bara Energy') }}</title>

        @php
            $manifestPath = public_path('build/manifest.json');
            $preloadFonts = [];
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true) ?: [];
                foreach ($manifest as $key => $entry) {
                    if (isset($entry['file']) && str_ends_with($entry['file'], '.woff2') && str_contains($entry['file'], 'latin-wght-normal')) {
                        $preloadFonts[] = asset('build/' . $entry['file']);
                    }
                }
            }
        @endphp
        @foreach(array_slice($preloadFonts, 0, 2) as $fontUrl)
            <link rel="preload" href="{{ $fontUrl }}" as="font" type="font/woff2" crossorigin>
        @endforeach

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        @inertiaHead
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>
