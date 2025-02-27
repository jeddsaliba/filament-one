<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Support\Str;

enum NavGroup: string implements HasIcon, HasLabel
{
    case UM = 'User Management';
    case ST = 'Settings';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::UM => 'heroicon-o-users',
            self::ST => 'heroicon-o-cog',
        };
    }

    public function getLabel(): string
    {
        return Str::title($this->value);
    }
}
