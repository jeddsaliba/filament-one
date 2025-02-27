<?php

namespace App\Filament\Resources;

use App\Enums\NavGroup;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Traits\HasForm;
use App\Filament\Resources\UserResource\Traits\HasNavigationBadge;
use App\Models\User;
use Filament\Forms\Form;
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

    public static function form(Form $form): Form
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('id', '!=', Auth::id())
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
