<?php

namespace App\Filament\Resources\ApiIntegrationResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateApiIntegrationRequest extends FormRequest
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
			'slug' => [
                'required',
                'string',
                Rule::unique('api_integrations', 'slug')->ignore($this->id),
            ],
			'is_active' => 'required|boolean',
			'keys' => 'required|array',
			'deleted_at' => 'sometimes'
		];
    }
}
