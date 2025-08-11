<?php

namespace App\Services;

use App\Enums\Currency;
use App\Models\PlatformStatistic;
use App\Models\PointsTransaction;
use Illuminate\Support\Facades\Log;

class StatisticsService
{
    /**
     * @param WalletService $walletService
     */
    public function __construct(private readonly WalletService $walletService)
    {
    }

    /**
     * @param string $date
     * @return void
     * @throws \Exception
     */
    public function calculateStatsForDay(string $date): void
    {
        $pointsCreated = PointsTransaction::whereDate('created_at', $date)->sum('points');
        $pointsClaimed = PointsTransaction::whereDate('updated_at', $date)
            ->where('claimed', true)
            ->sum('points');
        $usdClaimed = $this->walletService->convertPointsToCurrency(Currency::USD->value, $pointsClaimed);

        $stats = [
            'points_created' => $pointsCreated,
            'points_claimed' => $pointsClaimed,
            'usd_claimed' => $usdClaimed,
        ];

        PlatformStatistic::updateOrCreate(['date' => $date], $stats);

        Log::info("Statistics updated for date $date", $stats);
    }
}
