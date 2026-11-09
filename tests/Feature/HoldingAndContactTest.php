<?php

declare(strict_types=1);

use App\Domain\Company\Models\Company;
use App\Domain\Contact\Enums\ContactMessageStatus;
use App\Domain\Contact\Mail\ContactMessageReceivedMail;
use App\Domain\Contact\Models\ContactMessage;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
    RateLimiter::clear('127.0.0.1');
});

it('renders all 4 holding pages in Indonesian and English via ThemedPage with localized block links', function (): void {
    $this->get('/id')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'id')
            ->where('company.slug', 'derco-bara-energy')
            ->where('page.key', 'home')
            ->has('blocks', 6)
            ->has('subsidiaries', 2)
            ->where('blocks.0.data.primary_cta_href', '/id/bisnis-kami')
        );

    $this->get('/en')
        ->assertOk()
        ->assertInertia(fn (Assert $page): Assert => $page
            ->component('ThemedPage')
            ->where('locale', 'en')
            ->where('company.slug', 'derco-bara-energy')
            ->where('page.key', 'home')
            ->has('blocks', 6)
            ->has('subsidiaries', 2)
            ->where('blocks.0.data.primary_cta_href', '/en/our-business')
        );

    $this->get('/id/tentang-kami')->assertOk();
    $this->get('/en/about-us')->assertOk();
    $this->get('/id/bisnis-kami')->assertOk();
    $this->get('/en/our-business')->assertOk();
    $this->get('/id/kontak')->assertOk();
    $this->get('/en/contact')->assertOk();
});

it('stores a valid contact message with company_id and locale and queues notification email', function (): void {
    Mail::fake();

    $holding = Company::query()->where('slug', 'derco-bara-energy')->firstOrFail();

    $response = $this->from('/id/kontak')->post('/id/contact-messages', [
        'name' => 'Budi Santoso',
        'email' => 'budi.santoso@industri-energi.co.id',
        'phone' => '+62 811 9000 123',
        'organization' => 'PT Industri Energi Nusantara',
        'subject' => 'Permintaan Spesifikasi Batu Bara GAR 4200',
        'message' => 'Kami membutuhkan pasokan rutin 50.000 MT per bulan untuk pembangkit di Jawa Barat.',
        'website' => '',
    ]);

    $response->assertRedirect('/id/kontak')
        ->assertSessionHas('contactSuccess', true);

    $message = ContactMessage::query()->first();
    expect($message)->not->toBeNull()
        ->and($message?->company_id)->toBe($holding->id)
        ->and($message?->locale)->toBe('id')
        ->and($message?->name)->toBe('Budi Santoso')
        ->and($message?->status)->toBe(ContactMessageStatus::New);

    Mail::assertQueued(ContactMessageReceivedMail::class, function (ContactMessageReceivedMail $mail) use ($holding): bool {
        return $mail->company->id === $holding->id
            && $mail->contactMessage->email === 'budi.santoso@industri-energi.co.id';
    });
});

it('rejects invalid contact form submissions with validation errors', function (): void {
    Mail::fake();

    $response = $this->from('/id/kontak')->post('/id/contact-messages', [
        'name' => '',
        'email' => 'not-an-email',
        'subject' => '',
        'message' => 'short',
    ]);

    $response->assertRedirect('/id/kontak')
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

    expect(ContactMessage::query()->count())->toBe(0);
    Mail::assertNothingQueued();
});

it('silently drops honeypot spam submissions without saving or sending email', function (): void {
    Mail::fake();

    $response = $this->from('/en/contact')->post('/en/contact-messages', [
        'name' => 'Spam Bot',
        'email' => 'bot@spam.example',
        'subject' => 'Automated spam',
        'message' => 'This message has the hidden website honeypot field filled.',
        'website' => 'https://spam-link.example',
    ]);

    $response->assertRedirect('/en/contact')
        ->assertSessionHas('contactSuccess', true);

    expect(ContactMessage::query()->count())->toBe(0);
    Mail::assertNothingQueued();
});

it('enforces the 5 messages per hour per IP rate limit on contact form submissions', function (): void {
    Mail::fake();

    $payload = [
        'name' => 'Rina Wijaya',
        'email' => 'rina@mitra-logistik.co.id',
        'subject' => 'Kemitraan Pengapalan Tongkang',
        'message' => 'Permintaan informasi slot sandar jetty dan kapasitas muat tongkang 300 kaki.',
        'website' => '',
    ];

    for ($i = 1; $i <= 5; $i++) {
        $this->post('/id/contact-messages', $payload)->assertRedirect();
    }

    expect(ContactMessage::query()->count())->toBe(5);

    $this->post('/id/contact-messages', $payload)->assertTooManyRequests();
});
