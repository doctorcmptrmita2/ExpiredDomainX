<?php

namespace App\Services\Domain;

use App\Models\Domain;
use Illuminate\Support\Collection;

class DomainExportService
{
    public function exportToCsv(Collection $domains): string
    {
        $filename = 'domains_export_' . now()->format('Y-m-d_His') . '.csv';
        $path = storage_path('app/exports/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $file = fopen($path, 'w');

        // CSV Header
        fputcsv($file, [
            'Domain',
            'TLD',
            'Status',
            'Age (Years)',
            'Expiration Date',
            'Organic Traffic',
            'Referring Domains',
            'ED Score',
        ]);

        // CSV Rows
        foreach ($domains as $domain) {
            $metric = $domain->latestMetric;
            fputcsv($file, [
                $domain->name,
                $domain->tld ?? '',
                $domain->status,
                $domain->age_in_years ?? '',
                $domain->expires_at?->format('Y-m-d') ?? '',
                $metric?->organic_traffic ?? 0,
                $metric?->referring_domains ?? 0,
                $metric?->ed_score ?? 0,
            ]);
        }

        fclose($file);

        return $path;
    }
}

