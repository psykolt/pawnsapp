<?php

namespace App\Modules\Proxycheck;

class ProxycheckService
{
    /**
     * @param ApiClient $apiClient
     */
    public function __construct(private readonly ApiClient $apiClient)
    {
    }

    /**
     * @param string $ip
     * @return string|null
     */
    public function getCountryByIp(string $ip): ?string
    {
        $result = $this->apiClient->callApi($ip);

        if (empty($result)) {
            // API request failed, cannot determine the country
            return null;
        }

        return $result['country'];
    }

    /**
     * @param string $ip
     * @return bool
     */
    public function isVpn(string $ip): bool
    {
        $result = $this->apiClient->callApi($ip);

        if (empty($result)) {
            // API request failed, cannot determine if VPN
            return false;
        }

        return $result['proxy'] === 'yes';
    }
}
