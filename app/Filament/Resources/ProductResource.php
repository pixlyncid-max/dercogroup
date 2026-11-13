<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Company\Traits\HasAdaptiveTenantNavigation;
use App\Domain\Page\Enums\PageKey;
use App\Domain\Product\Models\Product;
use App\Filament\Resources\ProductResource\Pages\ManageProducts;
use App\Support\FilamentTranslatable;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProductResource extends Resource
{
    use HasAdaptiveTenantNavigation;

    protected static ?string $model = Product::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static string|UnitEnum|null $navigationGroup = 'Konten Operasional';

    protected static ?string $modelLabel = 'Produk & Spesifikasi';

    protected static ?string $pluralModelLabel = 'Produk & Spesifikasi';

    protected static ?int $navigationSort = 1;

    public static function getRequiredPageKey(): PageKey
    {
        return PageKey::Products;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id),
                Grid::make(3)
                    ->schema([
                        TextInput::make('slug')
                            ->label('Slug Produk')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Toggle::make('is_published')
                            ->label('Tayangkan Produk')
                            ->default(true),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textInput('name', 'Nama Produk', true),
                FilamentTranslatable::textInput('category_label', 'Label Kategori (mis. GAR 4.200 kcal/kg)', false),
                FilamentTranslatable::textarea('summary', 'Ringkasan Singkat', true, 2),
                FilamentTranslatable::textarea('description', 'Deskripsi Lengkap', false, 3),
                FileUpload::make('gallery_upload')
                    ->label('Unggah Foto / Galeri Produk')
                    ->image()
                    ->maxSize(5120)
                    ->multiple()
                    ->disk('public')
                    ->directory('products')
                    ->dehydrated(false),
                FilamentTranslatable::textInput('image_alt', 'Teks Alternatif Gambar Produk (Wajib)', true),
                Repeater::make('specs')
                    ->label('Tabel Spesifikasi Batu Bara / Produk')
                    ->relationship('specs')
                    ->orderColumn('sort_order')
                    ->reorderable()
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextInput::make('code')
                                    ->label('Kode (CV/TM/ASH/TS)')
                                    ->maxLength(30),
                                TextInput::make('value')
                                    ->label('Nilai')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('unit')
                                    ->label('Satuan (kcal/kg, %)')
                                    ->maxLength(50),
                                Toggle::make('is_highlight')
                                    ->label('Sorot (Highlight)')
                                    ->default(false),
                            ]),
                        FilamentTranslatable::textInput('label', 'Label Spesifikasi (ID/EN)', true),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name_id')
                    ->label('Nama Produk (ID)')
                    ->state(fn (Product $record): string => $record->translateField('name', 'id'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category_label_id')
                    ->label('Kategori')
                    ->state(fn (Product $record): string => $record->translateField('category_label', 'id'))
                    ->badge(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->badge(),
                IconColumn::make('is_published')
                    ->label('Tayang')
                    ->boolean(),
                TextColumn::make('specs_count')
                    ->label('Jumlah Spesifikasi')
                    ->counts('specs')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
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
            'index' => ManageProducts::route('/'),
        ];
    }
}
