<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Company\Traits\HasAdaptiveTenantNavigation;
use App\Domain\Compliance\Enums\CertificationCategory;
use App\Domain\Compliance\Models\Certification;
use App\Domain\Page\Enums\PageKey;
use App\Filament\Resources\CertificationResource\Pages\ManageCertifications;
use App\Support\FilamentTranslatable;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CertificationResource extends Resource
{
    use HasAdaptiveTenantNavigation;

    protected static ?string $model = Certification::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|UnitEnum|null $navigationGroup = 'Konten Operasional';

    protected static ?string $modelLabel = 'Sertifikasi & Izin';

    protected static ?string $pluralModelLabel = 'Sertifikasi & Izin';

    protected static ?int $navigationSort = 4;

    public static function getRequiredPageKey(): PageKey
    {
        return PageKey::Compliance;
    }

    /**
     * Module is dormant per Revisi 6 Section 0 Item 9 (code preserved, hidden from CMS navigation).
     */
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id),
                Grid::make(3)
                    ->schema([
                        Select::make('category')
                            ->label('Kategori Dokumen')
                            ->options(CertificationCategory::options())
                            ->required(),
                        TextInput::make('number')
                            ->label('Nomor Sertifikat / SK')
                            ->maxLength(120),
                        DatePicker::make('issued_at')
                            ->label('Tanggal Terbit'),
                        DatePicker::make('expires_at')
                            ->label('Masa Berlaku (Kosongkan jika Permanen)'),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textInput('name', 'Nama Sertifikat / Izin / Kebijakan', true),
                FilamentTranslatable::textInput('issuer', 'Lembaga Penerbit', true),
                Grid::make(2)
                    ->schema([
                        FileUpload::make('file_path_id')
                            ->label('Berkas PDF (Bahasa Indonesia)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->disk('public')
                            ->directory('documents'),
                        FileUpload::make('file_path_en')
                            ->label('Berkas PDF (English)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->disk('public')
                            ->directory('documents'),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn (CertificationCategory|string $state): string => $state instanceof CertificationCategory ? $state->label('id') : (string) $state),
                TextColumn::make('name_id')
                    ->label('Nama Dokumen')
                    ->state(fn (Certification $record): string => $record->translateField('name', 'id')),
                TextColumn::make('number')
                    ->label('Nomor')
                    ->placeholder('-'),
                TextColumn::make('expires_at')
                    ->label('Masa Berlaku')
                    ->date('d M Y')
                    ->placeholder('Permanen'),
                TextColumn::make('validity_status')
                    ->label('Status Masa Berlaku')
                    ->state(fn (Certification $record): string => $record->isExpired() ? 'Kedaluwarsa' : 'Aktif')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Aktif' ? 'success' : 'danger'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(CertificationCategory::options()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCertifications::route('/'),
        ];
    }
}
