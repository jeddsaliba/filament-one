<?php

namespace App\Filament\Resources\ApiIntegrationResource\Pages;

use App\Filament\Exports\ApiIntegrationExporter;
use App\Filament\Resources\ApiIntegrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListApiIntegrations extends ListRecords
{
    protected static string $resource = ApiIntegrationResource::class;

    /**
     * Get the actions available for the header of the table.
     *
     * Adds a create action to the header of the table.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Configure the table within the list page.
     *
     * Adds columns to search for by name, slug, and ID. Adds a boolean column
     * to indicate active status. Adds columns for created_at, updated_at, and
     * deleted_at for sorting and toggling.
     *
     * Adds a Trashed filter.
     *
     * Adds an ActionGroup to edit, delete, and view activity logs for each record.
     * Adds a BulkActionGroup with bulk delete, force delete, and restore actions.
     *
     * @param Tables\Table $table
     * @return Tables\Table
     */
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(ApiIntegrationExporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(config('filament.date_time_format'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(config('filament.date_time_format'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime(config('filament.date_time_format'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ActionGroup::make([
                        \Parallax\FilamentComments\Tables\Actions\CommentsAction::make(),
                        \Rmsramos\Activitylog\Actions\ActivityLogTimelineTableAction::make()
                            ->label('Activity Logs')
                            ->withRelations(['userProfile'])
                    ])->dropdown(false)
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
