<?php

namespace App\Livewire\Messages;

use App\Livewire\Traits\CanMarkAsRead;
use App\Livewire\Traits\CanValidateFiles;
use App\Livewire\Traits\HasPollInterval;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Inbox extends Component implements HasActions, HasForms
{
    use CanMarkAsRead, CanValidateFiles, HasPollInterval, InteractsWithActions, InteractsWithForms;

    public $conversations;

    public $selectedConversation;

    public function mount(): void
    {
        $this->setPollInterval();
        $this->loadConversations();
    }

    public function unreadCount()
    {
        return \App\Models\Inbox::whereJsonContains('user_ids', Auth::id())
            ->whereHas('messages', function ($query) {
                $query->whereJsonDoesntContain('read_by', Auth::id());
            })->get()->count();
    }

    #[On('refresh-inbox')]
    public function loadConversations(): void
    {
        $this->conversations = Auth::user()->allConversations()->get();
        $this->markAsRead();
    }

    public function createConversationAction(): Action
    {
        return Action::make('createConversation')
            ->icon('heroicon-o-plus')
            ->label(__('Create'))
            ->form([
                Forms\Components\Select::make('user_ids')
                    ->label(__('Select User(s)'))
                    ->options(fn () => \App\Models\User::whereNotIn('id', [Auth::id()])->get()->pluck('name', 'id'))
                    ->multiple()
                    ->preload(false)
                    ->searchable()
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('title')
                    ->label(__('Group Name'))
                    ->visible(function (Forms\Get $get) {
                        return collect($get('user_ids'))->count() > 1;
                    }),
                Forms\Components\Textarea::make('message')
                    ->placeholder(__('Write a message...'))
                    ->required()
                    ->autosize(),
            ])
            ->modalHeading(__('Create New Message'))
            ->modalSubmitActionLabel(__('Send'))
            ->modalWidth(MaxWidth::Large)
            ->action(function (array $data) {
                $userIds = collect($data['user_ids'])->push(Auth::id())->map(fn ($userId) => (int)$userId);
                $totalUserIds = $userIds->count();
                $inbox = \App\Models\Inbox::whereRaw("JSON_CONTAINS(user_ids, \"$userIds\") AND JSON_LENGTH(user_ids) = $totalUserIds")->first();
                $inboxId = null;
                if (!$inbox) {
                    $inbox = \App\Models\Inbox::create([
                        'title' => $data['title'] ?? null,
                        'user_ids' => $userIds
                    ]);
                    $inboxId = $inbox->getKey();
                } else {
                    $inbox->updated_at = now();
                    $inbox->save();
                    $inboxId = $inbox->getKey();
                }
                $inbox->messages()->create([
                    'message' => $data['message'],
                    'user_id' => Auth::id(),
                    'read_by' => [Auth::id()],
                    'read_at' => [now()],
                    'notified' => [Auth::id()],
                ]);
                redirect()->route('filament.admin.pages.filament-messages.{id?}', ['id' => $inboxId]);
            })->extraAttributes([
                'class' => 'w-full'
            ]);
    }

    public function render(): Application | Factory | View | \Illuminate\View\View
    {
        return view('livewire.messages.inbox');
    }
}