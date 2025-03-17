<?php
namespace App\Filament\Resources\ApiIntegrationResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ApiIntegrationResource;
use App\Filament\Resources\ApiIntegrationResource\Api\Requests\CreateApiIntegrationRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = ApiIntegrationResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create ApiIntegration
     *
     * @param CreateApiIntegrationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateApiIntegrationRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}