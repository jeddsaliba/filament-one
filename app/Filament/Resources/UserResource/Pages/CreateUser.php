<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Jobs\SendCredentials;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Facades\FilamentView;

use function Filament\Support\is_app_url;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    private bool $sendCredentials = false;

    protected function getFormActions(): array
    {
        return [
            $this->createAction(),
            ...(static::canCreateAnother() ? [
                $this->createAction('createAnother')
            ] : []),
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

    private function createAction(string $type = 'create'): Actions\Action
    {
        return Actions\Action::make($type)
            ->color(fn () => $type == 'create' ? 'primary' : 'gray')
            ->label(fn () => $type == 'create' ? __('filament-panels::resources/pages/create-record.form.actions.create.label') : __('filament-panels::resources/pages/create-record.form.actions.create_another.label'))
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
                    ->action(function (Actions\Action $action) use ($type) {
                        $this->sendCredentials = true;
                        $email = $this->data['email'];
                        $password = $this->data['password'];
                        $user = null;
                        if ($type == 'create') {
                            $this->create();
                            $user = $this->record;
                        } else {
                            $this->createAnother();
                            $user = User::where(['email' => $email])->firstOrFail();
                            $redirectUrl = UserResource::getUrl('create');
                            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
                        }
                        SendCredentials::dispatchSync($user, $password);
                        $this->sendCredentials = false;
                    })->size(ActionSize::Small)
            ])->action(fn () => $type == 'create' ? $this->create() : $this->createAnother());
    }
}
