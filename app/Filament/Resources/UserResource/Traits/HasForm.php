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
    /**
     * Builds an array representing the form schema for user resource forms,
     * structured with multiple tabs for different sections of user data.
     *
     * @return array The form schema array containing tab components for
     *               'Basic Information', 'Change Password', and 'Reset Password'.
     */
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

    /**
     * Builds an array representing the form schema for the 'Basic Information'
     * tab for user resource forms, containing fields for name, email, phone,
     * birthdate, and roles.
     *
     * @return array The form schema array containing form components for
     *               'Basic Information'.
     */
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
                ->required()
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable(),
        ];
    }

    /**
     * Builds an array representing the form schema for the 'Change Password'
     * tab, containing password input fields and actions for generating and
     * confirming passwords. The password fields are revealable and required
     * when creating a new record. A hint action is provided to generate a 
     * random password.
     *
     * @return array The form schema array containing form components for
     *               password input and confirmation.
     */
    public static function passwordForm(): array
    {
        return [
            Forms\Components\TextInput::make('password')
                ->password()
                ->confirmed()
                ->revealable()
                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                ->hintActions([
                    Forms\Components\Actions\Action::make('generate_password')
                        ->action(function (Forms\Set $set) {
                            $password = Str::random();
                            $set('password', $password);
                            $set('password_confirmation', $password);
                        })
                ]),
            Forms\Components\TextInput::make('password_confirmation')
                ->password()
                ->revealable()
                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),
        ];
    }

    /**
     * Builds an array representing the form schema for the 'Reset Password'
     * tab, containing an action for generating a random password and sending
     * it to the user's email address. The action will prompt the user to change
     * the password on initial login.
     *
     * @return array The form schema array containing a single form section and
     *               an action for resetting the password.
     */
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
