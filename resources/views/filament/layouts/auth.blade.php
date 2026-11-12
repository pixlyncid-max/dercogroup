@props([
    'livewire' => null,
])

<x-filament-panels::layout.base :livewire="$livewire">
    {{-- Ensure theme stylesheet is loaded via root-relative URL, immune to APP_URL localhost issues --}}
    <link rel="stylesheet" href="/css/filament/admin/theme.css?v={{ @filemtime(public_path('css/filament/admin/theme.css')) ?: 1 }}" />

    {{-- Embedded Self-Contained Critical CSS for instantaneous zero-CLS layout rendering --}}
    <style>
        .cms-login-viewport {
            min-height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: row;
            background-color: #F8FAFC;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            overflow-x: hidden;
        }

        .cms-login-banner {
            position: relative;
            width: 50%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem 4rem;
            background-color: #0B1528;
            overflow: hidden;
            box-sizing: border-box;
        }

        @media (max-width: 1023px) {
            .cms-login-banner {
                display: none !important;
            }
        }

        .cms-login-banner-bg {
            position: absolute;
            inset: 0;
            background-image: url('/images/cms/login-mining-banner.jpg');
            background-size: cover;
            background-position: center;
            z-index: 1;
        }

        .cms-login-banner-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(11, 21, 40, 0.88) 0%, rgba(11, 21, 40, 0.65) 50%, rgba(11, 21, 40, 0.35) 100%);
            z-index: 2;
        }

        .cms-login-banner-content {
            position: relative;
            z-index: 10;
        }

        .cms-login-form-side {
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem 1.5rem;
            background-color: #F8FAFC;
            box-sizing: border-box;
        }

        .cms-login-card {
            width: 100%;
            max-width: 440px;
            background-color: #ffffff;
            border-radius: 1.5rem;
            padding: 2.5rem 2.25rem;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.05), 0 8px 10px -6px rgba(15, 23, 42, 0.02);
            border: 1px solid #E2E8F0;
            box-sizing: border-box;
        }

        .cms-login-logo {
            max-height: 48px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
            display: block;
            margin: 0 auto 1.25rem auto;
        }

        .cms-banner-logo {
            max-height: 44px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
            display: block;
        }

        /* Form styling resilience */
        .cms-login-card .fi-btn-primary,
        .cms-login-card button[type="submit"] {
            background-color: #122A44 !important;
            color: #ffffff !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            padding: 0.75rem 1.25rem !important;
            width: 100% !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 4px 6px -1px rgba(18, 42, 68, 0.15) !important;
            transition: all 0.2s ease !important;
        }

        .cms-login-card .fi-btn-primary:hover,
        .cms-login-card button[type="submit"]:hover {
            background-color: #0c1d30 !important;
            transform: translateY(-1px);
        }

        .cms-login-card input[type="text"],
        .cms-login-card input[type="email"],
        .cms-login-card input[type="password"] {
            border-radius: 0.75rem !important;
            border: 1px solid #E2E8F0 !important;
            padding: 0.65rem 0.875rem !important;
            font-size: 0.875rem !important;
            width: 100% !important;
            background-color: #ffffff !important;
            box-sizing: border-box !important;
        }

        .cms-login-card input[type="text"]:focus,
        .cms-login-card input[type="email"]:focus,
        .cms-login-card input[type="password"]:focus {
            border-color: #122A44 !important;
            outline: 2px solid rgba(18, 42, 68, 0.15) !important;
        }

        .cms-login-card .fi-fo-field-wrp-label {
            display: none !important; /* Icons and placeholders act as visual cues matching reference */
        }
    </style>

    <main class="cms-login-viewport">
        <!-- Left Side: Mining Banner & Corporate Identity (Desktop) -->
        <aside class="cms-login-banner" aria-label="Company Overview">
            <div class="cms-login-banner-bg" aria-hidden="true"></div>
            <div class="cms-login-banner-overlay" aria-hidden="true"></div>

            <!-- Top Company Logo -->
            <div class="cms-login-banner-content">
                <img src="/images/cms/korin-logo-light.png"
                     alt="PT Korin Berkat Energy"
                     class="cms-banner-logo"
                     width="180"
                     height="44" />
            </div>

            <!-- Middle Core Headline -->
            <div class="cms-login-banner-content max-w-lg space-y-4 mb-12">
                <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight font-heading tracking-tight">
                    Building a Sustainable Future <span class="text-[#EDB856]">Together</span>
                </h1>
                <p class="text-base font-bold text-slate-100 flex items-center gap-2">
                    <span>Mining</span>
                    <span class="text-[#EDB856]">•</span>
                    <span>Trading</span>
                    <span class="text-[#EDB856]">•</span>
                    <span>Nickel</span>
                </p>
                <p class="text-sm text-slate-300 leading-relaxed max-w-md">
                    Committed to responsible mining, reliable trading, and a greener tomorrow.
                </p>
            </div>

            <!-- Bottom Sub-branding -->
            <div class="cms-login-banner-content text-xs text-slate-400">
                PT Derco Bara Energy Corporate Group
            </div>
        </aside>

        <!-- Right Side: Clean White Login Card Container -->
        <section class="cms-login-form-side" aria-label="Login Form">
            <div class="cms-login-card">
                <!-- Card Header -->
                <div class="text-center space-y-2 mb-6">
                    <img src="/images/cms/korin-logo-dark.png"
                         alt="PT Korin Berkat Energy"
                         class="cms-login-logo"
                         width="190"
                         height="44" />
                    <h2 class="text-2xl font-bold text-slate-900 font-heading">Welcome Back</h2>
                    <p class="text-xs sm:text-sm text-slate-500">Please login to access your dashboard</p>
                </div>

                <!-- Filament Livewire Form -->
                {{ $slot }}

                <!-- SSO Alternative Option (Matching Reference Visual) -->
                <div class="mt-6 space-y-4">
                    <div class="relative flex items-center justify-center">
                        <div class="border-t border-slate-200 w-full"></div>
                        <span class="bg-white px-3 text-xs text-slate-400 font-medium uppercase tracking-wider relative">or</span>
                    </div>

                    <button type="button"
                            aria-label="Login with SSO"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Login with SSO</span>
                    </button>
                </div>

                <!-- Footer Copyright -->
                <p class="mt-8 text-center text-xs text-slate-400">
                    &copy; 2025 Korin Berkat Energy. All rights reserved.
                </p>
            </div>
        </section>
    </main>
</x-filament-panels::layout.base>
