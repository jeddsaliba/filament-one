<?php

namespace App\Filament\Resources\PageBuilderResource\Pages;

use App\Filament\Resources\PageBuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPageBuilder extends EditRecord
{
    protected static string $resource = PageBuilderResource::class;

    /**
     * Gets the actions available for the header of the resource.
     *
     * The actions include a delete/force delete/restore action group, as well as an action group with a comments action and an activity log action.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\DeleteAction::make(),
                Actions\ForceDeleteAction::make(),
                Actions\RestoreAction::make(),
                Actions\ActionGroup::make([
                    \Parallax\FilamentComments\Actions\CommentsAction::make(),
                    \Rmsramos\Activitylog\Actions\ActivityLogTimelineSimpleAction::make()
                        ->label('Activity Logs'),
                ])->dropdown(false)
            ]),
        ];
    }
}
