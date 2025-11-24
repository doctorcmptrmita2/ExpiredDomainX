<?php

namespace App\Services\User;

use App\Models\User;
use Carbon\Carbon;

class PlanLimitService
{
    private const PLAN_LIMITS = [
        'free' => [
            'daily_domain_views' => 20,
            'watchlist_capacity' => 10,
            'csv_export' => false,
            'api_access' => false,
        ],
        'pro' => [
            'daily_domain_views' => 500,
            'watchlist_capacity' => 100,
            'csv_export' => true,
            'api_access' => true,
        ],
    ];

    public function canViewDomain(User $user): bool
    {
        $this->resetDailyViewsIfNeeded($user);

        $limit = $this->getPlanLimits($user->plan)['daily_domain_views'];

        return $user->daily_domain_views < $limit;
    }

    public function registerDomainView(User $user): void
    {
        $this->resetDailyViewsIfNeeded($user);

        $user->increment('daily_domain_views');
    }

    public function canAddToWatchlist(User $user): bool
    {
        $limit = $this->getPlanLimits($user->plan)['watchlist_capacity'];
        $currentCount = $user->watchlistItems()->count();

        return $currentCount < $limit;
    }

    public function canExportCsv(User $user): bool
    {
        return $this->getPlanLimits($user->plan)['csv_export'];
    }

    public function canAccessApi(User $user): bool
    {
        return $this->getPlanLimits($user->plan)['api_access'];
    }

    public function getPlanLimits(string $plan): array
    {
        return self::PLAN_LIMITS[$plan] ?? self::PLAN_LIMITS['free'];
    }

    private function resetDailyViewsIfNeeded(User $user): void
    {
        $resetAt = $user->last_domain_view_reset_at;

        if (!$resetAt || !Carbon::parse($resetAt)->isToday()) {
            $user->update([
                'daily_domain_views' => 0,
                'last_domain_view_reset_at' => now(),
            ]);
        }
    }
}

