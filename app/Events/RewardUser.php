<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RewardUser
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @param User $user
     * @param string $type
     * @param int $points
     */
    public function __construct(
        private readonly User $user,
        private readonly string $type,
        private readonly int $points
    ) {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'user' => $this->user->id,
            'transaction_type' => $this->type,
            'points' => $this->points,
        ];
    }
}
