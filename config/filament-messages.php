<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation Properties
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        /*
        |--------------------------------------------------------------------------
        | Show Menu Item
        |--------------------------------------------------------------------------
        |
        | This setting determines whether the plugin adds a menu item to the sidebar.
        | If disabled, you can manually add a navigation item elsewhere in the panel.
        |
        */
        'show_in_menu' => true,

        /*
        |--------------------------------------------------------------------------
        | Navigation Group
        |--------------------------------------------------------------------------
        |
        | This setting defines the navigation group displayed in the sidebar.
        */
        'navigation_group' => null,

        /*
        |--------------------------------------------------------------------------
        | Navigation Label
        |--------------------------------------------------------------------------
        |
        | This setting defines the navigation label shown in the sidebar.
        */
        'navigation_label' => 'Messages',

        /*
        |--------------------------------------------------------------------------
        | Navigation Badge
        |--------------------------------------------------------------------------
        |
        | This setting determines the unread message count badge for the user in the sidebar.
        */
        'navigation_display_unread_messages_count' => true,

        /*
        |--------------------------------------------------------------------------
        | Navigation Icon
        |--------------------------------------------------------------------------
        |
        | This setting defines the navigation icon for the chat section.
        | You can customize it if your application uses a different icon.
        |
        */
        'navigation_icon' => 'heroicon-o-chat-bubble-left-right',

        /*
        |--------------------------------------------------------------------------
        | Navigation Sort
        |--------------------------------------------------------------------------
        |
        | This setting defines the sort order for the chat navigation.
        | You can customize it to match your application's preferred order.
        |
        */

        'navigation_sort' => 1,
    ],
    /*
    |--------------------------------------------------------------------------
    | Attachment Properties
    |--------------------------------------------------------------------------
    */
    'attachments' => [
        /*
        | Set the maximum/minimum file size and maximum/minimum number of files that
        | can be attached to each message.
        */
        'max_file_size' => 5120, /** Default max file size: 5mb */
        'min_file_size' => 1, /** Default min file size: 0mb */
        'max_files' => 5, /** Default max files: 10 */
        'min_files' => 0, /** Default min files: 0 */
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Slug
    |--------------------------------------------------------------------------
    |
    | This option specifies the route slug for the chat system,
    | allowing customization if your application uses a different one.
    |
    */
    'slug' => 'messages',

    /*
    |--------------------------------------------------------------------------
    | Max Content Width
    |--------------------------------------------------------------------------
    |
    | This setting defines the maximum width of the chat page,
    | which can be customized to match your application's layout.
    | You can use any enum value from \Filament\Support\Enums\MaxWidth.
    |
    */
    'max_content_width' => \Filament\Support\Enums\MaxWidth::Full,

    /*
    |--------------------------------------------------------------------------
    | Timezone
    |--------------------------------------------------------------------------
    |
    | This setting defines the timezone for the chat system,
    | which can be customized to match your application's timezone.
    | Refer to the supported timezones here: https://www.php.net/manual/en/timezones.php
    |
    */
    'timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Poll Interval
    |--------------------------------------------------------------------------
    |
    | This setting determines how often the chat refreshes.
    | You can customize the interval to fit your application's needs.
    | For more details on poll intervals, visit:: https://livewire.laravel.com/docs/wire-poll
    |
    */
    'poll_interval' => '5s',
];
