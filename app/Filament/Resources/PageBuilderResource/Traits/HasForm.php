<?php

namespace App\Filament\Resources\PageBuilderResource\Traits;

use Filament\Forms;
use Illuminate\Support\Str;

trait HasForm
{
    public static function formBuilder(): array
    {
        return [
            Forms\Components\Tabs::make('tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Basic Information')
                        ->schema(self::basicInformationForm()),
                    Forms\Components\Tabs\Tab::make('Content')
                        ->schema(self::contentForm()),
                    Forms\Components\Tabs\Tab::make('Custom Assets')
                        ->schema(self::assetsForm()),
                    Forms\Components\Tabs\Tab::make('SEO')
                        ->schema(self::seoForm()),
                ])
                ->columnSpanFull(),
        ];
    }

    public static function basicInformationForm(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                    $set('slug', Str::slug($state));
                }),
            Forms\Components\TextInput::make('slug')
                ->dehydrated()
                ->helperText(function (Forms\Get $get) {
                    return "Note: This will be the URL generated for this page. (i.e. " . config('app.url') . "/{$get('slug')})";
                })
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\RichEditor::make('description')
                ->columnSpanFull()
                ->live()
                ->afterStateUpdated(function (string $operation, $state, Forms\Get $get, Forms\Set $set) {
                    $meta = $get('meta') ?? [
                        'description' => null,
                        'keywords' => null,
                        'author' => null
                    ];
                    $meta['description'] = strip_tags($state);
                    $set('meta', $meta);
                }),
            Forms\Components\Toggle::make('is_active')
                ->default(true)
                ->required(),
        ];
    }

    public static function contentForm(): array
    {
        return [
            \Dotswan\FilamentGrapesjs\Fields\GrapesJs::make('content')
                ->columnSpanFull()
                ->hiddenLabel()
                ->id('content'),
        ];
    }

    public static function assetsForm(): array
    {
        return [
            \Riodwanto\FilamentAceEditor\AceEditor::make('custom_css')
                ->label('Custom CSS')
                ->mode('css'),
            \Riodwanto\FilamentAceEditor\AceEditor::make('custom_js')
                ->label('Custom JS')
                ->mode('javascript'),
        ];
    }

    public static function seoForm(): array
    {
        return [
            Forms\Components\KeyValue::make('meta')
                ->label('Metadata')
                ->default([
                    'description' => null,
                    'keywords' => null,
                    'author' => null
                ])
                ->editableKeys(false)
                ->deletable(false)
                ->addable(false),
        ];
    }
}
