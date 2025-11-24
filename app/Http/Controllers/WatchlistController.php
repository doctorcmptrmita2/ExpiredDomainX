<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\WatchlistItem;
use App\Services\User\PlanLimitService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WatchlistController extends Controller
{
    public function __construct(
        private PlanLimitService $planLimitService
    ) {}

    public function index(): View
    {
        $user = auth()->user();
        $watchlistItems = $user->watchlistItems()->with('domain.latestMetric')->paginate(25);

        return view('watchlist.index', [
            'watchlistItems' => $watchlistItems,
        ]);
    }

    public function store(Domain $domain): RedirectResponse
    {
        $user = auth()->user();

        if (!$this->planLimitService->canAddToWatchlist($user)) {
            return back()->with('error', 'You have reached your watchlist limit. Upgrade to Pro for more capacity.');
        }

        WatchlistItem::firstOrCreate([
            'user_id' => $user->id,
            'domain_id' => $domain->id,
        ]);

        return back()->with('success', 'Domain added to watchlist.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        $user = auth()->user();

        WatchlistItem::where('user_id', $user->id)
            ->where('domain_id', $domain->id)
            ->delete();

        return back()->with('success', 'Domain removed from watchlist.');
    }
}
