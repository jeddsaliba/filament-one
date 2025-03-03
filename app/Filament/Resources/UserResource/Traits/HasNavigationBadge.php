<?php

namespace App\Filament\Resources\UserResource\Traits;

use Illuminate\Support\Facades\Auth;

trait HasNavigationBadge
{
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereNotIn('id', [Auth::id()])->count();
    } 
}
