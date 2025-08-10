<?php

namespace App\Listeners;

use App\Events\RewardUser;
use Illuminate\Support\Facades\Log;

class RewardListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(RewardUser $event): void
    {
        Log::info('User rewarded', $event->toArray());
    }
}
