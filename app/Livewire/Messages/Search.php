<?php

namespace App\Livewire\Messages;

use App\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Search extends Component
{
    public $search = '';

    public Collection $messages;

    /**
     * Set the initial state of the component.
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->messages = collect();
    }

    /**
     * Resets the search input value and updates the list of messages accordingly.
     *
     * This method is called when the modal is closed.
     * 
     * @return void
     */
    #[On('close-modal')]
    public function clearSearch(): void
    {
        $this->search = '';
        $this->updatedSearch();
    }

    /**
     * Updates the list of messages when the search input is changed.
     * 
     * If the search input is not empty, it will search for messages that contain the search
     * query and update the list of messages accordingly.
     *
     * @return void
     */
    public function updatedSearch(): void
    {
        $search = trim($this->search);
        $this->messages = collect();
        if (!empty($search)) {
            $this->messages = Message::query()
                ->with(['inbox'])
                ->whereHas('inbox', function ($query) {
                    $query->whereJsonContains('user_ids', Auth::id());
                })
                ->where('message', 'like', "%$search%")
                ->limit(5)
                ->latest()
                ->get();
        }
    }

    /**
     * Renders the search component.
     * 
     * The component displays a list of messages that match the search query.
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.messages.search', [
            'messages' => $this->messages,
        ]);
    }
}
