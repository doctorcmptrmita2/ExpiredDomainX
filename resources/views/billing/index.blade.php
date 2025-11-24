<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Current Plan</h3>
                <p class="text-2xl font-bold text-indigo-600 mb-6">{{ ucfirst($user->plan) }}</p>

                @if($user->plan === 'free')
                    <form method="POST" action="{{ route('billing.upgrade') }}">
                        @csrf
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                            Upgrade to Pro ($29/month)
                        </button>
                        <p class="text-sm text-gray-500 mt-2">Note: This is a demo upgrade. In production, this would integrate with Stripe/Paddle.</p>
                    </form>
                @else
                    <p class="text-gray-600">You are currently on the Pro plan.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

