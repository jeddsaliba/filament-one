<?php
namespace App\Filament\Resources\UserResource\Api\Handlers;

use App\Enums\MediaCollectionType;
use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Api\Requests\UpdateUserRequest;

class UpdateHandler extends Handlers {
    public static string | null $uri = '/{id}';
    public static string | null $resource = UserResource::class;

    public static function getMethod()
    {
        return Handlers::PUT;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }


    /**
     * Update User
     *
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(UpdateUserRequest $request)
    {
        $id = $request->route('id');

        $model = static::getModel()::find($id);

        if (!$model) return static::sendNotFoundResponse();

        $data = $request->all();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if (empty($data['password_confirmation'])) {
            unset($data['password_confirmation']);
        }
        $model->fill($data);

        $model->save();

        /**
         * Create or update user profile
         */
        $profile = $model->userProfile;
        if ($profile) {
            $profile->fill($request->all());
        } else {
            $profile = $model->userProfile()->create($request->all());
        }
        $profile->save();

        /**
         * Attach roles
         */
        if ($request->has('roles')) {
            $model->syncRoles($request->input('roles'));
        }

        /**
         * Upload avatar
         */
        if ($request->has('avatar')) {
            $model->clearMediaCollection(MediaCollectionType::USER_PROFILE->value);
            $model->addMediaFromRequest('avatar')->toMediaCollection(MediaCollectionType::USER_PROFILE->value);
        }

        return static::sendSuccessResponse($model, "Successfully Update Resource");
    }
}