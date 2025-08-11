<?php

namespace App\Console\Commands;

use App\Services\StatisticsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class GlobalStatsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cron:global-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate global stats for the platform';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = now()->format('Y-m-d');

        /** @var StatisticsService $service */
        $service = App::make(StatisticsService::class);

        try {
            $service->calculateStatsForDay($date);
        } catch (\Throwable $exception) {
            Log::error("Failed to calculate global stats for $date" . $exception->getMessage());
        }
    }
}
