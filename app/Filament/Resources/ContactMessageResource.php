<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Domain\Contact\Enums\ContactMessageStatus;
use App\Domain\Contact\Models\ContactMessage;
use App\Filament\Resources\ContactMessageResource\Pages\ManageContactMessages;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-inbox';

    protected static string|UnitEnum|null $navigationGroup = 'Komunikasi & Pesan';

    protected static ?string $modelLabel = 'Pesan Masuk';

    protected static ?string $pluralModelLabel = 'Pesan Masuk';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('company_id')
                    ->default(fn () => Filament::getTenant()?->id),
                Grid::make(2)
                    ->schema([
                        Select::make('status')
                            ->label('Status Pesan')
                            ->options(ContactMessageStatus::options())
                            ->required(),
                        TextInput::make('locale')
                            ->label('Bahasa Pengirim')
                            ->disabled(),
                        TextInput::make('name')
                            ->label('Nama Pengirim')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Email Pengirim')
                            ->email()
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->disabled(),
                        TextInput::make('organization')
                            ->label('Organisasi / Perusahaan')
                            ->disabled(),
                        TextInput::make('subject')
                            ->label('Subjek Pesan')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->label('Isi Pesan')
                            ->rows(5)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ContactMessageStatus|string $state): string => match ($state instanceof ContactMessageStatus ? $state : ContactMessageStatus::tryFrom((string) $state)) {
                        ContactMessageStatus::New => 'danger',
                        ContactMessageStatus::Read => 'warning',
                        ContactMessageStatus::Replied => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (ContactMessageStatus|string $state): string => ($state instanceof ContactMessageStatus ? $state : ContactMessageStatus::tryFrom((string) $state))?->label() ?? (string) $state),
                TextColumn::make('name')
                    ->label('Pengirim')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Pesan')
                    ->options(ContactMessageStatus::options()),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Tinjau & Tanggapi'),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageContactMessages::route('/'),
        ];
    }

    /**
     * @return Builder<ContactMessage>
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
