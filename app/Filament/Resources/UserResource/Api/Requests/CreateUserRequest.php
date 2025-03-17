<?php

namespace App\Filament\Resources\UserResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
			'email_verified_at' => 'sometimes',
			'password' => 'required|confirmed|string',
			'remember_token' => 'sometimes|string',
            'deleted_at' => 'sometimes',
            'phone' => 'sometimes',
            'birthdate' => 'sometimes',
            'roles' => 'sometimes|array',
		];
    }
}
