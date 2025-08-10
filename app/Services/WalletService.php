<?php

namespace App\Services;

use App\Enums\Currency;
use App\Mail\TransactionClaimed;
use App\Models\PointsTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WalletService
{
    /**
     * @param User $user
     * @param PointsTransaction $transaction
     * @return void
     * @throws \Exception
     */
    public function claimTransaction(User $user, PointsTransaction $transaction): void
    {
        if ($transaction->claimed) {
            throw new \Exception('Transaction already claimed');
        }

        /** @var Wallet $wallet */
        $wallet = $user->wallet()->first();

        $convertedPoints = $this->convertPointsToCurrency($wallet->currency, $transaction);
        $wallet->amount += $convertedPoints;
        $wallet->save();
        Log::info('Wallet updated', $wallet->toArray());


        $transaction->update(['claimed' => true]);
        Log::info('Transaction claimed', $transaction->toArray());

        try {
            Mail::to($user->email)->send(new TransactionClaimed($transaction));
        } catch (\Throwable $throwable) {
            Log::error('Failed to send email: ' . $throwable->getMessage());
        }
    }

    /**
     * @param string $currency
     * @param PointsTransaction $transaction
     * @return float
     * @throws \Exception
     */
    private function convertPointsToCurrency(string $currency, PointsTransaction $transaction): float
    {
        $rate = match ($currency) {
            Currency::USD->value => 0.01,
            Currency::EUR->value => 0.012,
            Currency::YEN->value => 0.001,
            default => throw new \Exception('Invalid currency'),
        };

        return $transaction->points * $rate;
    }

    /**
     * @param User $user
     * @param string $type
     * @param int $amount
     * @return void
     */
    public function createTransaction(User $user, string $type, int $amount): void
    {
        $user->transactions()->create(['type' => $type, 'points' => $amount]);
    }
}
