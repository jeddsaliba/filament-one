<?php
namespace App\Filament\Resources\PageBuilderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\PageBuilderResource;
use App\Filament\Resources\PageBuilderResource\Api\Requests\UpdatePageBuilderRequest;

class RestoreHandler extends Handlers {
    public static string | null $uri = '/{id}/restore';
    public static string | null $resource = PageBuilderResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Restore PageBuilder
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::onlyTrashed()->find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->fill(['deleted_at' => null]);

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Restore Resource");
    }
}