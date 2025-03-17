<?php
namespace App\Filament\Resources\ApiIntegrationResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\ApiIntegrationResource;
use Illuminate\Routing\Router;


class ApiIntegrationApiService extends ApiService
{
    protected static string | null $resource = ApiIntegrationResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class,
            Handlers\ForceDeleteHandler::class,
            Handlers\RestoreHandler::class
        ];
    }
}
