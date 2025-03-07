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

    public function mount(): void
    {
        $this->messages = collect();
    }

    #[On('close-modal')]
    public function clearSearch(): void
    {
        $this->search = '';
        $this->updatedSearch();
    }

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

    public function render()
    {
        return view('livewire.messages.search', [
            'messages' => $this->messages,
        ]);
    }
}
