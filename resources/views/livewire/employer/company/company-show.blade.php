<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $company->description ?: 'Manage departments, invitations, and employees.' }}</p>
        </div>

        <a href="{{ route('employer.companies') }}" wire:navigate class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
            Back to companies
        </a>
    </div>

    @include('partials.alerts', [
        'type' => session('department_status') ? 'success' : 'info',
        'message' => session('department_status'),
    ])

    @include('partials.alerts', [
        'type' => session('invitation_status') ? 'success' : 'info',
        'message' => session('invitation_status'),
    ])

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="space-y-6">
            <div class="bg-white border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Create Department</h2>

                <form wire:submit="createDepartment" class="space-y-4">
                    <div class="space-y-2">
                        <label for="department_name" class="block text-xs font-medium text-gray-500">Department Name</label>
                        <input
                            id="department_name"
                            type="text"
                            wire:model="department_name"
                            class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                            placeholder="Operations"
                            required
                        >
                        @error('department_name')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="department_description" class="block text-xs font-medium text-gray-500">Description</label>
                        <textarea
                            id="department_description"
                            wire:model="department_description"
                            rows="3"
                            class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition resize-none"
                            placeholder="Department responsibilities"
                        ></textarea>
                        @error('department_description')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white text-sm font-medium px-4 py-2.5 hover:bg-emerald-700 transition">
                        Create Department
                    </button>
                </form>
            </div>

            <div class="bg-white border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Invite Employee</h2>

                <form wire:submit="sendInvitation" class="space-y-4">
                    <div class="space-y-2">
                        <label for="invitation_employee_id" class="block text-xs font-medium text-gray-500">Employee ID</label>
                        <input
                            id="invitation_employee_id"
                            type="text"
                            wire:model.live="invitation_employee_id"
                            class="w-full bg-gray-50 px-3 py-2 text-sm uppercase text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                            placeholder="EMPL-ABC12345"
                            autocomplete="off"
                            required
                        >
                        @error('invitation_employee_id')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($invitation_employee)
                        <div class="border border-emerald-100 bg-emerald-50 p-3">
                            <p class="text-sm font-medium text-gray-900">{{ $invitation_employee->fullname }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $invitation_employee->employee_id }}</p>
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label for="invitation_department_id" class="block text-xs font-medium text-gray-500">Department</label>
                        <select
                            id="invitation_department_id"
                            wire:model="invitation_department_id"
                            class="w-full bg-gray-50 px-3 py-2 text-sm text-slate-900 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                        >
                            <option value="">No department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                        @error('invitation_department_id')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 text-white text-sm font-medium px-4 py-2.5 hover:bg-emerald-700 transition">
                        Send Invitation
                    </button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white border border-gray-100 p-2">
                <div class="grid grid-cols-3 gap-1 bg-gray-50 p-1">
                    <button type="button" wire:click="selectTab('departments')" class="px-3 py-2 text-sm font-medium transition {{ $active_tab === 'departments' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Departments
                    </button>
                    <button type="button" wire:click="selectTab('employees')" class="px-3 py-2 text-sm font-medium transition {{ $active_tab === 'employees' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Employees
                    </button>
                    <button type="button" wire:click="selectTab('invitations')" class="px-3 py-2 text-sm font-medium transition {{ $active_tab === 'invitations' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        Invitations
                    </button>
                </div>
            </div>

            <div class="mt-4">
                @if($active_tab === 'departments')
                    <div class="bg-white border border-gray-100 p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-sm font-semibold text-gray-900">Departments</h2>
                            <div class="relative w-full max-w-xs">
                                <input
                                    type="text"
                                    wire:model.live.debounce.250ms="department_search"
                                    class="w-full bg-gray-50 px-3 py-2 pl-8 text-xs text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                                    placeholder="Search departments"
                                >
                                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.35-4.35"/>
                                </svg>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @forelse($departments as $department)
                                <div class="border border-gray-100 p-4">
                                    <p class="text-sm font-semibold text-gray-900">{{ $department->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $department->description ?: 'No description' }}</p>
                                </div>
                            @empty
                                <div class="md:col-span-2 border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">
                                    No departments yet.
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            {{ $departments->links() }}
                        </div>
                    </div>
                @endif

                @if($active_tab === 'employees')
                    <div class="bg-white border border-gray-100 p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-sm font-semibold text-gray-900">Employees</h2>
                            <div class="relative w-full max-w-xs">
                                <input
                                    type="text"
                                    wire:model.live.debounce.250ms="employee_search"
                                    class="w-full bg-gray-50 px-3 py-2 pl-8 text-xs uppercase text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                                    placeholder="Search name, email, or ID"
                                >
                                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.35-4.35"/>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @forelse($employees as $employee)
                                <div class="flex items-center justify-between border border-gray-100 p-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $employee->fullname }}</p>
                                        <p class="text-xs text-gray-500">{{ $employee->employee_id }} · {{ $employee->email }}</p>
                                    </div>
                                    <span class="text-xs font-medium bg-emerald-50 text-emerald-700 px-2.5 py-1">{{ $employee->pivot->department_id ? ($departmentMap[$employee->pivot->department_id] ?? 'No department') : 'No department' }}</span>
                                </div>
                            @empty
                                <div class="border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">
                                    No employees have joined yet.
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            {{ $employees->links() }}
                        </div>
                    </div>
                @endif

                @if($active_tab === 'invitations')
                    <div class="bg-white border border-gray-100 p-6">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <h2 class="text-sm font-semibold text-gray-900">Invitations</h2>
                            <div class="relative w-full max-w-xs">
                                <input
                                    type="text"
                                    wire:model.live.debounce.250ms="invitation_search"
                                    class="w-full bg-gray-50 px-3 py-2 pl-8 text-xs uppercase text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition"
                                    placeholder="Search email or ID"
                                >
                                <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8"/>
                                    <path d="m21 21-4.35-4.35"/>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @forelse($invitations as $invitation)
                                <div class="flex items-center justify-between border border-gray-100 p-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $invitation->employee_id ? ($invitation->acceptedBy?->fullname ?? $invitation->employee_id) : $invitation->email }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $invitation->employee_id ? ($invitation->acceptedBy?->employee_id ?? 'Employee ID') : 'Email' }} · {{ $invitation->department?->name ?? 'No department' }} · Expires {{ $invitation->expires_at?->format('M j, Y') ?? 'Never' }}
                                        </p>
                                    </div>
                                    <span class="text-xs font-medium px-2.5 py-1 {{ $invitation->status->value === 'accepted' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($invitation->status->value) }}
                                    </span>
                                </div>
                            @empty
                                <div class="border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">
                                    No invitations yet.
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            {{ $invitations->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>