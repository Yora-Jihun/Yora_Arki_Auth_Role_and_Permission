<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700">Companies</h1>
            <p class="text-sm text-gray-500 mt-0.5">Create and manage employer workspaces.</p>
        </div>
    </div>

    @include('partials.alerts', [
        'type' => session('status') ? 'success' : 'info',
        'message' => session('status'),
    ])

    <div class="bg-white border border-gray-100 p-4">
        <label for="company_search" class="block text-xs font-medium text-gray-500 mb-2">Search Companies</label>
        <div class="relative">
            <input
                id="company_search"
                type="text"
                wire:model.live.debounce.250ms="search"
                class="w-full bg-gray-50 px-3 py-2 pl-9 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                placeholder="Search by company name or description"
            >
            <svg class="w-3.5 h-3.5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Create Company</h2>

                <form wire:submit="submit" class="space-y-4">
                    <div class="space-y-2">
                        <label for="name" class="block text-xs font-medium text-gray-500">Company Name</label>
                        <input
                            id="name"
                            type="text"
                            wire:model="name"
                            class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                            placeholder="Acme Corporation"
                            required
                        >
                        @error('name')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="block text-xs font-medium text-gray-500">Description</label>
                        <textarea
                            id="description"
                            wire:model="description"
                            rows="4"
                            class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition resize-none"
                            placeholder="Short company description"
                        ></textarea>
                        @error('description')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white text-sm font-medium px-4 py-2.5 hover:bg-emerald-700 transition">
                        Create Company
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($companies as $company)
                    <a href="{{ route('employer.companies.show', $company) }}" wire:navigate class="bg-white border border-gray-100 p-5 hover:border-emerald-200 hover:shadow-sm transition">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $company->name }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $company->description ? str($company->description)->limit(80) : 'No description' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <div class="bg-gray-50 p-2 text-center">
                                <p class="text-xs text-gray-500">Departments</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $company->departments_count }}</p>
                            </div>
                            <div class="bg-gray-50 p-2 text-center">
                                <p class="text-xs text-gray-500">Employees</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $company->employees_count }}</p>
                            </div>
                            <div class="bg-gray-50 p-2 text-center">
                                <p class="text-xs text-gray-500">Invites</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $company->invitations_count }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="md:col-span-2 bg-white border border-dashed border-gray-200 p-8 text-center">
                        <p class="text-sm text-gray-500">No companies yet. Create your first company to start inviting employees.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-5">
                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>