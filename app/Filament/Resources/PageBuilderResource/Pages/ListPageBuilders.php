<?php

namespace App\Filament\Resources\PageBuilderResource\Pages;

use App\Filament\Resources\PageBuilderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ListPageBuilders extends ListRecords
{
    protected static string $resource = PageBuilderResource::class;

    /**
     * Custom header actions for this resource.
     *
     * Only includes a create action.
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
     * Builds the table for this resource.
     *
     * Adds columns to search for by title, slug, and URL. Adds a boolean column
     * to indicate active status. Adds columns for created_at, updated_at, and
     * deleted_at for sorting and toggling.
     *
     * Adds a Trashed filter.
     *
     * Adds an ActionGroup to edit, delete, and view activity logs for each record.
     * Adds a BulkActionGroup with bulk delete, force delete, and restore actions.
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->url(fn (Model $record) => $record->url, true)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
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
