<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Company\Models\Setting;
use App\Filament\Resources\SettingResource\Pages\ManageSettings;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $modelLabel = 'Pengaturan Website';

    protected static ?string $pluralModelLabel = 'Pengaturan Website';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id),
                TextInput::make('key')
                    ->label('Kunci Pengaturan')
                    ->required()
                    ->maxLength(150),
                TextInput::make('group')
                    ->label('Kelompok (seo, contact, analytics)')
                    ->default('general')
                    ->required()
                    ->maxLength(50),
                KeyValue::make('value')
                    ->label('Nilai (Key-Value / ID-EN)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Kunci')
                    ->searchable()
                    ->badge(),
                TextColumn::make('group')
                    ->label('Kelompok')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSettings::route('/'),
        ];
    }
}
