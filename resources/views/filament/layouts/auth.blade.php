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
            background-color: #E2E8F0;
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
            background-image: url('/images/cms/derco-login-banner.jpg?v={{ @filemtime(public_path('images/cms/derco-login-banner.jpg')) ?: 1 }}');
            background-size: cover;
            background-position: left center;
            background-repeat: no-repeat;
            z-index: 1;
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
            max-height: 48px;
            width: auto;
            max-width: 220px;
            object-fit: contain;
            display: block;
        }

        /* Complete Login Card Form Layout & Styling */
        .cms-login-card form {
            width: 100% !important;
            margin: 0 !important;
        }

        .cms-login-card .fi-grid {
            display: flex !important;
            flex-direction: column !important;
            width: 100% !important;
            gap: 1.125rem !important;
            grid-template-columns: none !important;
        }

        .cms-login-card .fi-grid-col {
            width: 100% !important;
            min-width: 100% !important;
            grid-column: auto !important;
        }

        .cms-login-card .fi-sc-component {
            width: 100% !important;
            min-width: 100% !important;
        }

        .cms-login-card .fi-fo-field {
            width: 100% !important;
        }

        /* Hide text labels on email and password (placeholders act as visual cues matching reference) */
        .cms-login-card .fi-fo-field:not(:has(input[type="checkbox"])) .fi-fo-field-label-col {
            display: none !important;
        }

        /* Input field container styling */
        .cms-login-card .fi-input-wrp {
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            background-color: #ffffff !important;
            border: 1px solid #E2E8F0 !important;
            border-radius: 0.75rem !important;
            padding: 0.25rem 0.625rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03) !important;
            transition: all 0.2s ease !important;
            box-sizing: border-box !important;
        }

        .cms-login-card .fi-input-wrp:focus-within {
            border-color: #122A44 !important;
            box-shadow: 0 0 0 2px rgba(18, 42, 68, 0.12) !important;
        }

        .cms-login-card .fi-input-wrp-prefix svg {
            color: #94a3b8 !important;
            width: 1.125rem !important;
            height: 1.125rem !important;
            margin-right: 0.375rem !important;
        }

        .cms-login-card .fi-input-wrp input[type="text"],
        .cms-login-card .fi-input-wrp input[type="email"],
        .cms-login-card .fi-input-wrp input[type="password"] {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
            background: transparent !important;
            width: 100% !important;
            padding: 0.5rem 0.375rem !important;
            font-size: 0.875rem !important;
            color: #0f172a !important;
            line-height: 1.5 !important;
        }

        .cms-login-card .fi-input-wrp input::placeholder {
            color: #94a3b8 !important;
        }

        /* Remember Me & Forgot Password Row */
        .cms-login-card .fi-fo-field:has(input[type="checkbox"]) {
            width: 100% !important;
            margin-top: -0.25rem !important;
            margin-bottom: 0.25rem !important;
        }

        .cms-login-card .fi-fo-field:has(input[type="checkbox"]) .fi-fo-field-label-col {
            width: 100% !important;
        }

        .cms-login-card .fi-fo-field:has(input[type="checkbox"]) .fi-fo-field-label-ctn {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            width: 100% !important;
        }

        .cms-login-card label.fi-fo-field-label {
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
            cursor: pointer !important;
            font-size: 0.8125rem !important;
            font-weight: 500 !important;
            color: #64748b !important;
            user-select: none !important;
        }

        .cms-login-card input[type="checkbox"] {
            width: 1rem !important;
            height: 1rem !important;
            border-radius: 0.25rem !important;
            border: 1px solid #cbd5e1 !important;
            color: #122A44 !important;
            cursor: pointer !important;
        }

        .cms-login-card .fi-fo-field-hint-ctn a,
        .cms-login-card a.fi-link {
            color: #EDB856 !important;
            font-size: 0.8125rem !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: color 0.15s ease !important;
        }

        .cms-login-card .fi-fo-field-hint-ctn a:hover,
        .cms-login-card a.fi-link:hover {
            color: #D49E35 !important;
        }

        /* Submit Button */
        .cms-login-card .fi-btn-primary,
        .cms-login-card button[type="submit"] {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0.5rem !important;
            background-color: #122A44 !important;
            color: #ffffff !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            font-size: 0.875rem !important;
            padding: 0.75rem 1.25rem !important;
            width: 100% !important;
            min-height: 2.75rem !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 4px 6px -1px rgba(18, 42, 68, 0.15) !important;
            transition: all 0.2s ease !important;
            box-sizing: border-box !important;
        }

        .cms-login-card .fi-btn-primary:hover,
        .cms-login-card button[type="submit"]:hover {
            background-color: #0c1d30 !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 10px -1px rgba(18, 42, 68, 0.2) !important;
        }
    </style>

    <main class="cms-login-viewport">
        <!-- Left Side: Mining Banner & Corporate Identity (Desktop) -->
        <aside class="cms-login-banner" aria-label="PT Derco Bara Energy Corporate Overview">
            <div class="cms-login-banner-bg" aria-hidden="true"></div>

            <!-- Accessible semantic content for screen readers, SEO, and tests without visual duplication -->
            <div class="sr-only">
                <img src="/images/cms/derco-logo-dark.png" alt="PT Derco Bara Energy" />
                <h1>Building a Sustainable Future Together</h1>
                <p>Mining • Trading • Nickel</p>
                <p>Committed to responsible mining, reliable trading, and a greener tomorrow.</p>
                <p>PT Derco Bara Energy Corporate Group</p>
            </div>
        </aside>

        <!-- Right Side: Clean White Login Card Container -->
        <section class="cms-login-form-side" aria-label="Login Form">
            <div class="cms-login-card">
                <!-- Card Header -->
                <div class="text-center space-y-2 mb-6">
                    <img src="/images/cms/derco-logo-dark.png"
                         alt="PT Derco Bara Energy"
                         class="cms-login-logo"
                         width="200"
                         height="48"
                         style="max-height: 48px; width: auto; max-width: 220px; object-fit: contain; margin: 0 auto 1.25rem auto;" />
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
                    &copy; {{ date('Y') }} PT Derco Bara Energy. All rights reserved.
                </p>
            </div>
        </section>
    </main>
</x-filament-panels::layout.base>
