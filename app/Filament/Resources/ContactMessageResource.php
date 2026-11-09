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
use Filament\Actions\RestoreAction;
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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Select::make('company_id')
                            ->label('Perusahaan Tujuan')
                            ->relationship('company', 'name')
                            ->required(),
                        Select::make('status')
                            ->label('Status Pesan')
                            ->options(ContactMessageStatus::options())
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Pengirim')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email Pengirim')
                            ->email()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Telepon'),
                        TextInput::make('organization')
                            ->label('Organisasi / Perusahaan'),
                        TextInput::make('locale')
                            ->label('Bahasa Formulir')
                            ->maxLength(5),
                        TextInput::make('subject')
                            ->label('Subjek')
                            ->required(),
                    ])
                    ->columnSpanFull(),
                Textarea::make('message')
                    ->label('Isi Pesan')
                    ->rows(5)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('company.name')
                    ->label('Perusahaan Tujuan')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Pengirim')
                    ->searchable(),
                TextColumn::make('organization')
                    ->label('Organisasi')
                    ->placeholder('-'),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (ContactMessageStatus|string $state): string => match ($state instanceof ContactMessageStatus ? $state : ContactMessageStatus::from((string) $state)) {
                        ContactMessageStatus::New => 'warning',
                        ContactMessageStatus::Read => 'info',
                        ContactMessageStatus::Replied => 'success',
                    })
                    ->formatStateUsing(fn (ContactMessageStatus|string $state): string => ($state instanceof ContactMessageStatus ? $state : ContactMessageStatus::from((string) $state))->label()),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(ContactMessageStatus::options()),
                SelectFilter::make('company_id')
                    ->label('Perusahaan')
                    ->relationship('company', 'name'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
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

    public static function getPages(): array
    {
        return [
            'index' => ManageContactMessages::route('/'),
        ];
    }
}
