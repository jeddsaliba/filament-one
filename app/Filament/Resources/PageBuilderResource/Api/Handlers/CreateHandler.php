<?php
namespace App\Filament\Resources\PageBuilderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\PageBuilderResource;
use App\Filament\Resources\PageBuilderResource\Api\Requests\CreatePageBuilderRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = PageBuilderResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create PageBuilder
     *
     * @param CreatePageBuilderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreatePageBuilderRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}