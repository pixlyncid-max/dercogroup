<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Page\Actions\PageResolver;
use App\Domain\Page\Blocks\BlockRegistry;
use App\Domain\Page\Enums\BlockType;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Page\Models\Page;
use App\Filament\Resources\PageResource\Pages\ManagePages;
use App\Support\FilamentTranslatable;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Struktur & Halaman';

    protected static ?string $modelLabel = 'Halaman & Blok';

    protected static ?string $pluralModelLabel = 'Halaman & Blok';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id),
                Grid::make(3)
                    ->schema([
                        Select::make('key')
                            ->label('Kunci Halaman')
                            ->options(PageKey::options())
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan Menu')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Toggle::make('show_in_nav')
                            ->label('Tampil di Menu Navigasi')
                            ->default(true),
                        Toggle::make('is_published')
                            ->label('Tayangkan Halaman')
                            ->default(true),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textInput('title', 'Judul Halaman', true),
                FilamentTranslatable::textInput('slug', 'Slug URL per Bahasa (Kosongkan untuk Beranda)', false),
                FilamentTranslatable::textInput('meta_title', 'SEO Meta Title', false),
                FilamentTranslatable::textarea('meta_description', 'SEO Meta Description', false, 2),
                Repeater::make('blocks')
                    ->label('Penyusun Blok Halaman (Drag untuk Urut Ulang)')
                    ->relationship('blocks')
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->label('Tipe Blok')
                                    ->options(BlockType::options())
                                    ->required()
                                    ->live(),
                                Toggle::make('is_visible')
                                    ->label('Tampilkan Blok')
                                    ->default(true),
                            ]),
                        Group::make()
                            ->schema(function (Get $get): array {
                                $type = $get('type');
                                if (! $type) {
                                    return [];
                                }

                                $typeValue = $type instanceof BlockType ? $type->value : (string) $type;
                                $blockClass = BlockRegistry::for($typeValue);

                                return $blockClass::filamentSchema('data');
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Kunci')
                    ->badge()
                    ->formatStateUsing(fn (PageKey|string $state): string => $state instanceof PageKey ? $state->value : (string) $state),
                TextColumn::make('title_id')
                    ->label('Judul (ID)')
                    ->state(fn (Page $record): string => $record->translateField('title', 'id')),
                TextColumn::make('slug_pair')
                    ->label('Slug (ID / EN)')
                    ->state(fn (Page $record): string => sprintf(
                        '/%s · /%s',
                        $record->translateField('slug', 'id'),
                        $record->translateField('slug', 'en'),
                    )),
                IconColumn::make('show_in_nav')
                    ->label('Di Menu')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Tayang')
                    ->boolean(),
                TextColumn::make('missing_en')
                    ->label('Status EN')
                    ->state(fn (Page $record): string => $record->hasMissingTranslation('en') ? 'Belum diterjemahkan' : 'Lengkap')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Lengkap' ? 'success' : 'warning'),
            ])
            ->recordActions([
                Action::make('preview')
                    ->label('Pratinjau')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn (Page $record): string => url(
                        app(PageResolver::class)->pageUrl($record->company, $record, 'id', true),
                    ))
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()?->isSuperAdmin() ?? false)
                    ->modalHeading('Hapus Halaman')
                    ->modalDescription('Apakah Anda yakin ingin menghapus halaman ini beserta seluruh blok penyusunnya? URL publik halaman ini tidak akan dapat diakses lagi.')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->isSuperAdmin() ?? false),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePages::route('/'),
        ];
    }
}
