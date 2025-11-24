<?php

namespace App\Services\DataForSeo;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpDataForSeoClient implements DataForSeoClientInterface
{
    private string $apiUrl;
    private string $apiLogin;
    private string $apiPassword;
    private string $authHeader;

    public function __construct()
    {
        $this->apiUrl = config('services.dataforseo.api_url', 'https://api.dataforseo.com/v3');
        $this->apiLogin = config('services.dataforseo.api_login', '');
        $this->apiPassword = config('services.dataforseo.api_password', '');
        $this->authHeader = base64_encode($this->apiLogin . ':' . $this->apiPassword);
    }

    public function fetchExpiredDomains(int $limit = 100): array
    {
        // Note: DataForSEO doesn't have a direct "expired domains" endpoint
        // This method would need domain names as input to fetch their analytics
        // For now, we'll return empty array and log a message
        // In production, you would need to provide domain names to check
        
        Log::info('HttpDataForSeoClient::fetchExpiredDomains called', [
            'limit' => $limit,
            'note' => 'DataForSEO requires domain names as input. Use fetchDomainAnalytics() for specific domains.',
        ]);

        return [];
    }

    /**
     * Fetch domain analytics for a specific domain
     */
    public function fetchDomainAnalytics(string $domain): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->authHeader,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/domain_analytics/whois/live', [
                [
                    'domain' => $domain,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['tasks'][0]['result'][0])) {
                    $result = $data['tasks'][0]['result'][0];
                    
                    return $this->parseWhoisData($domain, $result);
                }
            } else {
                Log::error('DataForSEO API error', [
                    'domain' => $domain,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('DataForSEO API exception', [
                'domain' => $domain,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Fetch domain backlinks and SEO metrics
     */
    public function fetchDomainBacklinks(string $domain): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $this->authHeader,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/backlinks/summary/live', [
                [
                    'target' => $domain,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['tasks'][0]['result'][0])) {
                    $result = $data['tasks'][0]['result'][0];
                    
                    return [
                        'backlinks_total' => $result['backlinks'] ?? 0,
                        'referring_domains' => $result['referring_domains'] ?? 0,
                        'referring_main_domains' => $result['referring_main_domains'] ?? 0,
                        'raw_payload' => $result,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('DataForSEO Backlinks API exception', [
                'domain' => $domain,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Parse WHOIS data from DataForSEO response
     */
    private function parseWhoisData(string $domain, array $result): array
    {
        $domainParts = explode('.', $domain);
        $tld = end($domainParts);
        $name = str_replace('.' . $tld, '', $domain);

        $registeredAt = isset($result['created_datetime']) 
            ? Carbon::parse($result['created_datetime']) 
            : null;
        
        $expiresAt = isset($result['expiration_datetime']) 
            ? Carbon::parse($result['expiration_datetime']) 
            : null;

        $status = 'active';
        if ($expiresAt) {
            $daysToExpiry = now()->diffInDays($expiresAt, false);
            if ($daysToExpiry < 0) {
                $status = 'expired';
            } elseif ($daysToExpiry <= 90) {
                $status = 'expiring';
            }
        }

        return [
            'name' => $domain,
            'tld' => $tld,
            'registrar' => $result['registrar'] ?? null,
            'status' => $status,
            'registered_at' => $registeredAt,
            'expires_at' => $expiresAt,
            'country' => $result['country'] ?? null,
            'raw_payload' => $result,
        ];
    }
}

