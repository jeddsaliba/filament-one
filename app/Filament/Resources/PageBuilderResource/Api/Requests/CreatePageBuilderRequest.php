<?php

namespace App\Filament\Resources\PageBuilderResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePageBuilderRequest extends FormRequest
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
			'title' => 'required|string',
			'slug' => 'required|string|unique:page_builders,slug',
			'description' => 'sometimes|string',
			'is_active' => 'required|boolean',
			'content' => 'sometimes|string',
			'custom_css' => 'sometimes|string',
			'custom_js' => 'sometimes|string',
			'meta' => 'sometimes|array',
			'deleted_at' => 'sometimes'
		];
    }
}
