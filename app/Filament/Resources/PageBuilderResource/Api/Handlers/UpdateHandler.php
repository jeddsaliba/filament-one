<?php
namespace App\Filament\Resources\PageBuilderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\PageBuilderResource;
use App\Filament\Resources\PageBuilderResource\Api\Requests\UpdatePageBuilderRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = PageBuilderResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update PageBuilder
     *
     * @param UpdatePageBuilderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdatePageBuilderRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}