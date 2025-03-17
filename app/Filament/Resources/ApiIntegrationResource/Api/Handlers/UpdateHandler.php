<?php
namespace App\Filament\Resources\ApiIntegrationResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\ApiIntegrationResource;
use App\Filament\Resources\ApiIntegrationResource\Api\Requests\UpdateApiIntegrationRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = ApiIntegrationResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update ApiIntegration
     *
     * @param UpdateApiIntegrationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateApiIntegrationRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}