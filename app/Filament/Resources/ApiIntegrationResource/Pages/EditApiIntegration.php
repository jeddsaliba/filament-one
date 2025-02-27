<?php

namespace App\Filament\Resources\ApiIntegrationResource\Pages;

use App\Filament\Resources\ApiIntegrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditApiIntegration extends EditRecord
{
    protected static string $resource = ApiIntegrationResource::class;

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
