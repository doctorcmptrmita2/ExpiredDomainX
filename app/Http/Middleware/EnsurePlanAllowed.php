<?php

namespace App\Http\Middleware;

use App\Services\User\PlanLimitService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanAllowed
{
    public function __construct(
        private PlanLimitService $planLimitService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $requiredPlan = 'pro'): Response
    {
        $user = $request->user();

        if (!$user || $user->plan !== $requiredPlan) {
            return redirect()->route('pricing')
                ->with('error', "This feature requires a {$requiredPlan} plan.");
        }

        return $next($request);
    }
}
