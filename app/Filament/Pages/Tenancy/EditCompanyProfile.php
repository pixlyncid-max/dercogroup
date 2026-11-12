<?php

declare(strict_types=1);

namespace App\Filament\Pages\Tenancy;

use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Company\Services\CompanySettingsReader;
use App\Support\FilamentTranslatable;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class EditCompanyProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Profil & Pengaturan Perusahaan';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Perusahaan')
                    ->description('Informasi utama profil dan tema entitas perusahaan.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Perusahaan')
                                    ->required()
                                    ->maxLength(150),
                                TextInput::make('slug')
                                    ->label('Slug URL Perusahaan')
                                    ->disabled()
                                    ->helperText('Slug otomatis mengikuti pengenal resmi perusahaan.'),
                            ]),
                        FilamentTranslatable::textInput('tagline', 'Tagline / Slogan Perusahaan', false),
                        FilamentTranslatable::textarea('about_short', 'Deskripsi Singkat Profil', false, 3),
                    ]),
                Section::make('Pengaturan Kontak & Notifikasi Formulir')
                    ->description('Alamat email penerima notifikasi saat ada pesan masuk pada situs.')
                    ->schema([
                        TextInput::make('contact_email')
                            ->label('Email Notifikasi Pesan Masuk')
                            ->email()
                            ->maxLength(150)
                            ->placeholder('corsec@dercobara.co.id')
                            ->helperText('Pemberitahuan pesan masuk akan dikirim ke alamat email ini (jika kosong, menggunakan email default grup).'),
                    ]),
                Section::make('Pengaturan SEO Bawaan (Default Metadata)')
                    ->description('Nilai SEO bawaan untuk halaman-halaman yang tidak menentukan SEO khusus.')
                    ->schema([
                        TextInput::make('seo_title')
                            ->label('Judul SEO Bawaan (Meta Title)')
                            ->maxLength(200),
                        Textarea::make('seo_description')
                            ->label('Deskripsi SEO Bawaan (Meta Description)')
                            ->rows(2)
                            ->maxLength(300),
                        FileUpload::make('seo_og_image')
                            ->label('Gambar Berbagi Sosial (Open Graph Image)')
                            ->image()
                            ->disk('public')
                            ->directory('seo')
                            ->helperText('Disarankan rasio 1200x630px untuk pratinjau media sosial.'),
                    ]),
                Section::make('Konfigurasi Sistem & Tema (Hanya Super Admin)')
                    ->description('Pengaturan status publikasi dan tema desain hanya dapat diubah oleh Super Admin.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->label('Status Publikasi')
                                    ->options(CompanyStatus::class)
                                    ->required()
                                    ->disabled(fn () => ! auth()->user()?->isSuperAdmin())
                                    ->helperText('Perusahaan dengan status Draft tidak akan tampil ke publik.'),
                                Select::make('theme')
                                    ->label('Tema Visual & Desain')
                                    ->options(CompanyTheme::class)
                                    ->required()
                                    ->disabled(fn () => ! auth()->user()?->isSuperAdmin())
                                    ->helperText('Set warna, tipografi, dan tata letak khas perusahaan.'),
                            ]),
                    ]),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Company $tenant */
        $tenant = $this->tenant;
        $reader = app(CompanySettingsReader::class);

        $contactSetting = $reader->get('contact_default_email', null, $tenant);
        $data['contact_email'] = is_array($contactSetting) ? ($contactSetting['email'] ?? null) : null;

        $seoTitleSetting = $reader->get('seo_default_title', null, $tenant);
        $data['seo_title'] = is_array($seoTitleSetting) ? ($seoTitleSetting['title'] ?? null) : null;

        $seoDescSetting = $reader->get('seo_default_description', null, $tenant);
        $data['seo_description'] = is_array($seoDescSetting) ? ($seoDescSetting['description'] ?? null) : null;

        $ogSetting = $reader->get('seo_og_image', null, $tenant);
        $data['seo_og_image'] = is_array($ogSetting) ? ($ogSetting['path'] ?? null) : null;

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! auth()->user()?->isSuperAdmin()) {
            unset($data['status'], $data['theme']);
        }

        $reader = app(CompanySettingsReader::class);

        if (array_key_exists('contact_email', $data)) {
            $reader->set('contact_default_email', ['email' => $data['contact_email']], 'contact', $record);
            unset($data['contact_email']);
        }

        if (array_key_exists('seo_title', $data)) {
            $reader->set('seo_default_title', ['title' => $data['seo_title']], 'seo', $record);
            unset($data['seo_title']);
        }

        if (array_key_exists('seo_description', $data)) {
            $reader->set('seo_default_description', ['description' => $data['seo_description']], 'seo', $record);
            unset($data['seo_description']);
        }

        if (array_key_exists('seo_og_image', $data)) {
            $reader->set('seo_og_image', ['path' => $data['seo_og_image']], 'seo', $record);
            unset($data['seo_og_image']);
        }

        $record->update($data);

        return $record;
    }
}
