<?php

namespace App\Services\Domain;

use App\Models\Domain;
use App\Models\DomainMetric;
use App\Services\DataForSeo\DataForSeoClientInterface;
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

