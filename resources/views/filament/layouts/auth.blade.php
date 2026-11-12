@props([
    'livewire' => null,
])

<x-filament-panels::layout.base :livewire="$livewire">
    <main class="cms-login-viewport min-h-screen w-full flex flex-col lg:flex-row bg-[#EFF1F7]">
        <!-- Left Side: Mining Banner & Corporate Identity -->
        <div class="cms-login-banner relative hidden lg:flex lg:w-1/2 flex-col justify-between p-12 xl:p-16 bg-slate-950 overflow-hidden">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center"
                 style="background-image: url('{{ asset('images/cms/login-mining-banner.jpg') }}');"></div>

            <!-- Dark gradient overlay for text readability -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-950/60 to-transparent"></div>

            <!-- Top Company Logo -->
            <div class="relative z-10">
                <img src="{{ asset(config('branding.logos.korin-berkat.dark', '/images/logo/Logo-KorinBerkatEnergy-light.png')) }}"
                     alt="Korin Berkat Energy"
                     class="h-12 w-auto object-contain" />
            </div>

            <!-- Middle Core Headline -->
            <div class="relative z-10 max-w-lg space-y-4 mb-16">
                <h1 class="text-4xl xl:text-5xl font-extrabold text-white leading-tight font-heading">
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

            <!-- Decorative bottom footer space -->
            <div class="relative z-10 text-xs text-slate-400">
                PT Derco Bara Energy Corporate Group
            </div>
        </div>

        <!-- Right Side: Clean White Login Card Container -->
        <div class="cms-login-form-side flex-1 flex flex-col justify-center items-center p-6 sm:p-12 bg-white lg:bg-[#F8FAFC]">
            <div class="w-full max-w-[440px] bg-white rounded-3xl p-8 sm:p-10 lg:border lg:border-slate-200/80 lg:shadow-xl lg:shadow-slate-200/50">
                <!-- Card Header -->
                <div class="text-center space-y-2 mb-8">
                    <img src="{{ asset(config('branding.logos.korin-berkat.light', '/images/logo/Logo-KorinBerkatEnergy.png')) }}"
                         alt="Korin Berkat Energy"
                         class="h-11 w-auto mx-auto object-contain mb-4" />
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
        </div>
    </main>
</x-filament-panels::layout.base>
