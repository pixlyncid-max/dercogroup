<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Company\Models\Company;
use App\Domain\Company\Models\CompanyStat;
use App\Domain\Company\Models\Setting;
use App\Domain\Compliance\Models\Certification;
use App\Domain\Contact\Models\CompanyContact;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Operation\Models\Operation;
use App\Domain\Page\Models\Page;
use App\Domain\Product\Models\Product;
use App\Models\User;
use App\Policies\CertificationPolicy;
use App\Policies\CompanyContactPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\CompanyStatPolicy;
use App\Policies\ContactMessagePolicy;
use App\Policies\OperationPolicy;
use App\Policies\PagePolicy;
use App\Policies\ProductPolicy;
use App\Policies\SettingPolicy;
use App\Policies\UserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (request()->header('X-Forwarded-Proto') === 'https' || request()->isSecure() || str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('contact-submissions', function (Request $request): Limit {
            if ($request->hasHeader('X-E2E-Test')) {
                return Limit::none();
            }

            return Limit::perHour(5)->by((string) $request->ip());
        });

        RateLimiter::for('admin-login', function (Request $request): Limit {
            return Limit::perMinute(5)->by((string) $request->ip());
        });

        Gate::before(function ($user, string $ability, array $args = []): ?bool {
            if (in_array($ability, ['delete', 'deleteAny', 'forceDelete'], true)) {
                return null;
            }

            if ($user instanceof User && $user->isSuperAdmin()) {
                return true;
            }

            return null;
        });

        Gate::policy(Page::class, PagePolicy::class);
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Setting::class, SettingPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Operation::class, OperationPolicy::class);
        Gate::policy(Certification::class, CertificationPolicy::class);
        Gate::policy(CompanyContact::class, CompanyContactPolicy::class);
        Gate::policy(CompanyStat::class, CompanyStatPolicy::class);
        Gate::policy(ContactMessage::class, ContactMessagePolicy::class);
    }
}
