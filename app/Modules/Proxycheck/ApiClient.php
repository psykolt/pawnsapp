<?php

namespace App\Modules\Proxycheck;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use proxycheck\proxycheck;

class ApiClient
{
    private const CACHE_KEY = 'proxycheck_ip_';

    private const CACHE_FOR_DAYS = 1;

    /**
     * @var string|null
     */
    private ?string $apiKey;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->apiKey = config('proxycheck.api_key');
    }

    /**
     * @param string|null $ip
     * @return array
     */
    public function callApi(?string $ip): array
    {
        if (empty($ip)) {
            return [];
        }

        $options = [
            'VPN_DETECTION' => 1,
            'API_KEY' => $this->apiKey,
            'ASN_DATA' => 1,
        ];

        $cacheKey = self::CACHE_KEY . $ip;

        $result = Cache::remember($cacheKey, now()->addDays(self::CACHE_FOR_DAYS), function () use ($ip, $options) {
            $response = proxycheck::check($ip, $options);
            Log::info("Proxycheck API call result for $ip", $response);

            return $response;
        });

        if ($result['status'] === 'error') {
            Log::error("Proxycheck error for $ip", $result);
            return [];
        }

        return $result[$ip];
    }
}
