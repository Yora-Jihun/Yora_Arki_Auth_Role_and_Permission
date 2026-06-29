<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Invitations</h1>
        <p class="text-sm text-gray-500 mt-0.5">Search employers by ID, join companies, and review invitations.</p>
    </div>

    @include('partials.alerts', [
        'type' => session('status') ? 'success' : 'info',
        'message' => session('status'),
    ])

    <div class="bg-white border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Join Company by Employer ID</h2>

        <form wire:submit="joinCompany" class="space-y-4">
            <div class="space-y-2">
                <label for="employer_id" class="block text-xs font-medium text-gray-500">Employer ID</label>
                <input
                    id="employer_id"
                    type="text"
                    wire:model.live="employer_id"
                    class="w-full bg-gray-50 px-3 py-2 pl-9 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                    placeholder="EMPLOYER-ABC12345"
                    autocomplete="off"
                >
                @error('employer_id')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @if($employer_searched && $searchedEmployer)
                @if($searchedEmployer->companies->isEmpty())
                    <div class="border border-dashed border-gray-200 p-6 text-sm text-gray-500">
                        This employer has not created any company yet.
                    </div>
                @else
                    <div class="space-y-5">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Step 1</p>
                                    <h3 class="text-sm font-semibold text-gray-900">Choose a company</h3>
                                </div>
                                <span class="text-xs text-gray-500">{{ $searchedEmployer->companies->count() }} available</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($searchedEmployer->companies as $company)
                                    <button
                                        type="button"
                                        wire:click="selectCompany({{ $company->id }})"
                                        class="text-left border p-4 transition {{ $join_company_id === $company->id ? 'border-emerald-300 bg-emerald-50 ring-1 ring-emerald-200' : 'border-gray-100 bg-white hover:border-emerald-200 hover:shadow-sm' }}"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $company->name }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $company->description ?: 'No description' }}</p>
                                            </div>
                                            @if($join_company_id === $company->id)
                                                <span class="inline-flex h-6 w-6 items-center justify-center bg-emerald-600 text-white text-xs">✓</span>
                                            @endif
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        @php($selectedCompany = $searchedEmployer->companies->firstWhere('id', $join_company_id))

                        @if($selectedCompany)
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700">Step 2</p>
                                        <h3 class="text-sm font-semibold text-gray-900">Choose a department</h3>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $selectedCompany->departments->count() }} available</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <button
                                        type="button"
                                        wire:click="$set('join_department_id', null)"
                                        class="text-left border p-4 transition {{ $join_department_id === null ? 'border-emerald-300 bg-emerald-50 ring-1 ring-emerald-200' : 'border-gray-100 bg-white hover:border-emerald-200 hover:shadow-sm' }}"
                                    >
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">No department</p>
                                                <p class="text-xs text-gray-500 mt-1">Join without a department assignment.</p>
                                            </div>
                                            @if($join_department_id === null)
                                                <span class="inline-flex h-6 w-6 items-center justify-center bg-emerald-600 text-white text-xs">✓</span>
                                            @endif
                                        </div>
                                    </button>

                                    @foreach($selectedCompany->departments as $department)
                                        <button
                                            type="button"
                                            wire:click="$set('join_department_id', {{ $department->id }})"
                                            class="text-left border p-4 transition {{ $join_department_id === $department->id ? 'border-emerald-300 bg-emerald-50 ring-1 ring-emerald-200' : 'border-gray-100 bg-white hover:border-emerald-200 hover:shadow-sm' }}"
                                        >
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $department->name }}</p>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $department->description ?: 'No description' }}</p>
                                                </div>
                                                @if($join_department_id === $department->id)
                                                    <span class="inline-flex h-6 w-6 items-center justify-center bg-emerald-600 text-white text-xs">✓</span>
                                                @endif
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <button
                            type="submit"
                            @if(!$join_company_id) disabled @endif
                            class="w-full bg-emerald-600 text-white text-sm font-medium px-4 py-2.5 hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-gray-300 disabled:hover:bg-gray-300 transition"
                        >
                            Join Company
                        </button>
                    </div>
                @endif
            @else
                <div class="border border-dashed border-gray-200 p-4 text-sm text-gray-500">
                    Enter an employer ID to search for available companies.
                </div>
            @endif
        </form>
    </div>

    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-900">Company Invitations</h2>
            <p class="text-xs text-gray-500">Invitations can be sent to your employee ID.</p>
        </div>

        @forelse($invitations as $invitation)
            <div class="bg-white border border-gray-100 p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $invitation->company->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $invitation->department?->name ?? 'No department' }} · {{ ucfirst($invitation->status->value) }}
                        </p>
                    </div>

                    @if($invitation->accepted_by)
                        <span class="text-xs font-medium bg-emerald-50 text-emerald-700 px-2.5 py-1">Accepted</span>
                    @else
                        <a href="{{ route('employee.invitations.accept', $invitation->token) }}" wire:navigate class="text-xs font-medium bg-emerald-600 text-white px-3 py-2 hover:bg-emerald-700 transition">
                            Accept
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-gray-200 p-8 text-center text-sm text-gray-500">
                No invitations found.
            </div>
        @endforelse
    </div>
</div>