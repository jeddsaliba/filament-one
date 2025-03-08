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
     * 
     */
    public function pollMessages()
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

    public function loadMessages()
    {
        $this->conversationMessages->push(...$this->paginator->getCollection());
        $this->currentPage = $this->currentPage + 1;
    }

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

    #[Computed()]
    public function paginator()
    {
        return $this->selectedConversation->messages()->latest()->paginate(10, ['*'], 'page', $this->currentPage);
    }

    public function downloadAttachment(string $filePath, string $fileName)
    {
        return response()->download($filePath, $fileName);
    }

    public function validateMessage(): bool
    {
        $rawData = $this->form->getRawState();
        if (empty($rawData['attachments']) && !$rawData['message']) {
            return true;
        }
        return false;
    }

    public function render()
    {
        return view('livewire.messages.messages');
    }
}
