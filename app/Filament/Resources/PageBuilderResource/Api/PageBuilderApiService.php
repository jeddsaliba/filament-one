<?php
namespace App\Filament\Resources\PageBuilderResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\PageBuilderResource;
use Illuminate\Routing\Router;


class PageBuilderApiService extends ApiService
{
    protected static string | null $resource = PageBuilderResource::class;

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
