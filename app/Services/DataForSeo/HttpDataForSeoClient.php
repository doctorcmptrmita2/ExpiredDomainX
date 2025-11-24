<?php

namespace App\Services\DataForSeo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpDataForSeoClient implements DataForSeoClientInterface
{
    private string $apiUrl;
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->apiUrl = config('services.dataforseo.api_url', 'https://api.dataforseo.com/v3');
        $this->apiKey = config('services.dataforseo.api_key', '');
        $this->apiSecret = config('services.dataforseo.api_secret', '');
    }

    public function fetchExpiredDomains(int $limit = 100): array
    {
        // TODO: Implement actual DataForSEO API integration
        // This is a stub for future implementation

        Log::warning('HttpDataForSeoClient::fetchExpiredDomains called but not implemented');

        return [];
    }
}

