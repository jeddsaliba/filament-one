@php
    use App\Filament\Pages\Messages;
@endphp
<x-filament::modal width="xl" id="search-conversation">
    <x-slot name="heading">
        {{__('Search Messages')}}
    </x-slot>

    <x-filament::input.wrapper suffix-icon="heroicon-o-magnifying-glass">
        <x-filament::input type="search" placeholder="{{__('Search messages...')}}" wire:model.live.debounce.500ms="search"/>
    </x-filament::input.wrapper>

    @if(count($messages) > 0)
        <div class="relative">
            <ul class="bg-white dark:bg-gray-900 divide-y dark:divide-white/10">
                @foreach($messages as $message)
                    <li wire:key="{{ $message->id }}" class="hover:bg-gray-100 hover:bg-gray-100 dark:hover:bg-white/10">
                        <a wire:navigate href="{{ Messages::getUrl(tenant: filament()->getTenant()) . '/' . $message->inbox->id }}">
                            <div class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] p-3" style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(5, minmax(0, 1fr));">
                                <div style="--col-span-default: span 4 / span 4;" class="col-[--col-span-default]">
                                    <div class="flex gap-3">
                                        @php
                                            $avatar = "https://ui-avatars.com/api/?name=" . urlencode($message->inbox->inbox_title);
                                            $alt = urlencode($message->inbox->inbox_title);
                                        @endphp
                                        @if ($message->inbox->other_users->count() == 1)
                                            @if ($message->inbox->other_users->first()?->getFirstMediaUrl('*'))
                                                @php
                                                    $avatar = $message->inbox->other_users->first()?->getFirstMediaUrl('*');
                                                    $alt = $message->inbox->other_users->first()?->name;
                                                @endphp
                                            @endif
                                        @endif
                                        <x-filament::avatar
                                            src="{{ $avatar }}"
                                            alt="{{ $alt }}" size="lg" />
                                        <div class="overflow-hidden">
                                            <p class="text-sm font-semibold truncate">{{ $message->inbox->inbox_title }}</p>
                                            <p class="text-sm text-gray-600 truncate dark:text-gray-400">
                                                @if ($message->user_id == auth()->id())
                                                    <span class="font-bold">You:</span>
                                                @else
                                                    <span class="font-bold">{{ $message->sender->name }}:</span>
                                                @endif
                                                {{ $message->message }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div style="--col-span-default: span 1 / span 1;" class="col-[--col-span-default]">
                                    <p class="text-sm font-light text-gray-600 dark:text-gray-500 text-end">
                                        {{ \Carbon\Carbon::parse($message->updated_at)->setTimezone(config('filament-messages.timezone', 'app.timezone'))->format('F j, Y') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @elseif(!empty($search))
        <div class="relative">
           <div class="absolute z-10 w-full bg-white border dark:divide-gray-800 border-gray-200 rounded-lg shadow dark:border-gray-800 dark:bg-gray-900 max-h-64 overflow-y-auto">
                <p class="w-full p-3 text-sm text-center text-gray-500 dark:text-gray-400">
                    {{__('No results found')}}
                </p>
            </div>
        </div>
    @endif
</x-filament::modal>
