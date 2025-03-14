<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Custom header actions for this resource.
     *
     * Adds a delete/force delete/restore action group, but only if the record is not the current user.
     * Also adds an action group with a comments action and an activity log action.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\DeleteAction::make()
                    ->hidden(function (Model $record) {
                        return $record->getKey() == Auth::id();
                    }),
                Actions\ForceDeleteAction::make()
                    ->hidden(function (Model $record) {
                        return $record->getKey() == Auth::id();
                    }),
                Actions\RestoreAction::make()
                    ->hidden(function (Model $record) {
                        return $record->getKey() == Auth::id();
                    }),
                Actions\ActionGroup::make([
                    \Parallax\FilamentComments\Actions\CommentsAction::make(),
                    \Rmsramos\Activitylog\Actions\ActivityLogTimelineSimpleAction::make()
                        ->label('Activity Logs')
                        ->withRelations(['userProfile']),
                ])->dropdown(false)
            ]),
        ];
    }

    /**
     * Removes the password and password confirmation fields from the form data
     * if they are empty. This is to prevent overwriting the existing password
     * with a blank value when updating a user.
     *
     * @param array $data the form data
     * @return array the mutated form data
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (empty($data['password_confirmation'])) {
            unset($data['password_confirmation']);
        }
        return $data;
    }
}
