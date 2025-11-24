<?php

namespace App\Services\Domain;

use App\Models\Domain;
use App\Models\DomainMetric;
use App\Services\DataForSeo\DataForSeoClientInterface;
use App\Services\DataForSeo\HttpDataForSeoClient;
use Illuminate\Support\Facades\Log;

class DomainIngestionService
{
    public function __construct(
        private DataForSeoClientInterface $dataForSeoClient
    ) {}

    public function ingestDomains(int $limit = 100): int
    {
        $domains = $this->dataForSeoClient->fetchExpiredDomains($limit);
        $ingested = 0;

        foreach ($domains as $domainData) {
            try {
                $domain = Domain::updateOrCreate(
                    ['name' => $domainData['name']],
                    [
                        'tld' => $domainData['tld'] ?? null,
                        'registrar' => $domainData['registrar'] ?? null,
                        'status' => $domainData['status'] ?? 'expired',
                        'registered_at' => $domainData['registered_at'] ?? null,
                        'expires_at' => $domainData['expires_at'] ?? null,
                        'country' => $domainData['country'] ?? null,
                        'last_checked_at' => now(),
                    ]
                );

                if (isset($domainData['metrics'])) {
                    $this->createMetric($domain, $domainData['metrics'], $domainData['raw_payload'] ?? null);
                }

                $ingested++;
            } catch (\Exception $e) {
                Log::error('Domain ingestion failed', [
                    'domain' => $domainData['name'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $ingested;
    }

    /**
     * Fetch and update domain data from DataForSEO API
     */
    public function fetchDomainFromDataForSeo(string $domainName): ?Domain
    {
        if (!($this->dataForSeoClient instanceof HttpDataForSeoClient)) {
            Log::warning('DataForSEO client is not HttpDataForSeoClient', [
                'domain' => $domainName,
            ]);
            return null;
        }

        try {
            // Fetch WHOIS data
            $whoisData = $this->dataForSeoClient->fetchDomainAnalytics($domainName);
            
            if (!$whoisData) {
                Log::warning('No WHOIS data returned from DataForSEO', [
                    'domain' => $domainName,
                ]);
                return null;
            }

            // Fetch backlinks data
            $backlinksData = $this->dataForSeoClient->fetchDomainBacklinks($domainName);

            // Create or update domain
            $domain = Domain::updateOrCreate(
                ['name' => $domainName],
                [
                    'tld' => $whoisData['tld'] ?? null,
                    'registrar' => $whoisData['registrar'] ?? null,
                    'status' => $whoisData['status'] ?? 'active',
                    'registered_at' => $whoisData['registered_at'] ?? null,
                    'expires_at' => $whoisData['expires_at'] ?? null,
                    'country' => $whoisData['country'] ?? null,
                    'last_checked_at' => now(),
                ]
            );

            // Create metrics
            $metrics = [
                'organic_traffic' => 0, // DataForSEO doesn't provide this directly
                'organic_keywords' => 0, // DataForSEO doesn't provide this directly
                'backlinks_total' => $backlinksData['backlinks_total'] ?? 0,
                'referring_domains' => $backlinksData['referring_domains'] ?? 0,
            ];

            $this->createMetric($domain, $metrics, [
                'whois' => $whoisData['raw_payload'] ?? null,
                'backlinks' => $backlinksData['raw_payload'] ?? null,
            ]);

            return $domain;
        } catch (\Exception $e) {
            Log::error('Failed to fetch domain from DataForSEO', [
                'domain' => $domainName,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function createMetric(Domain $domain, array $metrics, ?array $rawPayload = null): void
    {
        DomainMetric::create([
            'domain_id' => $domain->id,
            'organic_traffic' => $metrics['organic_traffic'] ?? 0,
            'organic_keywords' => $metrics['organic_keywords'] ?? 0,
            'backlinks_total' => $metrics['backlinks_total'] ?? 0,
            'referring_domains' => $metrics['referring_domains'] ?? 0,
            'ed_score' => $this->calculateEdScore($domain, $metrics),
            'raw_provider_payload' => $rawPayload,
        ]);
    }

    private function calculateEdScore(Domain $domain, array $metrics): int
    {
        $scoringService = new DomainScoringService();

        return $scoringService->calculateEdScore(
            $domain->age_in_years,
            $metrics['organic_traffic'] ?? 0,
            $metrics['referring_domains'] ?? 0,
            $domain->days_to_expiry
        );
    }
}

