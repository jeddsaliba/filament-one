<?php

namespace App\Livewire\Traits;

trait HasPollInterval
{
    public $pollInterval = '5s';

    /**
     * Set the polling interval for the component.
     *
     * This method retrieves the poll interval from the configuration
     * file `filament-messages.php` and sets it to the `pollInterval`
     * property. If the configuration is not set, it defaults to '5s'.
     *
     * @return void
     */
    public function setPollInterval(): void
    {
        $this->pollInterval = config('filament-messages.poll_interval', '5s');
    }
}
