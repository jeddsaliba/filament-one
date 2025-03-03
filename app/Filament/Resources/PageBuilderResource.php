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

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema(self::formBuilder());
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageBuilders::route('/'),
            'create' => Pages\CreatePageBuilder::route('/create'),
            'edit' => Pages\EditPageBuilder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
