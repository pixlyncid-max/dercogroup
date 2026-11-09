<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('theme', 50);
            $table->json('tagline')->nullable();
            $table->json('about_short')->nullable();
            $table->json('logo_alt')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->string('status', 30)->default('draft')->index();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('key', 50);
            $table->json('title');
            $table->json('slug');
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->boolean('show_in_nav')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();

            $table->unique(['company_id', 'key']);
        });

        Schema::create('page_blocks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('type', 50)->index();
            $table->json('data');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->index(['page_id', 'sort_order']);
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('slug');
            $table->json('name');
            $table->json('category_label')->nullable();
            $table->json('summary')->nullable();
            $table->json('description')->nullable();
            $table->json('image_alt')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();

            $table->unique(['company_id', 'slug']);
        });

        Schema::create('product_specs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('code', 30)->nullable();
            $table->json('label');
            $table->string('value');
            $table->string('unit', 50)->nullable();
            $table->boolean('is_highlight')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'sort_order']);
        });

        Schema::create('operations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('type', 50)->index();
            $table->json('name');
            $table->json('location_label')->nullable();
            $table->json('description')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('image_alt')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('company_stats', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->decimal('value', 14, 2);
            $table->string('prefix', 20)->nullable();
            $table->string('suffix', 20)->nullable();
            $table->json('unit')->nullable();
            $table->json('label');
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('certifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('category', 50)->index();
            $table->json('name');
            $table->json('issuer');
            $table->string('number', 120)->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable()->index();
            $table->string('file_path_id')->nullable();
            $table->string('file_path_en')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('company_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('type', 50)->default('head_office')->index();
            $table->json('label')->nullable();
            $table->json('address')->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('notify_email', 150)->nullable();
            $table->string('whatsapp', 60)->nullable();
            $table->json('operating_hours')->nullable();
            $table->json('socials')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->string('locale', 5)->default('id');
            $table->string('name', 150);
            $table->string('email', 150);
            $table->string('phone', 60)->nullable();
            $table->string('organization', 150)->nullable();
            $table->string('subject', 200);
            $table->text('message');
            $table->string('status', 30)->default('new')->index();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('group', 50)->default('general')->index();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->string('event', 30);
            $table->json('changes')->nullable();
            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('company_contacts');
        Schema::dropIfExists('certifications');
        Schema::dropIfExists('company_stats');
        Schema::dropIfExists('operations');
        Schema::dropIfExists('product_specs');
        Schema::dropIfExists('products');
        Schema::dropIfExists('page_blocks');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('companies');
    }
};
