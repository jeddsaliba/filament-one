@php
    use App\Filament\Pages\Messages;
    use App\Enums\MediaCollectionType;
@endphp

@props(['selectedConversation'])
<div wire:poll.visible.{{ $pollInterval }}="loadConversations" style="--col-span-default: span 1 / span 1; height: inherit" class="col-[--col-span-default] bg-white shadow-sm rounded-xl ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10 p-6">
    <div class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(5, minmax(0, 1fr));">
        <div style="--col-span-default: span 3 / span 3;" class="col-[--col-span-default]">
            <div class="flex gap-6">
                <p class="text-lg font-bold">{{__('Inbox')}}</p>
                @if ($this->unreadCount() > 0)
                    <x-filament::badge>
                        {{ $this->unreadCount() }}
                    </x-filament::badge>
                @endif
            </div>
        </div>
        <div style="--col-span-default: span 2 / span 2;" class="col-[--col-span-default]">
            {{ $this->createConversation }}
        </div>
        <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
            <x-filament::input.wrapper suffix-icon="heroicon-o-magnifying-glass">
                <x-filament::input type="text" placeholder="{{__('Search messages...')}}" x-on:click="$dispatch('open-modal', { id: 'search-conversation' })"/>
            </x-filament::input.wrapper>
        </div>
    </div>
    <livewire:messages.search/>

    <!-- Inbox : Start -->
    <div @style([
            'height: calc(100% - 120px)' => $this->conversations->count() > 0
        ])
        @class([
            'flex-1 overflow-y-auto' => $this->conversations->count() > 0,
        ])
    >
        @if ($this->conversations->count() > 0)
            <div class="grid w-full">
                @foreach ($this->conversations as $conversation)
                    <a wire:key="{{ $conversation->id }}" wire:navigate
                        href="{{ Messages::getUrl(tenant: filament()->getTenant()) . '/' . $conversation->id }}"
                        @class([
                            'p-2 rounded-xl w-full mb-2',
                            'hover:bg-gray-100 hover:bg-gray-100 dark:hover:bg-white/10' => $conversation->id != $selectedConversation?->id,
                            'bg-gray-100 dark:bg-white/10 dark:text-white' => $conversation->id == $selectedConversation?->id,
                            'bg-gray-100 dark:bg-white/10' => !in_array(auth()->id(), $conversation->latestMessage()->read_by)
                        ])>
                        <div class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg]" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(6, minmax(0, 1fr));">
                            <div style="--col-span-default: span 5 / span 5;" class="col-[--col-span-default]">
                                <div class="flex gap-2">
                                    @php
                                        $avatar = "https://ui-avatars.com/api/?name=" . urlencode($conversation->inbox_title);
                                        $alt = urlencode($conversation->inbox_title);
                                    @endphp
                                    @if ($conversation->other_users->count() == 1)
                                        @if ($conversation->other_users->first()?->getFirstMediaUrl('*'))
                                            @php
                                                $avatar = $conversation->other_users->first()?->getFirstMediaUrl('*');
                                                $alt = $conversation->other_users->first()?->name;
                                            @endphp
                                        @endif
                                    @endif
                                    <x-filament::avatar
                                        src="{{ $avatar }}"
                                        alt="{{ $alt }}" size="lg" />
                                    <div class="overflow-hidden">
                                        <p
                                            @class([
                                                'text-sm font-semibold truncate',
                                                'italic text-gray-900' => !in_array(auth()->id(), $conversation->latestMessage()->read_by)
                                            ])
                                        >{{ $conversation->inbox_title }}</p>
                                        <p
                                            @class([
                                                'text-sm truncate dark:text-gray-400',
                                                'text-gray-600' => in_array(auth()->id(), $conversation->latestMessage()->read_by),
                                                'italic text-gray-900' => !in_array(auth()->id(), $conversation->latestMessage()->read_by)
                                            ])
                                        >
                                            <span class="font-bold">
                                                {{ $conversation->latestMessage()->user_id == auth()->id() ? 'You:' : $conversation->latestMessage()->sender->name . ':' }}
                                            </span>
                                            @if ($conversation->latestMessage()->getMedia(MediaCollectionType::FILAMENT_MESSAGES->value) && count($conversation->latestMessage()->getMedia(MediaCollectionType::FILAMENT_MESSAGES->value)) > 0)
                                                {{ count($conversation->latestMessage()->getMedia(MediaCollectionType::FILAMENT_MESSAGES->value)) > 1 ? 'Attachments' : 'Attachment' }}
                                            @else
                                                {{ $conversation->latestMessage()->message }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                <p
                                    @class([
                                        'text-sm font-light text-end',
                                        'text-gray-600 dark:text-gray-500' => in_array(auth()->id(), $conversation->latestMessage()->read_by),
                                        'italic font-semibold text-gray-900 dark:text-gray-500' => !in_array(auth()->id(), $conversation->latestMessage()->read_by)
                                    ])
                                >
                                    {{ \Carbon\Carbon::parse($conversation->updated_at)->setTimezone(config('filament-messages.timezone', 'app.timezone'))->shortAbsoluteDiffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-full p-3">
                <div class="p-3 mb-4 bg-gray-100 rounded-full dark:bg-gray-500/20">
                    <x-filament::icon icon="heroicon-o-x-mark" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                </div>
                <p class="text-base text-center text-gray-600 dark:text-gray-400">
                    {{__('No conversations yet')}}
                </p>
            </div>
        @endif
    </div>
    <!-- Inbox : End -->
    <x-filament-actions::modals />
</div>
