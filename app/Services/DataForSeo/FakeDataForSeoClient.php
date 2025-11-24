<?php

namespace App\Services\DataForSeo;

use Carbon\Carbon;

class FakeDataForSeoClient implements DataForSeoClientInterface
{
    public function fetchExpiredDomains(int $limit = 100): array
    {
        $domains = [];
        $tlds = ['com', 'net', 'org', 'io', 'ai', 'dev'];
        $statuses = ['expired', 'expiring', 'active', 'pending_delete'];
        $countries = ['US', 'GB', 'DE', 'FR', 'IT', null];

        for ($i = 0; $i < $limit; $i++) {
            $name = $this->generateDomainName();
            $tld = $tlds[array_rand($tlds)];
            $status = $statuses[array_rand($statuses)];
            $registeredAt = Carbon::now()->subYears(rand(1, 15));
            $expiresAt = match ($status) {
                'expired' => Carbon::now()->subDays(rand(1, 365)),
                'expiring' => Carbon::now()->addDays(rand(1, 90)),
                default => Carbon::now()->addYears(rand(1, 5)),
            };

            $domains[] = [
                'name' => $name . '.' . $tld,
                'tld' => $tld,
                'registrar' => $this->generateRegistrar(),
                'status' => $status,
                'registered_at' => $registeredAt,
                'expires_at' => $expiresAt,
                'country' => $countries[array_rand($countries)],
                'metrics' => [
                    'organic_traffic' => rand(0, 50000),
                    'organic_keywords' => rand(0, 10000),
                    'backlinks_total' => rand(0, 50000),
                    'referring_domains' => rand(0, 2000),
                ],
                'raw_payload' => [
                    'source' => 'fake',
                    'generated_at' => now()->toIso8601String(),
                ],
            ];
        }

        return $domains;
    }

    private function generateDomainName(): string
    {
        $adjectives = ['smart', 'fast', 'cool', 'best', 'top', 'pro', 'super', 'mega', 'ultra', 'prime'];
        $nouns = ['tech', 'web', 'app', 'dev', 'code', 'data', 'cloud', 'net', 'hub', 'lab'];
        $numbers = ['', '1', '2', '2024', '2025', 'pro', 'plus'];

        $adjective = $adjectives[array_rand($adjectives)];
        $noun = $nouns[array_rand($nouns)];
        $number = $numbers[array_rand($numbers)];

        return $adjective . $noun . $number;
    }

    private function generateRegistrar(): string
    {
        $registrars = [
            'GoDaddy',
            'Namecheap',
            'Google Domains',
            'Name.com',
            'Register.com',
            'Network Solutions',
        ];

        return $registrars[array_rand($registrars)];
    }
}

