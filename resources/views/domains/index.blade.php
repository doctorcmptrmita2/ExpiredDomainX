<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Domain Explorer') }}
            </h2>
            @if(auth()->user()->isPro())
                <a href="{{ route('domains.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Export CSV
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('domains.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">TLD</label>
                            <input type="text" name="tld" value="{{ request('tld') }}" placeholder="com, net, org" class="w-full rounded-md border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300">
                                <option value="">All</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="expiring" {{ request('status') === 'expiring' ? 'selected' : '' }}>Expiring</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Domain name" class="w-full rounded-md border-gray-300">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Domain</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Age</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Traffic</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref. Domains</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ED Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($domains as $domain)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('domains.show', $domain) }}" class="text-indigo-600 hover:underline">
                                            {{ $domain->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $domain->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($domain->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $domain->age_in_years ?? 'N/A' }} years
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($domain->latestMetric?->organic_traffic ?? 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($domain->latestMetric?->referring_domains ?? 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ ($domain->latestMetric?->ed_score ?? 0) >= 80 ? 'bg-green-100 text-green-800' : (($domain->latestMetric?->ed_score ?? 0) >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ $domain->latestMetric?->ed_score ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('domains.show', $domain) }}" class="text-indigo-600 hover:underline">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No domains found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $domains->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

