<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        return view('billing.index', [
            'user' => $user,
        ]);
    }

    public function upgrade(): RedirectResponse
    {
        $user = auth()->user();

        // TODO: Implement actual payment processing
        // For now, manual upgrade stub
        $user->update(['plan' => 'pro']);

        return back()->with('success', 'Your account has been upgraded to Pro!');
    }
}
