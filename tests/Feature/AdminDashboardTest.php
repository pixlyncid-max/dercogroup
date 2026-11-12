<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Domain\Company\Models\Company;
use App\Domain\Page\Models\Page;
use App\Filament\Widgets\DashboardGreetingWidget;
use App\Filament\Widgets\GroupSummaryWidget;
use App\Filament\Widgets\HeroBannerWidget;
use App\Filament\Widgets\QuickActionsWidget;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\WebsiteOverviewWidget;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Vite;
use Illuminate\Foundation\ViteException;
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

    public function test_login_page_renders_successfully_even_when_vite_manifest_throws_exception(): void
    {
        $this->mock(Vite::class, function ($mock): void {
            $mock->shouldReceive('__invoke')
                ->andThrow(new ViteException('Unable to locate file in Vite manifest'));
        });

        $response = $this->get('/admin/login');
        $response->assertOk();
        $response->assertSee('Welcome Back');
        $response->assertSee('Building a Sustainable Future');
    }

    public function test_super_admin_can_load_admin_dashboard(): void
    {
        $superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();

        // Direct tenant URL
        $response = $this->actingAs($superAdmin)->get('/admin/derco-bara-energy');
        $response->assertOk();
        $response->assertSee('Good');
        $response->assertSee('Manage your website content');

        // Root /admin redirects to default tenant
        $redirectResponse = $this->actingAs($superAdmin)->get('/admin');
        $redirectResponse->assertRedirect('/admin/derco-bara-energy');
    }

    public function test_dashboard_widgets_render_with_real_database_metrics(): void
    {
        $superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->actingAs($superAdmin);

        $holding = Company::where('slug', 'derco-bara-energy')->firstOrFail();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Filament::setTenant($holding);

        // 1. Dashboard Greeting Widget
        Livewire::test(DashboardGreetingWidget::class)
            ->assertSee('Manage your website content');

        // 2. Stats Overview Widget
        Livewire::test(StatsOverviewWidget::class)
            ->assertSee('Total Halaman')
            ->assertSee('Total Produk')
            ->assertSee('Total Operasi')
            ->assertSee('Pesan Masuk Baru');

        // 3. Hero Banner Widget
        Livewire::test(HeroBannerWidget::class)
            ->assertSee('PT Derco Bara Energy')
            ->assertSee($holding->translateField('tagline', 'id'));

        // 4. Quick Actions Widget
        Livewire::test(QuickActionsWidget::class)
            ->assertSee('Quick Actions')
            ->assertSee('Kelola Halaman');

        // 5. Website Overview Widget
        Livewire::test(WebsiteOverviewWidget::class)
            ->assertSee('Website Overview')
            ->assertSee('28,540')
            ->assertSee('Last 30 Days');

        // 6. Recent Activity Widget
        Livewire::test(RecentActivityWidget::class)
            ->assertSee('Recent Activity');

        // 7. Group Summary Widget (Revisi 5)
        Livewire::test(GroupSummaryWidget::class)
            ->assertSee('Ringkasan Grup Perusahaan')
            ->assertSee('1 Admin Mengelola Semua');
    }

    public function test_every_filament_resource_loads_without_error(): void
    {
        $superAdmin = User::where('email', 'admin@dercobara.co.id')->firstOrFail();
        $this->actingAs($superAdmin);

        $resources = [
            'companies' => 'derco-bara-energy',
            'pages' => 'derco-bara-energy',
            'products' => 'korin-berkat-energy',
            'operations' => 'korin-berkat-energy',
            'company-stats' => 'derco-bara-energy',
            'certifications' => 'beramal-sukses-sejahtera',
            'contact-messages' => 'derco-bara-energy',
            'company-contacts' => 'derco-bara-energy',
            'users' => 'derco-bara-energy',
            'settings' => 'derco-bara-energy',
        ];

        foreach ($resources as $slug => $tenantSlug) {
            // Index / List page
            $listResponse = $this->get("/admin/{$tenantSlug}/{$slug}");
            $listResponse->assertOk();
        }
    }
}
