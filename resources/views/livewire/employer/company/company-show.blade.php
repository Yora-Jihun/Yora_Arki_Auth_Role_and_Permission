<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700">{{ $company->name }}</h1>
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
                        <input id="department_name" type="text" wire:model="department_name" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition" placeholder="Operations" required>
                        @error('department_name')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="department_description" class="block text-xs font-medium text-gray-500">Description</label>
                        <textarea id="department_description" wire:model="department_description" rows="3" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition resize-none" placeholder="Department responsibilities"></textarea>
                        @error('department_description')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-emerald-700 hover:shadow-md active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z" />
                            <path d="M12 11v6" />
                            <path d="M9 14h6" />
                        </svg>

                        <span>Create Department</span>
                    </button>
                </form>
            </div>

             <div class="bg-white border border-gray-100 p-6">
                 <h3 class="text-sm font-semibold text-gray-900 mb-4">Send Invitation</h3>

                 @include('partials.alerts', [
                 'type' => 'success',
                 'message' => session('invitation_status'),
                 ])

                 <form wire:submit="sendInvitation" class="space-y-4">
                     <div>
                         <label for="invitation_email" class="block text-xs font-medium text-gray-500 mb-1.5">Employee Email</label>
                         <input type="email" wire:model="invitation_email" id="invitation_email" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition" placeholder="employee@example.com">
                         @error('invitation_email')
                         <p class="text-xs text-red-600">{{ $message }}</p>
                         @enderror
                     </div>

                     <div class="space-y-2">
                         <label for="invitation_department_id" class="block text-xs font-medium text-gray-500">Department</label>
                         <select
                             id="invitation_department_id"
                             wire:model="invitation_department_id"
                             class="w-full bg-gray-50 px-3 py-2 text-sm text-slate-900 border border-gray-100 pr-10 focus:outline-none focus:border-emerald-300 focus:bg-white transition select-arrow appearance-none"
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

                     <button type="submit" class="inline-flex w-full items-center justify-center gap-2 bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-emerald-700 hover:shadow-md active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 2L11 13" />
                            <path d="M22 2L15 22L11 13L2 9L22 2Z" />
                        </svg>

                        <span>Send Invitation</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white border border-gray-100 p-2">
                <div class="grid grid-cols-3 gap-1 bg-gray-50 p-1">
                    <button type="button" wire:click="selectTab('departments')" class="px-3 py-2 text-sm font-medium transition flex items-center justify-center gap-1.5 {{ $active_tab === 'departments' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18" />
                        </svg>
                        Departments
                    </button>
                    <button type="button" wire:click="selectTab('employees')" class="px-3 py-2 text-sm font-medium transition flex items-center justify-center gap-1.5 {{ $active_tab === 'employees' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        </svg>
                        Employees
                    </button>
                    <button type="button" wire:click="selectTab('invitations')" class="px-3 py-2 text-sm font-medium transition flex items-center justify-center gap-1.5 {{ $active_tab === 'invitations' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m22 6-10 7L2 6" />
                        </svg>
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
                            <input type="text" wire:model.live.debounce.250ms="department_search" class="w-full bg-gray-50 px-3 py-2 pl-8 text-xs text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition" placeholder="Search departments">
                            <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
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
                            <input type="text" wire:model.live.debounce.250ms="employee_search" class="w-full bg-gray-50 px-3 py-2 pl-8 text-xs uppercase text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition" placeholder="Search name, email, or ID">
                            <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
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
                            @php($deptId = $pivot_map[$employee->id] ?? null)
                            <span class="text-xs font-medium bg-emerald-50 text-emerald-700 px-2.5 py-1">{{ $deptId ? ($department_map[$deptId] ?? 'No department') : 'No department' }}</span>
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
                            <input type="text" wire:model.live.debounce.250ms="invitation_search" class="w-full bg-gray-50 px-3 py-2 pl-8 text-xs uppercase text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition" placeholder="Search email or ID">
                            <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
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
