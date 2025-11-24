<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchDomainsRequest;
use App\Models\Domain;
use App\Services\Domain\DomainExportService;
use App\Services\Domain\DomainSearchService;
use App\Services\User\PlanLimitService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function __construct(
        private DomainSearchService $searchService,
        private PlanLimitService $planLimitService,
        private DomainExportService $exportService
    ) {}

    public function index(SearchDomainsRequest $request): View
    {
        $domains = $this->searchService->search($request->validated());

        return view('domains.index', [
            'domains' => $domains,
            'filters' => $request->validated(),
        ]);
    }

    public function show(Domain $domain): View|Response
    {
        $user = auth()->user();

        if (!$this->planLimitService->canViewDomain($user)) {
            return redirect()->route('pricing')
                ->with('error', 'You have reached your daily domain view limit. Upgrade to Pro for more views.');
        }

        $this->planLimitService->registerDomainView($user);

        Log::info('Domain view registered', [
            'user_id' => $user->id,
            'domain_id' => $domain->id,
            'action' => 'web.domain_view',
        ]);

        $domain->load('latestMetric');

        return view('domains.show', [
            'domain' => $domain,
        ]);
    }

    public function export(SearchDomainsRequest $request): Response
    {
        $user = auth()->user();

        if (!$this->planLimitService->canExportCsv($user)) {
            return redirect()->route('pricing')
                ->with('error', 'CSV export is only available for Pro users.');
        }

        $domains = $this->searchService->search($request->validated(), 1000);
        $filePath = $this->exportService->exportToCsv($domains->items());

        return response()->download($filePath)->deleteFileAfterSend();
    }
}
