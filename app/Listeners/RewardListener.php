<?php

namespace App\Listeners;

use App\Events\RewardUser;
use App\Services\WalletService;
use Illuminate\Support\Facades\Log;

class RewardListener
{
    /**
     * @param WalletService $walletService
     */
    public function __construct(private readonly WalletService $walletService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(RewardUser $event): void
    {
        Log::info('User rewarded', $event->toArray());

        $this->walletService->createTransaction($event->getUser(), $event->getType(), $event->getPoints());
    }
}
