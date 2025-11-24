<?php

namespace App\Services\Domain;

use App\Models\Domain;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DomainSearchService
{
    public function search(array $filters, int $perPage = 25): LengthAwarePaginator
    {
        $query = Domain::query()->with('latestMetric');

        // TLD filter
        if (!empty($filters['tld'])) {
            $query->where('tld', $filters['tld']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Keyword filter (domain name LIKE)
        if (!empty($filters['keyword'])) {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        // Minimum age filter
        if (!empty($filters['min_age_years'])) {
            $minDate = now()->subYears($filters['min_age_years']);
            $query->where('registered_at', '<=', $minDate);
        }

        // Expiry window filter
        if (!empty($filters['expiry_window'])) {
            $now = now();
            switch ($filters['expiry_window']) {
                case 'expired':
                    $query->where('expires_at', '<', $now);
                    break;
                case '7_days':
                    $query->whereBetween('expires_at', [$now, $now->copy()->addDays(7)]);
                    break;
                case '30_days':
                    $query->whereBetween('expires_at', [$now, $now->copy()->addDays(30)]);
                    break;
                case '90_days':
                    $query->whereBetween('expires_at', [$now, $now->copy()->addDays(90)]);
                    break;
            }
        }

        // Minimum organic traffic filter
        if (!empty($filters['min_organic_traffic'])) {
            $query->whereHas('latestMetric', function ($q) use ($filters) {
                $q->where('organic_traffic', '>=', $filters['min_organic_traffic']);
            });
        }

        // Minimum referring domains filter
        if (!empty($filters['min_referring_domains'])) {
            $query->whereHas('latestMetric', function ($q) use ($filters) {
                $q->where('referring_domains', '>=', $filters['min_referring_domains']);
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}

