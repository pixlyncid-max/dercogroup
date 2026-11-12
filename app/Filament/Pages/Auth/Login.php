<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.layouts.auth';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getSubHeading(): string|Htmlable|null
    {
        return null;
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Email Address')
            ->placeholder('Email or Username')
            ->prefixIcon('heroicon-o-user')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['class' => '!rounded-xl !py-2.5 !text-sm']);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Password')
            ->placeholder('Password')
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="-1" class="!text-[#EDB856] hover:!text-[#D49E35] font-semibold text-xs">Forgot Password?</x-filament::link>')) : null)
            ->prefixIcon('heroicon-o-lock-closed')
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->extraInputAttributes(['class' => '!rounded-xl !py-2.5 !text-sm']);
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('Login')
            ->icon('heroicon-o-arrow-right-start-on-rectangle')
            ->submit('authenticate')
            ->extraAttributes([
                'aria-label' => 'Login',
                'class' => 'w-full !bg-[#122A44] hover:!bg-[#0C1D30] !text-white font-semibold py-3 rounded-xl transition-all shadow-md',
            ]);
    }
}
