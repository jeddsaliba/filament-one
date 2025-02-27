<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class Register extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        $user = $this->getUserModel()::create($data);
        $user->assignRole($data['role']);
        Notification::make()
            ->title("Welcome, {$data['name']}!")
            ->success()
            ->send();
        return $user;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRoleFormComponent(), 
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getRoleFormComponent(): Forms\Components\Select
    {
        return Forms\Components\Select::make('role')
            ->native(false)
            ->options(
                Role::all()->pluck('name', 'name')
                    ->mapWithKeys(function ($role, $index) {
                        return [$index => Str::headline($role)];
                    })
            )
            ->preload();
    }
}
