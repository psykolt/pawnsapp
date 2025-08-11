<?php

namespace App\Modules\Proxycheck;

use Illuminate\Http\Request;

class ProxycheckService
{
    /**
     * @param ApiClient $apiClient
     */
    public function __construct(private readonly ApiClient $apiClient)
    {
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getIpAddressFromRequest(Request $request): string
    {
        return $request->headers->get('X-Original-Forwarded-For') ??
            $request->headers->get('X-Forwarded-For') ??
            $request->getClientIp() ?? '';
    }

    /**
     * @param string $ip
     * @return string|null
     */
    public function getCountryByIp(string $ip): ?string
    {
        $result = $this->apiClient->callApi($ip);

        if (empty($result)) {
            // API request failed, cannot determine if VPN
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
