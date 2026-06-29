<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700">Attendance</h1>
            <p class="text-sm text-gray-500 mt-0.5">Time in and time out for your assigned company.</p>
        </div>
    </div>

    @include('partials.alerts', [
        'type' => session('status') ? 'success' : 'info',
        'message' => session('status'),
    ])

    @error('attendance')
        @include('partials.alerts', [
            'type' => 'error',
            'message' => $message,
        ])
    @enderror

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-gray-100 p-6">
                <p class="text-xs font-medium text-gray-500">Current Company</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $membership?->name ?? 'Not joined yet' }}</p>
                <p class="text-xs text-gray-500 mt-2">Department: {{ $department_id ? 'Assigned' : 'No department' }}</p>
            </div>

            <div class="bg-white border border-gray-100 p-6">
                <p class="text-xs font-medium text-gray-500">Today</p>
                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $today?->status?->label() ?? 'No attendance yet' }}</p>
                <div class="space-y-2 mt-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Time In</span>
                        <span class="font-medium text-gray-900">{{ $today?->check_in_at?->format('h:i A') ?? '--:--' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Time Out</span>
                        <span class="font-medium text-gray-900">{{ $today?->check_out_at?->format('h:i A') ?? '--:--' }}</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" wire:click="checkIn" class="flex-1 bg-emerald-600 text-white text-sm font-medium px-4 py-3 hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l2 2"/>
                    </svg>
                    Time In
                </button>
                <button type="button" wire:click="checkOut" class="flex-1 bg-white border border-emerald-600 text-emerald-700 text-sm font-medium px-4 py-3 hover:bg-emerald-50 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l-2 2"/>
                    </svg>
                    Time Out
                </button>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Recent Attendance</h2>
                <div class="space-y-2">
                    @forelse($history as $attendance)
                        <div class="flex items-center justify-between border border-gray-100 p-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $attendance->date->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $attendance->company->name }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-medium px-2.5 py-1 {{ $attendance->status->value === 'checked_out' ? 'bg-gray-100 text-gray-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $attendance->status->label() }}
                                </span>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $attendance->check_in_at?->format('h:i A') ?? '--:--' }} - {{ $attendance->check_out_at?->format('h:i A') ?? '--:--' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">
                            No attendance records yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>