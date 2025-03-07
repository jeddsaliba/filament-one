<?php

namespace App\Livewire\Traits;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

trait CanMarkAsRead
{
    public function markAsRead()
    {
        $this->selectedConversation?->messages()->each(function (Message $message) {
            $message->where(['inbox_id' => $message->inbox_id])->whereJsonDoesntContain('read_by', Auth::id())
                ->update([
                    'read_by' => [
                        ...$message->read_by,
                        Auth::id()
                    ],
                    'read_at' => [
                        ...$message->read_at,
                        now()
                    ]
                ]);
        });
    }
}
