<?php

namespace App\Filament\Resources\ApiIntegrationResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\ApiIntegrationResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\ApiIntegrationResource\Api\Transformers\ApiIntegrationTransformer;

class DetailHandler extends Handlers
{
    public static string | null $uri = '/{id}';
    public static string | null $resource = ApiIntegrationResource::class;


    /**
     * Show ApiIntegration
     *
     * @param Request $request
     * @return ApiIntegrationTransformer
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

        return new ApiIntegrationTransformer($query);
    }
}
