<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
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
        $users = User::withCount('watchlistItems')->paginate(25);

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function updatePlan(User $user, Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => 'required|in:free,pro',
        ]);

        $user->update(['plan' => $request->plan]);

        return back()->with('success', "User plan updated to {$request->plan}.");
    }
}
