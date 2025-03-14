<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\MediaCollectionType;
use App\Enums\MediaConversion;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\AvatarProviders\UiAvatarsProvider;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    /**
     * Actions to display in the header of the table.
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
     * @param \Filament\Tables\Table $table
     * @return \Filament\Tables\Table
     */
    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(UserExporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection(MediaCollectionType::USER_PROFILE->value)
                    ->circular()
                    ->conversion(MediaConversion::SM->value)
                    ->defaultImageUrl(fn (Model $record) => app(UiAvatarsProvider::class)->get($record)),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                \Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn::make('userProfile.phone')
                    ->label('Phone')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->displayFormat(\Ysfkaya\FilamentPhoneInput\PhoneInputNumberType::INTERNATIONAL),
                Tables\Columns\TextColumn::make('userProfile.birthdate')
                    ->label('Birthdate')
                    ->date(config('filament.date_format'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
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
                \STS\FilamentImpersonate\Tables\Actions\Impersonate::make()
                    ->redirectTo(route('filament.admin.pages.dashboard'))
                    ->requiresConfirmation(),
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
