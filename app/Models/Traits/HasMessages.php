<?php

namespace App\Models\Traits;

use App\Models\Inbox;

trait HasMessages
{
    public function allConversations()
    {
        return Inbox::whereJsonContains('user_ids', $this->id)->orderBy('updated_at', 'desc');
    }
}
