<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Your Plan</h3>
                    <p class="text-2xl font-bold text-indigo-600">{{ ucfirst(auth()->user()->plan) }}</p>
                    @if(auth()->user()->plan === 'free')
                        <a href="{{ route('pricing') }}" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">Upgrade to Pro</a>
                    @endif
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Daily Views</h3>
                    <p class="text-2xl font-bold">{{ auth()->user()->daily_domain_views }}</p>
                    <p class="text-sm text-gray-600 mt-1">of {{ auth()->user()->plan === 'pro' ? '500' : '20' }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Watchlist</h3>
                    <p class="text-2xl font-bold">{{ auth()->user()->watchlistItems()->count() }}</p>
                    <a href="{{ route('watchlist.index') }}" class="text-sm text-indigo-600 hover:underline mt-2 inline-block">View Watchlist</a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Quick Actions</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('domains.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <h4 class="font-semibold mb-2">Explore Domains</h4>
                        <p class="text-sm text-gray-600">Search and filter expired/expiring domains</p>
                    </a>
                    <a href="{{ route('watchlist.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <h4 class="font-semibold mb-2">My Watchlist</h4>
                        <p class="text-sm text-gray-600">View your saved domains</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
