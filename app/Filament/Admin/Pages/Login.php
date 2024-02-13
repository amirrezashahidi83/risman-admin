<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Pages\Auth\Login as BaseAuth;
use Filament\Forms\Form;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Hash;

class Login extends BaseAuth 
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'vendor.filament-panels.pages.auth.login';


    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException) {
            throw ValidationException::withMessages([
                'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }
    }    
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('username')
            ->label('نام کاربری')
            ->autocomplete()
            ->autofocus()
            ->required(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ])
        ->statePath('data');

    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return[
            'username' => $data['username'],
            'password' => $data['password']
        ];
    }
}
