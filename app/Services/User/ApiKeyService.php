<?php

namespace App\Services\User;

use App\Models\ApiKey;
use App\Models\User;

class ApiKeyService
{
    public function createApiKey(User $user, string $name): ApiKey
    {
        return ApiKey::create([
            'user_id' => $user->id,
            'name' => $name,
            'key' => ApiKey::generate(),
        ]);
    }

    public function revokeApiKey(string $key): bool
    {
        $apiKey = ApiKey::where('key', $key)->first();

        if (!$apiKey) {
            return false;
        }

        return $apiKey->delete();
    }

    public function validateApiKey(string $key): ?User
    {
        $apiKey = ApiKey::where('key', $key)->first();

        if (!$apiKey) {
            return null;
        }

        $apiKey->update(['last_used_at' => now()]);

        return $apiKey->user;
    }
}

