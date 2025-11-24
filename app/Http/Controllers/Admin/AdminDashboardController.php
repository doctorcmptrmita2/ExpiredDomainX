<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(): View
    {
        $totalUsers = User::count();
        $freeUsers = User::where('plan', 'free')->count();
        $proUsers = User::where('plan', 'pro')->count();
        $totalDomains = Domain::count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'freeUsers' => $freeUsers,
            'proUsers' => $proUsers,
            'totalDomains' => $totalDomains,
        ]);
    }
}
