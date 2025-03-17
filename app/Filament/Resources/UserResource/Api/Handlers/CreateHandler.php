<?php
namespace App\Filament\Resources\UserResource\Api\Handlers;

use App\Enums\MediaCollectionType;
use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Api\Requests\CreateUserRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = UserResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create User
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateUserRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

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
            $model->addMediaFromRequest('avatar')->toMediaCollection(MediaCollectionType::USER_PROFILE->value);
        }

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}