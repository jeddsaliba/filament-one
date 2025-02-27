<?php

namespace App\Filament\Resources;

use App\Enums\NavGroup;
use App\Filament\Resources\ApiIntegrationResource\Pages;
use App\Filament\Resources\ApiIntegrationResource\RelationManagers;
use App\Filament\Resources\ApiIntegrationResource\Traits\HasForm;
use App\Filament\Resources\UserResource\Traits\HasNavigationBadge;
use App\Models\ApiIntegration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApiIntegrationResource extends Resource
{
    use HasForm, HasNavigationBadge;

    protected static ?string $model = ApiIntegration::class;
    protected static ?string $modelLabel = 'API Integration';

    protected static ?string $navigationGroup = NavGroup::ST->value;

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
            'index' => Pages\ListApiIntegrations::route('/'),
            'create' => Pages\CreateApiIntegration::route('/create'),
            'edit' => Pages\EditApiIntegration::route('/{record}/edit'),
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
