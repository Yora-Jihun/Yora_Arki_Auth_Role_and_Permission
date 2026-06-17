<x-dashboard>
    <div class="max-w-4xl">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Dashboard</h1>
        <div class="bg-white shadow rounded-lg p-6 border border-slate-200">
            <p class="text-slate-700 mb-4">
                Welcome, <span class="font-semibold">{{ auth()->user()->fullname ?? 'User' }}</span>! You have successfully logged in.
            </p>

            <dl class="mt-4 space-y-3 text-sm text-slate-600">
                <div class="flex">
                    <dt class="font-medium text-slate-700 w-24">First Name</dt>
                    <dd>{{ auth()->user()->first_name ?? '-' }}</dd>
                </div>
                <div class="flex">
                    <dt class="font-medium text-slate-700 w-24">Last Name</dt>
                    <dd>{{ auth()->user()->last_name ?? '-' }}</dd>
                </div>
                <div class="flex">
                    <dt class="font-medium text-slate-700 w-24">Email</dt>
                    <dd>{{ auth()->user()->email ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-dashboard>