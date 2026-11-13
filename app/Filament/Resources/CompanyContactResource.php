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
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
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

    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-phone';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi & Pesan';

    protected static ?string $modelLabel = 'Kontak Perusahaan';

    protected static ?string $pluralModelLabel = 'Kontak Perusahaan';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id),
                Grid::make(3)
                    ->schema([
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
                            ->maxLength(150)
                            ->helperText('Form contact publik akan meneruskan pesan ke email ini.'),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(1)
                            ->required(),
                    ])
                    ->columnSpanFull(),
                FilamentTranslatable::textInput('label', 'Label / Nama Kantor', true),
                FilamentTranslatable::textarea('address', 'Alamat Lengkap', true, 3),
                FilamentTranslatable::textInput('operating_hours', 'Jam Kerja (mis. Senin-Jumat 08:00 - 17:00)', false),
                KeyValue::make('social_links')
                    ->label('Tautan Media Sosial (Platform => URL)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label_id')
                    ->label('Kantor / Fasilitas')
                    ->state(fn (CompanyContact $record): string => $record->translateField('label', 'id'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (ContactType|string $state): string => $state instanceof ContactType ? $state->label('id') : (string) $state),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->placeholder('-'),
                TextColumn::make('email')
                    ->label('Email Publik')
                    ->placeholder('-'),
                TextColumn::make('notify_email')
                    ->label('Email Notifikasi')
                    ->placeholder('-'),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipe Kontak')
                    ->options(ContactType::options()),
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
