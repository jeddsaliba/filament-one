<?php

namespace App\Livewire\Messages;

use App\Enums\MediaCollectionType;
use App\Livewire\Traits\CanMarkAsRead;
use App\Livewire\Traits\CanValidateFiles;
use App\Livewire\Traits\HasPollInterval;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Messages extends Component implements HasForms
{
    use CanMarkAsRead, CanValidateFiles, HasPollInterval, InteractsWithForms, WithPagination;

    public $selectedConversation;

    public $currentPage = 1;

    public Collection $conversationMessages;

    public ?array $data = [];

    public bool $showUpload = false;

    /**
     * Initialize the Messages component.
     *
     * This method is called when the component is mounted.
     * It sets the polling interval, fills the form state, and 
     * if a conversation is selected, initializes the conversation
     * messages, loads existing messages, and marks them as read.
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->setPollInterval();
        $this->form->fill();
        if ($this->selectedConversation) {
            $this->conversationMessages = collect();
            $this->loadMessages();
            $this->markAsRead();
        }
    }

    /**
     * Poll for new messages in the selected conversation.
     *
     * This method retrieves messages that are newer than the
     * latest message currently loaded in the conversation.
     * If new messages are found, they are prepended to the
     * existing collection of conversation messages.
     *
     * @return void
     */
    public function pollMessages(): void
    {
        $latestId = $this->conversationMessages->pluck('id')->first();
        $polledMessages = $this->selectedConversation->messages()->where('id', '>', $latestId)->latest()->get();
        if ($polledMessages->isNotEmpty()) {
            $this->conversationMessages = collect([
                ...$polledMessages,
                ...$this->conversationMessages
            ]);
        }
    }

    /**
     * Load the next page of messages for the selected conversation.
     *
     * This method appends the messages from the next page to the
     * existing collection of conversation messages and increments
     * the current page number.
     *
     * @return void
     */
    public function loadMessages(): void
    {
        $this->conversationMessages->push(...$this->paginator->getCollection());
        $this->currentPage = $this->currentPage + 1;
    }

    /**
     * Customize the form.
     *
     * The form schema is defined in this method which is called
     * when the component is mounted. The form state is stored in
     * the 'data' property.
     *
     * @param \Filament\Forms\Form $form
     * @return \Filament\Forms\Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\SpatieMediaLibraryFileUpload::make('attachments')
                    ->hiddenLabel()
                    ->collection(MediaCollectionType::FILAMENT_MESSAGES->value)
                    ->multiple()
                    ->panelLayout('grid')
                    ->visible(fn () => $this->showUpload)
                    ->maxFiles(config('filament-messages.attachments.max_files'))
                    ->minFiles(config('filament-messages.attachments.min_files'))
                    ->maxSize(config('filament-messages.attachments.max_file_size'))
                    ->minSize(config('filament-messages.attachments.min_file_size'))
                    ->live(),
                Forms\Components\Split::make([
                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('show_hide_upload')
                            ->hiddenLabel()
                            ->icon('heroicon-o-paper-clip')
                            ->color('gray')
                            ->tooltip(__('Attach Files'))
                            ->action(fn () => $this->showUpload = !$this->showUpload),
                    ])->grow(false),
                    Forms\Components\Textarea::make('message')
                        ->live()
                        ->hiddenLabel()
                        ->rows(1)
                        ->autosize(),
                ])->verticallyAlignEnd(),
            ])->statePath('data');
    }

    /**
     * Saves the message and all the associated attachments, and refreshes the inbox and messages list.
     *
     * @return void
     * @throws \Exception
     */
    public function sendMessage(): void
    {
        $data = $this->form->getState();
        $rawData = $this->form->getRawState();

        try {
            DB::transaction(function () use ($data, $rawData) {
                $this->showUpload = false;

                $newMessage = $this->selectedConversation->messages()->create([
                    'message' => $data['message'] ?? null,
                    'user_id' => Auth::id(),
                    'read_by' => [Auth::id()],
                    'read_at' => [now()],
                    'notified' => [Auth::id()],
                ]);

                $this->conversationMessages->prepend($newMessage);
                collect($rawData['attachments'])->each(function ($attachment) use ($newMessage) {
                    $newMessage->addMedia($attachment)->usingFileName(Str::slug(config('filament-messages.slug'), '_') . '_' . Str::random(20) .'.'.$attachment->extension())->toMediaCollection(MediaCollectionType::FILAMENT_MESSAGES->value);
                });

                $this->form->fill();

                $this->selectedConversation->updated_at = now();

                $this->selectedConversation->save();

                $this->dispatch('refresh-inbox');
            });
        } catch (\Exception $exception) {
            Notification::make()
                ->title(__('Something went wrong'))
                ->body($exception->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }

    /**
     * Computes the paginator for the conversation messages.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    #[Computed()]
    public function paginator(): \Illuminate\Contracts\Pagination\Paginator
    {
        return $this->selectedConversation->messages()->latest()->paginate(10, ['*'], 'page', $this->currentPage);
    }

    /**
     * Download an attachment from the given file path and return it as a response.
     *
     * @param string $filePath
     * @param string $fileName
     * @return \Illuminate\Http\Response
     */
    public function downloadAttachment(string $filePath, string $fileName)
    {
        return response()->download($filePath, $fileName);
    }

    /**
     * Determines if the message input is valid.
     *
     * @return bool
     */
    public function validateMessage(): bool
    {
        $rawData = $this->form->getRawState();
        if (empty($rawData['attachments']) && !$rawData['message']) {
            return true;
        }
        return false;
    }

    /**
     * Render the messages view for the Livewire component.
     *
     * This method returns the view responsible for displaying
     * the messages interface, which includes the chat box and
     * input area for sending messages.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.messages.messages');
    }
}
