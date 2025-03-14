<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum NavGroup: string implements HasIcon, HasLabel
{
    case CMS = 'CMS';
    case UM = 'User Management';
    case ST = 'Settings';

    /**
     * Gets the icon for the navigation group.
     *
     * @return string|null The icon class name.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::CMS => 'heroicon-o-puzzle-piece',
            self::UM => 'heroicon-o-users',
            self::ST => 'heroicon-o-cog',
        };
    }

    /**
     * Gets the label for the navigation group.
     *
     * @return string The title-cased label.
     */
    public function getLabel(): string
    {
        return Str::title($this->value);
    }
}
