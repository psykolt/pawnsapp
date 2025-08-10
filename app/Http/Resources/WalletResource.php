<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Wallet $wallet */
        $wallet = $this->wallet()->first();

        return [
            'amount' => $wallet->amount,
            'currency' => $wallet->currency,
            'unclaimed_points' => (int)$this->transactions()->where('claimed', false)->sum('points'),
        ];
    }
}
