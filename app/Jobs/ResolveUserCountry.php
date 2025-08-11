<?php

namespace App\Jobs;

use App\Models\User;
use App\Modules\Proxycheck\ProxycheckService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ResolveUserCountry implements ShouldQueue
{
    use Queueable;

    /**
     * @param User $user
     * @param string $ip
     */
    public function __construct(private readonly User $user, private readonly string $ip)
    {
    }

    /**
     * @param ProxycheckService $proxycheckService
     * @return void
     */
    public function handle(ProxycheckService $proxycheckService): void
    {
        $country = $proxycheckService->getCountryByIp($this->ip);

        if ($country) {
            $this->user->update(['country' => $country]);
            Log::info('User country resolved', ['user' => $this->user->id, 'country' => $country]);
        }
    }
}
