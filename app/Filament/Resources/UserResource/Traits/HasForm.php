<?php

namespace App\Filament\Resources\UserResource\Traits;

use App\Enums\MediaCollectionType;
use App\Jobs\ResetPassword;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait HasForm
{
    public static function formBuilder(): array
    {
        return [
            Forms\Components\Tabs::make('tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Basic Information')
                        ->schema(self::basicInformationForm())
                        ->columns(2),
                    Forms\Components\Tabs\Tab::make('Change Password')
                        ->hidden(fn () => !Auth::user()->hasRole(Utils::getSuperAdminName()))
                        ->label(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord ? 'Set Password' : 'Change Password')
                        ->schema(self::passwordForm()),
                    Forms\Components\Tabs\Tab::make('Reset Password')
                        ->hidden(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                        ->schema(self::resetPasswordForm()),
                ])
                ->columnSpanFull(),
        ];
    }

    public static function basicInformationForm(): array
    {
        return [
            Forms\Components\Grid::make(4)
                ->schema([
                    Forms\Components\SpatieMediaLibraryFileUpload::make('avatar')
                        ->collection(MediaCollectionType::USER_PROFILE->value)
                        ->avatar(),
                ])->columnSpanFull(),
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->unique(ignoreRecord: true)
                ->required(),
            Forms\Components\Group::make()
                ->relationship('userProfile')
                ->schema([
                    \Ysfkaya\FilamentPhoneInput\Forms\PhoneInput::make('phone'),
                    Forms\Components\DatePicker::make('birthdate'),
                ]),
            Forms\Components\Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
        ];
    }

    public static function passwordForm(): array
    {
        return [
            Forms\Components\TextInput::make('password')
                ->password()
                ->confirmed()
                ->revealable()
                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),
            Forms\Components\TextInput::make('password_confirmation')
                ->password()
                ->revealable()
                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),
        ];
    }

    public static function resetPasswordForm(): array
    {
        return [
            Forms\Components\Section::make()
                ->description('This will generate a random password and send it to the user\'s email address. Upon initial login, the user will be prompted to change the password.')
                ->schema([
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('resetPassword')
                            ->label('Reset Password')
                            ->requiresConfirmation()
                            ->action(function (\Livewire\Component $livewire) {
                                $user = $livewire->getRecord();
                                $password = Str::random(8);
                                $user->save();
                                ResetPassword::dispatchSync($user, $password);
                                Notification::make()
                                    ->title('Your password was reset. Please check your email.')
                                    ->success()
                                    ->send();
                            }),
                    ]),
                ]),
        ];
    }    
}
