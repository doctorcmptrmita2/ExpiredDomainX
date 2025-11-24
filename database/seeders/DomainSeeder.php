<?php

namespace Database\Seeders;

use App\Services\Domain\DomainIngestionService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingestionService = app(DomainIngestionService::class);
        $ingestionService->ingestDomains(50);
    }
}
