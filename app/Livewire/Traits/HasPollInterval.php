<?php

namespace App\Livewire\Traits;

trait HasPollInterval
{
    public $pollInterval = '5s';

    public function setPollInterval()
    {
        $this->pollInterval = config('filament-messages.poll_interval', '5s');
    }
}
