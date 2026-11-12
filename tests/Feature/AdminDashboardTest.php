<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Compliance\Models\Certification;
use App\Domain\Contact\Models\CompanyContact;
use App\Domain\Contact\Models\ContactMessage;
use App\Domain\Operation\Models\Operation;
use App\Domain\Page\Models\Page;
use App\Domain\Product\Models\Product;
use App\Filament\Widgets\DashboardGreetingWidget;
use App\Filament\Widgets\HeroBannerWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\WebsiteOverviewWidget;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');
        $response->assertOk();
        $response->assertSee('Welcome Back');
        $response->assertSee('Building a Sustainable Future');
    }

    public function test_super_admin_can_load_admin_dashboard(): void
    {
        $superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();

        $response = $this->actingAs($superAdmin)->get('/admin');
        $response->assertOk();
        $response->assertSee('Good');
        $response->assertSee('Manage your website content');
    }

    public function test_dashboard_widgets_render_with_real_database_metrics(): void
    {
        $superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->actingAs($superAdmin);

        // 1. Dashboard Greeting Widget
        Livewire::test(DashboardGreetingWidget::class)
            ->assertSee('Manage your website content');

        // 2. Stats Overview Widget
        Livewire::test(StatsOverviewWidget::class)
            ->assertSee('Total Pages')
            ->assertSee((string) Page::count())
            ->assertSee('Total Products')
            ->assertSee((string) Product::count())
            ->assertSee('Total Operations')
            ->assertSee((string) Operation::count())
            ->assertSee('Total Users')
            ->assertSee((string) User::count());

        // 3. Hero Banner Widget
        Livewire::test(HeroBannerWidget::class)
            ->assertSee('Korin Berkat Energy')
            ->assertSee('Mining · Trading · Nickel');

        // 4. Quick Actions Widget
        Livewire::test(QuickActionsWidget::class)
            ->assertSee('Quick Actions')
            ->assertSee('Add Product')
            ->assertSee('Manage Pages');

        // 5. Website Overview Widget
        Livewire::test(WebsiteOverviewWidget::class)
            ->assertSee('Website Overview')
            ->assertSee('28,540')
            ->assertSee('Last 30 Days');

        // 6. Recent Activity Widget
        Livewire::test(RecentActivityWidget::class)
            ->assertSee('Recent Activity');
    }

    public function test_every_filament_resource_loads_without_error(): void
    {
        $superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->actingAs($superAdmin);

        $resources = [
            'companies' => Company::first()?->getKey(),
            'pages' => Page::first()?->getKey(),
            'products' => Product::first()?->getKey(),
            'operations' => Operation::first()?->getKey(),
            'company-stats' => null,
            'certifications' => Certification::first()?->getKey(),
            'contact-messages' => ContactMessage::first()?->getKey(),
            'company-contacts' => CompanyContact::first()?->getKey(),
            'users' => User::first()?->getKey(),
            'settings' => null,
        ];

        foreach ($resources as $slug => $sampleId) {
            // Index / List page
            $listResponse = $this->get("/admin/{$slug}");
            $listResponse->assertOk();

            // Create page (if resource supports separate create route)
            $createResponse = $this->get("/admin/{$slug}/create");
            if ($createResponse->status() !== 404) {
                $createResponse->assertOk();
            }

            // Edit page (if resource supports separate edit route and sample ID exists)
            if ($sampleId) {
                $editResponse = $this->get("/admin/{$slug}/{$sampleId}/edit");
                if ($editResponse->status() !== 404) {
                    $editResponse->assertOk();
                }
            }
        }
    }
}
