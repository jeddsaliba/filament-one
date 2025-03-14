<?php

namespace App\Filament\Resources\PageBuilderResource\Traits;

use Filament\Forms;
use Illuminate\Support\Str;

trait HasForm
{
    /**
     * Builds an array representing the form schema for the PageBuilder resource,
     * structured with multiple tabs for different sections of page data.
     *
     * @return array The form schema array containing tab components for
     *               'Basic Information', 'Content', 'Custom Assets', and 'SEO'.
     */
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

    /**
     * Builds an array representing the form schema for the 'Basic Information'
     * tab for the PageBuilder resource, containing fields for title, slug,
     * description, and is_active. The slug field is dehydrated and unique,
     * while the description field is a RichEditor and is required. The
     * is_active toggle is also required.
     *
     * @return array The form schema array containing form components for
     *               'Basic Information'.
     */
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

    /**
     * Builds an array representing the form schema for the 'Content'
     * tab for the PageBuilder resource, containing a single GrapeJS
     * field for the page content.
     *
     * @return array The form schema array containing the GrapeJS component.
     */
    public static function contentForm(): array
    {
        return [
            \Dotswan\FilamentGrapesjs\Fields\GrapesJs::make('content')
                ->columnSpanFull()
                ->hiddenLabel()
                ->id('content'),
        ];
    }

    /**
     * Builds an array representing the form schema for the 'Custom Assets'
     * tab for the PageBuilder resource, containing AceEditor fields for
     * custom CSS and custom JavaScript. Each field is labeled and set to
     * the appropriate code mode.
     *
     * @return array The form schema array containing AceEditor components
     *               for 'Custom CSS' and 'Custom JS'.
     */
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

    /**
     * Builds an array representing the form schema for the 'SEO'
     * tab for the PageBuilder resource, containing a KeyValue field
     * for metadata with keys for description, keywords, and author.
     *
     * @return array The form schema array containing a KeyValue component
     *               for metadata.
     */
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
