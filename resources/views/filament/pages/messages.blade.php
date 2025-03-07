<x-filament-panels::page>
    <div class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6"
        style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(4, minmax(0, 1fr)); height: calc(100vh - 8rem);">
        <livewire:messages.inbox :selectedConversation="$selectedConversation" />
        <livewire:messages.messages :selectedConversation="$selectedConversation" />
    </div>
</x-filament-panels::page>
