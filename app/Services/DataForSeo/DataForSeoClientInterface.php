<?php

namespace App\Services\DataForSeo;

interface DataForSeoClientInterface
{
    /**
     * Fetch expired/expiring domains from provider
     *
     * @param int $limit Maximum number of domains to fetch
     * @return array Array of domain data with structure:
     *   [
     *     'name' => string,
     *     'tld' => string|null,
     *     'registrar' => string|null,
     *     'status' => string,
     *     'registered_at' => Carbon|string|null,
     *     'expires_at' => Carbon|string|null,
     *     'country' => string|null,
     *     'metrics' => [
     *       'organic_traffic' => int,
     *       'organic_keywords' => int,
     *       'backlinks_total' => int,
     *       'referring_domains' => int,
     *     ],
     *     'raw_payload' => array|null,
     *   ]
     */
    public function fetchExpiredDomains(int $limit = 100): array;
}

