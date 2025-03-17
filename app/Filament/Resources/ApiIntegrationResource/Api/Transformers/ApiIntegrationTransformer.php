<?php
namespace App\Filament\Resources\ApiIntegrationResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ApiIntegration;

/**
 * @property ApiIntegration $resource
 */
class ApiIntegrationTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->toArray();
    }
}
