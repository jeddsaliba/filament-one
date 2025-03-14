<?php

namespace App\Filament\Resources;

use App\Enums\NavGroup;
use App\Filament\Resources\PageBuilderResource\Pages;
use App\Filament\Resources\PageBuilderResource\Traits\HasForm;
use App\Filament\Resources\UserResource\Traits\HasNavigationBadge;
use App\Models\PageBuilder;
use Filament\Forms;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageBuilderResource extends Resource
{
    use HasForm, HasNavigationBadge;

    protected static ?string $model = PageBuilder::class;

    protected static ?string $navigationGroup = NavGroup::CMS->value;

    protected static ?string $modelLabel = 'Page';

    protected static ?string $pluralModelLabel = 'Page Builder';

    protected static ?string $recordTitleAttribute = 'title';

    /**
     * Builds the form for the resource.
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
     * Gets the relationships that should be eager loaded when performing an index query.
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
     * Get the page routes for the PageBuilder resource.
     *
     * @return array<string, \Filament\Resources\Pages\Page> The array of page routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageBuilders::route('/'),
            'create' => Pages\CreatePageBuilder::route('/create'),
            'edit' => Pages\EditPageBuilder::route('/{record}/edit'),
        ];
    }

    /**
     * Gets the query for the resource's index action.
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
