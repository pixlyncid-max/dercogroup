<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Operation\Enums\OperationType;
use App\Domain\Operation\Models\Operation;
use App\Filament\Resources\OperationResource\Pages\ManageOperations;
use App\Support\FilamentTranslatable;
use BackedEnum;
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

class OperationResource extends Resource
{
    protected static ?string $model = Operation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map-pin';

    protected static string|UnitEnum|null $navigationGroup = 'Konten Operasional';

    protected static ?string $modelLabel = 'Operasi & Lokasi';

    protected static ?string $pluralModelLabel = 'Operasi & Lokasi';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Select::make('company_id')
                            ->label('Perusahaan')
                            ->relationship('company', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->label('Tipe Operasi')
                            ->options(OperationType::options())
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        TextInput::make('latitude')
                            ->label('Latitude (Koordinat)')
                            ->numeric(),
                        TextInput::make('longitude')
                            ->label('Longitude (Koordinat)')
                            ->numeric(),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textInput('name', 'Nama Lokasi / Fasilitas Operasi', true),
                FilamentTranslatable::textInput('location_label', 'Label Wilayah / Kabupaten', false),
                FilamentTranslatable::textarea('description', 'Deskripsi Operasional', true, 3),
                FileUpload::make('image_upload')
                    ->label('Unggah Foto Fasilitas')
                    ->image()
                    ->disk('public')
                    ->directory('operations')
                    ->dehydrated(false),
                FilamentTranslatable::textInput('image_alt', 'Teks Alternatif Gambar Operasi (Wajib)', true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company.name')
                    ->label('Perusahaan')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (OperationType|string $state): string => $state instanceof OperationType ? $state->label('id') : (string) $state),
                TextColumn::make('name_id')
                    ->label('Nama Operasi')
                    ->state(fn (Operation $record): string => $record->translateField('name', 'id')),
                TextColumn::make('coordinates')
                    ->label('Koordinat')
                    ->state(fn (Operation $record): string => $record->latitude !== null && $record->longitude !== null
                        ? "{$record->latitude}, {$record->longitude}"
                        : '-'),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'name'),
                SelectFilter::make('type')
                    ->label('Tipe Operasi')
                    ->options(OperationType::options()),
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
            'index' => ManageOperations::route('/'),
        ];
    }
}
