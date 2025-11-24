<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Total Users</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Free Users</h3>
                    <p class="text-3xl font-bold text-gray-600">{{ $freeUsers }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Pro Users</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $proUsers }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Total Domains</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalDomains }}</p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.users.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    Manage Users
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

