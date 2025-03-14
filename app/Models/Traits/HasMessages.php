<?php

namespace App\Models\Traits;

use App\Models\Inbox;

trait HasMessages
{
    /**
     * Retrieves all conversations for the current user.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allConversations()
    {
        return Inbox::whereJsonContains('user_ids', $this->id)->orderBy('updated_at', 'desc');
    }
}
