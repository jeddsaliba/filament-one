<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Jobs\SendCredentials;
use Filament\Actions;
use Filament\Notifications;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    private bool $sendCredentials = false;

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
                ->requiresConfirmation()
                ->modalDescription(function () {
                    $email = !empty($this->data['email']) ? $this->data['email'] : 'the email provided';
                    return "Creating a new user will send the credentials to {$email}. " . __('filament-actions::modal.confirmation');
                })
                ->modalWidth(MaxWidth::FitContent)
                ->modalSubmitActionLabel('No, save only')
                ->extraModalFooterActions([
                    Actions\Action::make('send_credentials')
                        ->color('warning')
                        ->icon('heroicon-o-envelope')
                        ->label('Confirm & send credentials')
                        ->action(function () {
                            $this->sendCredentials = true;
                            $this->create();
                            SendCredentials::dispatchSync($this->record, $this->data['password']);
                            $this->sendCredentials = false;
                        })->size(ActionSize::Small)
                ])->action(fn () => $this->create()),
            ...(static::canCreateAnother() ? [$this->getCreateAnotherFormAction()] : []),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreatedNotification(): ?Notifications\Notification
    {
        return Notifications\Notification::make()
            ->title(fn () => $this->sendCredentials ? __('filament-panels::resources/pages/create-record.notifications.created.title') . " and sent credentials to " . $this->record->email : __('filament-panels::resources/pages/create-record.notifications.created.title'))
            ->success()
            ->send();
    }
}
