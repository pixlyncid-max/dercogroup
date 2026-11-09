<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Company\Enums\CompanyStatus;
use App\Domain\Company\Enums\CompanyTheme;
use App\Domain\Company\Models\Company;
use App\Domain\Page\Actions\PageResolver;
use App\Domain\Page\Enums\PageKey;
use App\Filament\Resources\CompanyResource\Pages\ManageCompanies;
use App\Support\FilamentTranslatable;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|UnitEnum|null $navigationGroup = 'Struktur & Halaman';

    protected static ?string $modelLabel = 'Perusahaan';

    protected static ?string $pluralModelLabel = 'Perusahaan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('parent_id')
                            ->label('Induk Usaha (Kosongkan untuk Holding)')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('theme')
                            ->label('Tema Visual')
                            ->options(CompanyTheme::options())
                            ->required(),
                        Select::make('status')
                            ->label('Status Tayang')
                            ->options(CompanyStatus::options())
                            ->default(CompanyStatus::Draft->value)
                            ->disabled(fn (): bool => ! (auth()->user()?->isSuperAdmin() ?? false))
                            ->dehydrated()
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textarea('tagline', 'Tagline Singkat', true, 2),
                FilamentTranslatable::textarea('about_short', 'Ringkasan Tentang Perusahaan', true, 3),
                FileUpload::make('logo_upload')
                    ->label('Unggah Logo (Opsional)')
                    ->image()
                    ->disk('public')
                    ->directory('logos')
                    ->dehydrated(false),
                FilamentTranslatable::textInput('logo_alt', 'Teks Alternatif Logo (Wajib untuk Aksesibilitas)', true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge(),
                TextColumn::make('theme')
                    ->label('Tema')
                    ->formatStateUsing(fn (CompanyTheme|string $state): string => $state instanceof CompanyTheme ? $state->label() : (string) $state),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (CompanyStatus|string $state): string => ($state instanceof CompanyStatus ? $state : CompanyStatus::from((string) $state)) === CompanyStatus::Published ? 'success' : 'warning')
                    ->formatStateUsing(fn (CompanyStatus|string $state): string => ($state instanceof CompanyStatus ? $state : CompanyStatus::from((string) $state))->label()),
                TextColumn::make('missing_en')
                    ->label('Status EN')
                    ->state(fn (Company $record): string => $record->hasMissingTranslation('en') ? 'Belum diterjemahkan' : 'Lengkap')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Lengkap' ? 'success' : 'warning'),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Tayang')
                    ->options(CompanyStatus::options()),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('Pratinjau')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(function (Company $record): string {
                        $homePage = $record->pages()->where('key', PageKey::Home->value)->first();
                        if ($homePage === null) {
                            return url('/id?preview=1');
                        }

                        return url(app(PageResolver::class)->pageUrl($record, $homePage, 'id', true));
                    })
                    ->openUrlInNewTab(),
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
            'index' => ManageCompanies::route('/'),
        ];
    }
}
