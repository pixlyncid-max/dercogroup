<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Contact\Enums\ContactType;
use App\Domain\Contact\Models\CompanyContact;
use App\Filament\Resources\CompanyContactResource\Pages\ManageCompanyContacts;
use App\Support\FilamentTranslatable;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class CompanyContactResource extends Resource
{
    protected static ?string $model = CompanyContact::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi & Pesan';

    protected static ?string $modelLabel = 'Kontak Perusahaan';

    protected static ?string $pluralModelLabel = 'Kontak Perusahaan';

    protected static ?int $navigationSort = 1;

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
                            ->label('Tipe Kontak')
                            ->options(ContactType::options())
                            ->default(ContactType::HeadOffice->value)
                            ->required(),
                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->maxLength(60),
                        TextInput::make('whatsapp')
                            ->label('Nomor WhatsApp')
                            ->maxLength(60),
                        TextInput::make('email')
                            ->label('Email Publik')
                            ->email()
                            ->maxLength(150),
                        TextInput::make('notify_email')
                            ->label('Email Tujuan Notifikasi Form (FR-CON-4)')
                            ->email()
                            ->maxLength(150),
                        TextInput::make('latitude')
                            ->label('Latitude Peta')
                            ->numeric(),
                        TextInput::make('longitude')
                            ->label('Longitude Peta')
                            ->numeric(),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textInput('label', 'Label Kantor / Lokasi', true),
                FilamentTranslatable::textarea('address', 'Alamat Lengkap', true, 2),
                FilamentTranslatable::textInput('operating_hours', 'Jam Operasional', false),
                KeyValue::make('socials')
                    ->label('Tautan Media Sosial (mis. linkedin => https://...)')
                    ->columnSpanFull(),
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
                    ->formatStateUsing(fn (ContactType|string $state): string => $state instanceof ContactType ? $state->label('id') : (string) $state),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('email')
                    ->label('Email Publik'),
                TextColumn::make('notify_email')
                    ->label('Email Notifikasi Form'),
            ])
            ->filters([
                SelectFilter::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'name'),
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
            'index' => ManageCompanyContacts::route('/'),
        ];
    }
}
