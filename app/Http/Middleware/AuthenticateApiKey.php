<?php

namespace App\Http\Middleware;

use App\Services\User\ApiKeyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    public function __construct(
        private ApiKeyService $apiKeyService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey) {
            return response()->json([
                'error' => 'API key is required. Provide it in X-API-Key header.',
            ], 401);
        }

        $user = $this->apiKeyService->validateApiKey($apiKey);

        if (!$user) {
            return response()->json([
                'error' => 'Invalid API key.',
            ], 401);
        }

        if (!$user->isPro()) {
            return response()->json([
                'error' => 'API access requires a Pro plan.',
            ], 403);
        }

        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
