<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Company\Models\CompanyStat;
use App\Filament\Resources\CompanyStatResource\Pages\ManageCompanyStats;
use App\Support\FilamentTranslatable;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CompanyStatResource extends Resource
{
    protected static ?string $model = CompanyStat::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static string|UnitEnum|null $navigationGroup = 'Konten Operasional';

    protected static ?string $modelLabel = 'Statistik Perusahaan';

    protected static ?string $pluralModelLabel = 'Statistik Perusahaan';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id),
                Grid::make(4)
                    ->schema([
                        TextInput::make('value')
                            ->label('Angka Statistik')
                            ->numeric()
                            ->required(),
                        TextInput::make('prefix')
                            ->label('Awalan (Opsional)')
                            ->maxLength(20),
                        TextInput::make('suffix')
                            ->label('Akhiran (mis. %, ft, Ha)')
                            ->maxLength(20),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textInput('unit', 'Satuan / Sub-label (mis. MT / Tahun)', false),
                FilamentTranslatable::textInput('label', 'Keterangan Statistik', true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label_id')
                    ->label('Keterangan (ID)')
                    ->state(fn (CompanyStat $record): string => $record->translateField('label', 'id')),
                TextColumn::make('display_value')
                    ->label('Tampilan Angka')
                    ->state(fn (CompanyStat $record): string => trim(sprintf(
                        '%s%s %s',
                        $record->prefix ?? '',
                        number_format((float) $record->value),
                        $record->suffix ?? '',
                    )))
                    ->badge(),
                TextColumn::make('unit_id')
                    ->label('Satuan')
                    ->state(fn (CompanyStat $record): string => $record->translateField('unit', 'id')),
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
            'index' => ManageCompanyStats::route('/'),
        ];
    }
}
