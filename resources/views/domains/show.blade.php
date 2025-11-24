<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Domain Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $domain->name }}</h1>
                            <p class="text-gray-600 mt-2">{{ $domain->status }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-4xl font-bold {{ ($domain->latestMetric?->ed_score ?? 0) >= 80 ? 'text-green-600' : (($domain->latestMetric?->ed_score ?? 0) >= 50 ? 'text-yellow-600' : 'text-gray-600') }}">
                                {{ $domain->latestMetric?->ed_score ?? 0 }}
                            </div>
                            <div class="text-sm text-gray-500">ED Score</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">WHOIS Information</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">TLD</dt>
                                    <dd class="text-sm text-gray-900">{{ $domain->tld ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Registrar</dt>
                                    <dd class="text-sm text-gray-900">{{ $domain->registrar ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                                    <dd class="text-sm text-gray-900">{{ $domain->country ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Registered At</dt>
                                    <dd class="text-sm text-gray-900">{{ $domain->registered_at?->format('Y-m-d') ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Expires At</dt>
                                    <dd class="text-sm text-gray-900">{{ $domain->expires_at?->format('Y-m-d') ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Age</dt>
                                    <dd class="text-sm text-gray-900">{{ $domain->age_in_years ?? 'N/A' }} years</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">SEO Metrics</h3>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Organic Traffic</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($domain->latestMetric?->organic_traffic ?? 0) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Organic Keywords</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($domain->latestMetric?->organic_keywords ?? 0) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Backlinks Total</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($domain->latestMetric?->backlinks_total ?? 0) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Referring Domains</dt>
                                    <dd class="text-sm text-gray-900">{{ number_format($domain->latestMetric?->referring_domains ?? 0) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-sm text-gray-700">
                            {{ app(\App\Services\Domain\DomainScoringService::class)->getScoreComment($domain->latestMetric?->ed_score ?? 0) }}
                        </p>
                    </div>

                    <div class="flex gap-4">
                        @if(auth()->user()->watchlistItems()->where('domain_id', $domain->id)->exists())
                            <form method="POST" action="{{ route('watchlist.destroy', $domain) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                                    Remove from Watchlist
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('watchlist.store', $domain) }}">
                                @csrf
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                    Add to Watchlist
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('domains.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

