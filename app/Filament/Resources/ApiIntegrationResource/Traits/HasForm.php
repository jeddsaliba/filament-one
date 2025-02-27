<?php

namespace App\Filament\Resources\ApiIntegrationResource\Traits;

use App\Enums\MediaCollectionType;
use App\Jobs\ResetPassword;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait HasForm
{
    public static function formBuilder(): array
    {
        return [
            Forms\Components\Section::make('API Integration Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                            $set('slug', Str::slug($state));
                        }),
                    Forms\Components\TextInput::make('slug')
                        ->dehydrated()
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\KeyValue::make('keys')
                        ->label('Credentials')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2)
        ];
    } 
}
