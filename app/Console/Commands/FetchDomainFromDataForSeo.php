<?php

namespace App\Console\Commands;

use App\Services\Domain\DomainIngestionService;
use Illuminate\Console\Command;

class FetchDomainFromDataForSeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'domain:fetch {domain : The domain name to fetch from DataForSEO}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch domain data from DataForSEO API';

    /**
     * Execute the console command.
     */
    public function handle(DomainIngestionService $ingestionService)
    {
        $domainName = $this->argument('domain');

        $this->info("Fetching domain data for: {$domainName}");

        $domain = $ingestionService->fetchDomainFromDataForSeo($domainName);

        if ($domain) {
            $this->info("✓ Domain data fetched successfully!");
            $this->line("Domain: {$domain->name}");
            $this->line("Status: {$domain->status}");
            $this->line("Registrar: " . ($domain->registrar ?? 'N/A'));
            $this->line("ED Score: " . ($domain->latestMetric?->ed_score ?? 0));
        } else {
            $this->error("✗ Failed to fetch domain data. Check logs for details.");
            return 1;
        }

        return 0;
    }
}
