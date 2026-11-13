<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Password;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static bool $isScopedToTenant = false;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255),
                Select::make('roles')
                    ->label('Peran (Role)')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y'),
            ])
            ->recordActions([
                Action::make('send_password_reset')
                    ->label('Reset Sandi')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Email Reset Kata Sandi')
                    ->modalDescription(fn (User $record): string => "Kirimkan tautan reset kata sandi ke email {$record->email}?")
                    ->action(function (User $record): void {
                        Password::broker()->sendResetLink(['email' => $record->email]);

                        Notification::make()
                            ->success()
                            ->title('Email Terkirim')
                            ->body("Tautan reset kata sandi berhasil dikirim ke {$record->email}.")
                            ->send();
                    }),
                EditAction::make()
                    ->before(function (EditAction $action, User $record, array $data): void {
                        // Prevent removing Super Admin role if this is the last Super Admin
                        if ($record->isSuperAdmin() && User::role(User::ROLE_SUPER_ADMIN)->count() <= 1) {
                            $selectedRoles = $data['roles'] ?? [];
                            $superAdminRole = \Spatie\Permission\Models\Role::where('name', User::ROLE_SUPER_ADMIN)->first();
                            if ($superAdminRole && ! in_array($superAdminRole->id, (array) $selectedRoles, false)) {
                                Notification::make()
                                    ->danger()
                                    ->title('Super Admin Terakhir')
                                    ->body('Sistem harus mempertahankan minimal satu akun dengan peran Super Admin.')
                                    ->send();
                                $action->halt();
                            }
                        }
                    }),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Pengguna CMS')
                    ->modalDescription('Apakah Anda yakin ingin menghapus akun pengguna ini?')
                    ->before(function (DeleteAction $action, User $record): void {
                        $currentUser = auth()->user();

                        if ($currentUser && $currentUser->id === $record->id) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak Dapat Menghapus Akun Sendiri')
                                ->body('Anda tidak dapat menghapus akun yang sedang Anda gunakan untuk masuk.')
                                ->send();
                            $action->halt();
                        }

                        if ($record->isSuperAdmin() && User::role(User::ROLE_SUPER_ADMIN)->count() <= 1) {
                            Notification::make()
                                ->danger()
                                ->title('Super Admin Terakhir')
                                ->body('Sistem harus mempertahankan minimal satu akun dengan peran Super Admin.')
                                ->send();
                            $action->halt();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, Collection $records): void {
                            $currentUserId = auth()->id();
                            $hasSelf = $records->contains(fn (User $user): bool => $user->id === $currentUserId);
                            if ($hasSelf) {
                                Notification::make()
                                    ->danger()
                                    ->title('Gagal Menghapus')
                                    ->body('Anda tidak dapat menghapus akun Anda sendiri.')
                                    ->send();
                                $action->halt();
                            }

                            $superAdminCount = User::role(User::ROLE_SUPER_ADMIN)->count();
                            $deletingSuperAdmins = $records->filter(fn (User $user): bool => $user->isSuperAdmin())->count();
                            if ($superAdminCount - $deletingSuperAdmins < 1) {
                                Notification::make()
                                    ->danger()
                                    ->title('Super Admin Terakhir')
                                    ->body('Sistem harus mempertahankan minimal satu akun dengan peran Super Admin.')
                                    ->send();
                                $action->halt();
                            }
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }
}
