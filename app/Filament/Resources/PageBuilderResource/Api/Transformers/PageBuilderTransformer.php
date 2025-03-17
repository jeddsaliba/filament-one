<?php
namespace App\Filament\Resources\PageBuilderResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PageBuilder;

/**
 * @property PageBuilder $resource
 */
class PageBuilderTransformer extends JsonResource
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
