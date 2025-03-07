@props(['selectedConversation'])
<!-- Right Section (Chat Box) -->
<div
    style="--col-span-default: span 3 / span 3;"
    class="col-[--col-span-default] bg-white shadow-sm rounded-xl ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10 overflow-hidden flex flex-col">
    @if ($selectedConversation)
        <!-- Chat Header : Start -->
        <div class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] p-6" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(1, minmax(0, 1fr));">
            <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
                <div class="flex gap-6 items-center">
                    @php
                        $avatar = "https://ui-avatars.com/api/?name=" . urlencode($selectedConversation->inbox_title);
                        $alt = urlencode($selectedConversation->inbox_title);
                    @endphp
                    @if ($selectedConversation->other_users->count() == 1)
                        @if ($selectedConversation->other_users->first()?->getFirstMediaUrl('*'))
                            @php
                                $avatar = $selectedConversation->other_users->first()?->getFirstMediaUrl('*');
                                $alt = $selectedConversation->other_users->first()?->name;
                            @endphp
                        @endif
                    @endif
                    <x-filament::avatar
                        src="{{ $avatar }}"
                        alt="{{ $alt }}" size="lg" />
                    <div class="overflow-hidden">
                        <p class="text-base font-bold truncate">{{ $selectedConversation->inbox_title }}</p>
                        @if ($selectedConversation->title)
                            <p class="text-base truncate">{{ $selectedConversation->other_users->pluck('name')->implode(', ') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Chat Header : End -->
        <!-- Chat Box : Start -->
        <div wire:poll.visible.{{ $pollInterval }}="pollMessages" id="chatContainer" class="flex flex-col-reverse flex-1 p-5 overflow-y-auto border-t">
            @foreach ($conversationMessages as $index => $message)
                <div
                    @class([
                        'flex mb-2 px-2 items-end gap-2',
                        'justify-end' => $message->user_id === auth()->id(),
                        'justify-start' => $message->user_id !== auth()->id()
                    ]) wire:key="{{ $message->id }}">
                    @if ($message->user_id !== auth()->id())
                        @php
                            $avatar = "https://ui-avatars.com/api/?name=" . urlencode($message->sender->name);
                            $alt = urlencode($message->sender->name);
                        @endphp
                        @if ($message->sender?->getFirstMediaUrl('*'))
                            @php
                                $avatar = $message->sender?->getFirstMediaUrl('*');
                                $alt = $message->sender?->name;
                            @endphp
                        @endif
                        <x-filament::avatar
                            src="{{ $avatar }}"
                            alt="{{ $alt }}" size="sm"
                        />
                    @endif
                    <div>
                        @if ($message->user_id !== auth()->id())
                            <p class="text-xs mb-2">{{ $message->sender->name }}</p>
                        @endif
                        <div
                            @class([
                                'max-w-md p-2 rounded-xl mb-2',
                                'text-white bg-primary-600 dark:bg-primary-500' => $message->user_id === auth()->id(),
                                'text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-500' => $message->user_id !== auth()->id(),
                            ])
                            @style([
                                'border-bottom-right-radius: 0' => $message->user_id === auth()->id(),
                                'border-bottom-left-radius: 0' => $message->user_id !== auth()->id(),
                            ])>
                            <div class="px-1">
                                @if ($message->message)
                                    <p class="text-sm">{!! nl2br($message->message) !!}</p>
                                @endif
                                @if ($message->getMedia() && count($message->getMedia()) > 0)
                                    @foreach ($message->getMedia() as $index => $media)
                                        <div wire:click="downloadAttachment('{{ $media->getPath() }}', '{{ $media->file_name }}')"
                                            @class([
                                                'flex items-center gap-2 p-2 my-2 rounded-lg group cursor-pointer',
                                                'bg-gray-200 dark:bg-gray-600' => $message->user_id !== auth()->id(),
                                                'bg-primary-500 dark:bg-primary-400' => $message->user_id === auth()->id()
                                            ])
                                            >
                                            <div
                                                @class([
                                                    'p-2 rounded-full',
                                                    'bg-gray-100 dark:bg-gray-500' => $message->user_id !== auth()->id(),
                                                    'bg-primary-600 group-hover:bg-primary-700 group-hover:dark:bg-primary-900' => $message->user_id === auth()->id()
                                                ])
                                            >
                                                @php
                                                    $icon = 'heroicon-o-x-circle';
                                                    if($this->validateImage($media->getFullUrl())) {
                                                        $icon = 'heroicon-o-photo';
                                                    }

                                                    if ($this->validateDocument($media->getFullUrl())) {
                                                        $icon = 'heroicon-o-paper-clip';
                                                    }

                                                    if ($this->validateVideo($media->getFullUrl())) {
                                                        $icon = 'heroicon-o-video-camera';
                                                    }

                                                    if ($this->validateAudio($media->getFullUrl())) {
                                                        $icon = 'heroicon-o-speaker-wave';
                                                    }
                                                @endphp
                                                <x-filament::icon icon="{{ $icon }}" class="w-4 h-4" />
                                            </div>
                                            <p class="text-sm">
                                                {{ $media->file_name }}
                                            </p>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <p
                            @class([
                                'text-xs',
                                'text-end' => $message->user_id === auth()->id(),
                                'text-start' => $message->user_id !== auth()->id()
                            ])>
                            @php
                                $createdAt = \Carbon\Carbon::parse($message->created_at)->setTimezone(config('filament-messages.timezone', 'app.timezone'));

                                if ($createdAt->isToday()) {
                                    $date = $createdAt->format('g:i A');
                                } else {
                                    $date = $createdAt->format('M d, Y g:i A');
                                }
                            @endphp
                            {{ $date }}
                        </p>
                    </div>
                </div>
                @php
                    $nextMessage = $conversationMessages[$index + 1] ?? null;
                    $nextMessageDate = $nextMessage ? \Carbon\Carbon::parse($nextMessage->created_at)->setTimezone(config('filament-messages.timezone', 'app.timezone'))->format('Y-m-d') : null;
                    $currentMessageDate = \Carbon\Carbon::parse($message->created_at)->setTimezone(config('filament-messages.timezone', 'app.timezone'))->format('Y-m-d');
                    $showDateBadge = $currentMessageDate !== $nextMessageDate;
                @endphp
                @if ($showDateBadge)
                    <div class="flex justify-center my-4">
                        <x-filament::badge>
                            {{ \Carbon\Carbon::parse($message->created_at)->setTimezone(config('filament-messages.timezone', 'app.timezone'))->format('F j, Y') }}
                        </x-filament::badge>
                    </div>
                @endif
            @endforeach
            @if ($this->paginator->hasMorePages())
                <div x-intersect="$wire.loadMessages">
                    <div class="w-full py-6 text-center text-gray-900">{{__('Getting more messages...')}}</div>
                </div>
            @endif
        </div>
        <!-- Chat Box : End -->
        <!-- Chat Input : Start -->
        <div class="w-full p-4 border-t">
            <form wire:submit="sendMessage" class="flex items-end justify-between w-full gap-4">
                <div class="w-full max-h-96 overflow-y-auto p-1">
                    {{ $this->form }}
                </div>
                <div class="p-1">
                    <x-filament::button type="submit" icon="heroicon-o-paper-airplane" :disabled="$this->validateMessage()">Send</x-filament::button>
                </div>
            </form>
            <x-filament-actions::modals />
        </div>
        <!-- Chat Input : End -->
    @else
        <div class="flex flex-col items-center justify-center h-full p-3">
            <div class="p-3 mb-4 bg-gray-100 rounded-full dark:bg-gray-500/20">
                <x-filament::icon icon="heroicon-o-x-mark" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
            </div>
            <p class="text-base text-center text-gray-600 dark:text-gray-400">
                {{__('No selected conversation')}}
            </p>
        </div>
    @endif
</div>
@script
<script>
    $wire.on('chat-box-scroll-to-bottom', () => {

        chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTo({
            top: chatContainer.scrollHeight,
            behavior: 'smooth',
        });

        setTimeout(() => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 400);
    });
</script>
@endscript
