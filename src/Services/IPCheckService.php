<?php

namespace wtg\IpCountryDetector\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class IPCheckService
{
    private IPCacheService $ipCacheService;
    private IpApiService $ipApiService;

    public function __construct(IPCacheService $ipCacheService, IpApiService $ipApiService)
    {
        $this->ipCacheService = $ipCacheService;
        $this->ipApiService = $ipApiService;
    }

    /**
     * @throws Exception
     */
    public function ipToCountry(string $ipAddress): string
    {
        $cachedCountry = $this->ipCacheService->getCountryFromCache($ipAddress);
        if ($cachedCountry) {
            return $cachedCountry;
        }

        $ipLong = ip2long($ipAddress);
        if ($ipLong === false) {
            return 'Invalid IP address';
        }

        $country = $this->findCountryByIp($ipLong);

        if ($country !== "IP Address not found in the range.") {
            $this->ipCacheService->setCountryToCache($ipAddress, $country);
            return $country;
        }

        $country = $this->fetchCountryFromApi($ipAddress);

        if ($country != 'Country not found') {
            $this->ipCacheService->setCountryToCache($ipAddress, $country);
        }

        return $country;
    }

    private function findCountryByIp(int $ipLong): string
    {
        $result = DB::table('ip_country')
            ->where('first_ip', '<=', $ipLong)
            ->where('last_ip', '>=', $ipLong)
            ->select('country')
            ->first();

        if ($result) {
            return $result->country;
        }

        return "IP Address not found in the range.";
    }

    private function fetchCountryFromApi(string $ipAddress): string
    {
        return $this->ipApiService->getCountry($ipAddress);
    }
}