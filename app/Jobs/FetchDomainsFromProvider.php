<?php

namespace App\Jobs;

use App\Services\Domain\DomainIngestionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class FetchDomainsFromProvider implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private int $limit = 100
    ) {}

    /**
     * Execute the job.
     */
    public function handle(DomainIngestionService $ingestionService): void
    {
        Log::info('Starting domain ingestion job', ['limit' => $this->limit]);

        $ingested = $ingestionService->ingestDomains($this->limit);

        Log::info('Domain ingestion job completed', ['ingested' => $ingested]);
    }
}
