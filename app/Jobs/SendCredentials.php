<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\SendCredentialsNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendCredentials implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public $password)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('SendCredentials running...');
        $this->user->notify(new SendCredentialsNotification($this->password));
    }
}
