<?php

namespace App\Filament\Resources\UserResource\Traits;

use Illuminate\Support\Facades\Auth;

trait HasNavigationBadge
{
    /**
     * Returns the number of all users except the current user.
     * This is used as the navigation badge.
     *
     * @return int|null
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereNotIn('id', [Auth::id()])->count();
    } 
}
