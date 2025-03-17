<?php
namespace App\Filament\Resources\PageBuilderResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\PageBuilderResource;

class ForceDeleteHandler extends Handlers {
    public static string | null $uri = '/{id}/force-delete';
    public static string | null $resource = PageBuilderResource::class;

    public static function getMethod()
    {
        return Handlers::DELETE;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Force Delete PageBuilder
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::withTrashed()->find($id);

        if (!$model) return static::sendNotFoundResponse();

        $model->forceDelete();

        return static::sendSuccessResponse($model, "Successfully Force Delete Resource");
    }
}