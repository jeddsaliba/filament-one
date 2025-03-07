<?php

namespace App\Filament\Pages;

use App\Models\Inbox;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class Messages extends Page
{
    use HasPageShield;

    protected static string $view = 'filament.pages.messages';

    public ?Inbox $selectedConversation;

    public static function getSlug(): string
    {
        return config('filament-messages.slug') . '/{id?}';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-messages.navigation.show_in_menu', true);
    }

    public static function getNavigationGroup(): ?string
    {
        return __(config('filament-messages.navigation.navigation_group'));
    }

    public static function getNavigationLabel(): string
    {
        return __(config('filament-messages.navigation.navigation_label'));
    }

    public static function getNavigationBadgeColor(): string | array | null
    {
        return parent::getNavigationBadgeColor();
    }

    public static function getNavigationBadge(): ?string
    {
        if (config('filament-messages.navigation.navigation_display_unread_messages_count')) {
            return Inbox::whereJsonContains('user_ids', Auth::id())
            ->whereHas('messages', function ($query) {
                $query->whereJsonDoesntContain('read_by', Auth::id());
            })->get()->count();
        }

        return parent::getNavigationBadge();
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return config('filament-messages.navigation.navigation_icon');
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-messages.navigation.navigation_sort');
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->selectedConversation = Inbox::findOrFail($id);
        }
    }

    public function getTitle(): string
    {
        return __(config('filament-messages.navigation.navigation_label'));
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return config('filament-messages.max_content_width');
    }

    public function getHeading(): string | Htmlable
    {
        return '';
    }
}
