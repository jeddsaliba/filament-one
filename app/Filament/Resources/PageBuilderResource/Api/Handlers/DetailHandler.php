<?php

namespace App\Filament\Resources\PageBuilderResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\PageBuilderResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\PageBuilderResource\Api\Transformers\PageBuilderTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = PageBuilderResource::class;


    /**
     * Show PageBuilder
     *
     * @param Request $request
     * @return PageBuilderTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');
        
        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new PageBuilderTransformer($query);
    }
}
