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

    /**
     * Initialize the component.
     *
     * This method is called when the component is mounted,
     * and is used to set the poll interval and load the conversations.
     */
    public function mount(): void
    {
        $this->setPollInterval();
        $this->loadConversations();
    }

    /**
     * Returns the number of unread messages in all conversations.
     *
     * Uses the message's `read_by` array to determine if the current user has
     * already read the message.
     *
     * @return int
     */
    public function unreadCount(): int
    {
        return \App\Models\Inbox::whereJsonContains('user_ids', Auth::id())
            ->whereHas('messages', function ($query) {
                $query->whereJsonDoesntContain('read_by', Auth::id());
            })->get()->count();
    }

    /**
     * Load the conversations for the current user.
     *
     * This method is called when the poll interval is reached,
     * and is used to refresh the conversations list.
     *
     * @return void
     */
    #[On('refresh-inbox')]
    public function loadConversations(): void
    {
        $this->conversations = Auth::user()->allConversations()->get();
        $this->markAsRead();
    }

    /**
     * Define the action for creating a new conversation.
     *
     * This action is shown in the inbox page, and is used to create a new conversation.
     *
     * The form contains a select box to select the users to send the message to,
     * a text input to enter the group name (visible only if multiple users are selected),
     * a textarea to enter the message, and a submit button to send the message.
     *
     * When the form is submitted, the action creates a new conversation (if it doesn't exist already),
     * and adds a new message to the conversation.
     *
     * @return \Filament\Actions\Action
     */
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

    /**
     * Render the inbox view for livewire messages.
     *
     * This method returns the view responsible for displaying the inbox
     * component, which includes the list of conversations and the interface
     * for interacting with them.
     *
     * @return Application|Factory|View|\Illuminate\View\View
     */
    public function render(): Application | Factory | View | \Illuminate\View\View
    {
        return view('livewire.messages.inbox');
    }
}