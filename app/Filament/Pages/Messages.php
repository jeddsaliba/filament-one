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

    /**
     * Get the slug for the messages page.
     *
     * @return string The slug with an optional ID placeholder.
     */
    public static function getSlug(): string
    {
        return config('filament-messages.slug') . '/{id?}';
    }

    /**
     * Determines whether the messages page should be registered in the navigation menu.
     *
     * Returns the value of the `filament-messages.navigation.show_in_menu` config option.
     * Defaults to `true` if the option is not set.
     *
     * @return bool
     */
    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-messages.navigation.show_in_menu', true);
    }

    /**
     * Determines the navigation group for the messages page.
     *
     * Returns the value of the `filament-messages.navigation.navigation_group` config option.
     * Defaults to `null` if the option is not set.
     *
     * @return string|null
     */
    public static function getNavigationGroup(): ?string
    {
        return __(config('filament-messages.navigation.navigation_group'));
    }

    /**
     * Get the label for the messages page navigation item.
     *
     * Returns the value of the `filament-messages.navigation.navigation_label` config option.
     * Defaults to `'Messages'` if the option is not set.
     *
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __(config('filament-messages.navigation.navigation_label'));
    }

    /**
     * Returns the color of the navigation badge.
     *
     * Defaults to the value of the `filament.badge_color` config option.
     *
     * @return string|array|null
     */
    public static function getNavigationBadgeColor(): string | array | null
    {
        return parent::getNavigationBadgeColor();
    }

    /**
     * Returns the unread message count badge for the user in the sidebar.
     *
     * Defaults to the value of the `filament-messages.navigation.navigation_display_unread_messages_count` config option.
     * If `true`, the badge will display the unread message count for the user.
     * If `false`, the badge will not be displayed.
     *
     * @return string|null
     */
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

    /**
     * Returns the icon for the navigation item.
     *
     * Defaults to the value of the `filament-messages.navigation.navigation_icon` config option.
     *
     * @return string|Htmlable|null
     */
    public static function getNavigationIcon(): string | Htmlable | null
    {
        return config('filament-messages.navigation.navigation_icon');
    }

    /**
     * Returns the navigation sort value for the messages page.
     *
     * Defaults to the value of the `filament-messages.navigation.navigation_sort` config option.
     *
     * @return int|null
     */
    public static function getNavigationSort(): ?int
    {
        return config('filament-messages.navigation.navigation_sort');
    }

    /**
     * Mount the component.
     *
     * If an ID is provided, set the `selectedConversation` property to the matching Inbox model.
     *
     * @param int|null $id The ID of the Inbox model to select.
     * @return void
     */
    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->selectedConversation = Inbox::findOrFail($id);
        }
    }

    /**
     * Get the title of the messages page.
     *
     * Defaults to the value of the `filament-messages.navigation.navigation_label` config option.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return __(config('filament-messages.navigation.navigation_label'));
    }

    /**
     * Get the maximum content width of the messages page.
     *
     * Defaults to the value of the `filament-messages.max_content_width` config option.
     *
     * @return \Filament\Support\Enums\MaxWidth|string|null
     */
    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return config('filament-messages.max_content_width');
    }

    /**
     * Gets the heading of the messages page.
     *
     * @return string|Htmlable
     */
    public function getHeading(): string | Htmlable
    {
        return '';
    }
}
