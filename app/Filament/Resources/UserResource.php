<?php

namespace App\Filament\Resources;

use App\Enums\NavGroup;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Traits\HasForm;
use App\Filament\Resources\UserResource\Traits\HasNavigationBadge;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    use HasForm, HasNavigationBadge;

    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = NavGroup::UM->value;

    protected static ?string $recordTitleAttribute = 'name';

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
     * Get the pages for the resource.
     *
     * @return array<string, Page> The array of page routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
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
            ->where('id', '!=', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
