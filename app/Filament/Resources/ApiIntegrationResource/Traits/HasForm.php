<?php

namespace App\Filament\Resources\ApiIntegrationResource\Traits;

use Filament\Forms;
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
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->required(),
                ])->columns(2)
        ];
    } 
}
