<?php

namespace App\Models\Traits;

use App\Enums\MediaConversion;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;

trait HasMediaConvertionRegistrations
{
    use InteractsWithMedia;

    /**
     * Registers media conversions for the model.
     *
     * This method adds 4 conversions to the media library:
     * - original: non optimized, non queued
     * - sm: 300x300, fit crop, non queued
     * - md: 500x500, fit crop, non queued
     * - lg: 800x800, fit crop, non queued
     *
     * @return callable
     */
    public function modelMediaConvertionRegistrations(): callable
    {
        return function () {
            $this->addMediaConversion(MediaConversion::ORIGINAL->value)->nonOptimized()->nonQueued();
            $this->addMediaConversion(MediaConversion::SM->value)->fit(Fit::Crop, 300, 300)->nonQueued();
            $this->addMediaConversion(MediaConversion::MD->value)->fit(Fit::Crop, 500, 500)->nonQueued();
            $this->addMediaConversion(MediaConversion::LG->value)->fit(Fit::Crop, 800, 800)->nonQueued();
        };
    }
}
