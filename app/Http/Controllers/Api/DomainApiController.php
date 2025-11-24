<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DomainSearchApiRequest;
use App\Models\ApiUsageLog;
use App\Models\Domain;
use App\Services\Domain\DomainSearchService;
use App\Services\User\ApiKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DomainApiController extends Controller
{
    public function __construct(
        private DomainSearchService $searchService,
        private ApiKeyService $apiKeyService
    ) {}

    public function index(DomainSearchApiRequest $request): JsonResponse
    {
        $user = $request->user();

        ApiUsageLog::create([
            'user_id' => $user->id,
            'action' => 'api.domain_list',
            'meta' => $request->validated(),
        ]);

        $perPage = $request->input('per_page', 25);
        $domains = $this->searchService->search($request->validated(), $perPage);

        return response()->json([
            'data' => $domains->items(),
            'meta' => [
                'current_page' => $domains->currentPage(),
                'last_page' => $domains->lastPage(),
                'per_page' => $domains->perPage(),
                'total' => $domains->total(),
            ],
        ]);
    }

    public function show(Domain $domain): JsonResponse
    {
        $domain->load('latestMetric');

        return response()->json([
            'data' => $domain,
        ]);
    }
}
