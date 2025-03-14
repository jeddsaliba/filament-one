<?php

namespace App\Filament\Resources;

use App\Enums\NavGroup;
use App\Filament\Resources\ApiIntegrationResource\Pages;
use App\Filament\Resources\ApiIntegrationResource\Traits\HasForm;
use App\Filament\Resources\UserResource\Traits\HasNavigationBadge;
use App\Models\ApiIntegration;
use Filament\Forms;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApiIntegrationResource extends Resource
{
    use HasForm, HasNavigationBadge;

    protected static ?string $model = ApiIntegration::class;
    protected static ?string $modelLabel = 'API Credentials';

    protected static ?string $navigationGroup = NavGroup::ST->value;

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Build the form for the resource.
     *
     * @param \Filament\Forms\Form $form
     * @return \Filament\Forms\Form
     */
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema(self::formBuilder());
    }

    /**
     * Get the relationships that should be eager loaded when performing an index query.
     *
     * @return array<string>
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the pages for the resource.
     *
     * @return array<string, Page>
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApiIntegrations::route('/'),
            'create' => Pages\CreateApiIntegration::route('/create'),
            'edit' => Pages\EditApiIntegration::route('/{record}/edit'),
        ];
    }

    /**
     * Get the query for the resource's index action.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
