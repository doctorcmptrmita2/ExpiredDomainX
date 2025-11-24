<?php

namespace App\Services\Domain;

use Carbon\Carbon;

class DomainScoringService
{
    public function calculateEdScore(
        ?int $ageYears,
        int $organicTraffic,
        int $referringDomains,
        ?int $daysToExpiry = null
    ): int {
        $score = 0;

        // Age component (0-30 points)
        if ($ageYears !== null) {
            if ($ageYears >= 10) {
                $score += 30;
            } elseif ($ageYears >= 5) {
                $score += 20;
            } elseif ($ageYears >= 2) {
                $score += 10;
            }
        }

        // Organic traffic component (0-40 points)
        if ($organicTraffic >= 10000) {
            $score += 40;
        } elseif ($organicTraffic >= 5000) {
            $score += 30;
        } elseif ($organicTraffic >= 1000) {
            $score += 20;
        } elseif ($organicTraffic >= 100) {
            $score += 10;
        }

        // Referring domains component (0-20 points)
        if ($referringDomains >= 1000) {
            $score += 20;
        } elseif ($referringDomains >= 500) {
            $score += 15;
        } elseif ($referringDomains >= 100) {
            $score += 10;
        } elseif ($referringDomains >= 50) {
            $score += 5;
        }

        // Expiry window bonus (0-10 points)
        if ($daysToExpiry !== null) {
            if ($daysToExpiry < 0) {
                // Already expired
                $score += 5;
            } elseif ($daysToExpiry <= 7) {
                // Expiring within 7 days
                $score += 10;
            } elseif ($daysToExpiry <= 30) {
                // Expiring within 30 days
                $score += 7;
            } elseif ($daysToExpiry <= 90) {
                // Expiring within 90 days
                $score += 3;
            }
        }

        return min(100, max(0, $score));
    }

    public function getScoreComment(int $edScore): string
    {
        if ($edScore >= 80) {
            return 'High potential investment domain with strong SEO metrics and established authority.';
        } elseif ($edScore >= 50) {
            return 'Moderate potential domain with decent SEO metrics and some authority.';
        } else {
            return 'Low potential domain with limited SEO value and authority.';
        }
    }
}

