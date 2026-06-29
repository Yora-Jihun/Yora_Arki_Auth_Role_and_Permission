<div class="w-full">
    <div class="overflow-hidden bg-white shadow-2xl shadow-slate-200/70 ring-1 ring-slate-900/5 transition-all duration-300 ease-out">
        <div class="grid min-h-screen grid-cols-1 lg:grid-cols-2 lg:min-h-0">
            <section class="flex items-center justify-center bg-white p-8 sm:p-12 lg:p-16">
                <div class="w-full max-w-md space-y-6">
                    <div class="space-y-6">
                        <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center gap-3 transition-all duration-200 ease-out hover:opacity-90">
                            <span class="grid h-11 w-11 place-items-center bg-emerald-600 text-sm font-bold text-white">
                                YA
                            </span>
                            <span class="text-lg font-semibold tracking-tight text-slate-950">Yora Arki</span>
                        </a>

                        <div class="space-y-2">
                            <h1 class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">
                                Join Company
                            </h1>
                            <p class="text-sm leading-6 text-slate-500">
                                Review the invitation before joining the workspace.
                            </p>
                        </div>
                    </div>

                    @include('partials.alerts', [
                        'type' => session('status') ? 'success' : 'info',
                        'message' => session('status'),
                    ])

                    @if($accepted)
                        @include('partials.alerts', [
                            'type' => 'success',
                            'message' => 'You already accepted this invitation.',
                        ])

                        <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex w-full items-center justify-center bg-emerald-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                            Go to Dashboard
                        </a>
                    @elseif($invitation->canBeAcceptedBy(auth()->user()))
                        <div class="bg-gray-50 border border-gray-100 p-5 space-y-3">
                            <div>
                                <p class="text-xs font-medium text-gray-500">Company</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $invitation->company->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500">Department</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $invitation->department?->name ?? 'No department assigned' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500">Invited Identity</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $invitation->employee_id ? ($invitation->acceptedBy?->employee_id ?? $invitation->employee_id) : $invitation->email }}</p>
                            </div>
                        </div>

                        <button type="button" wire:click="accept" class="inline-flex w-full items-center justify-center bg-emerald-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-emerald-700 transition">
                            Accept Invitation
                        </button>
                    @else
                        @include('partials.alerts', [
                            'type' => 'error',
                            'message' => 'This invitation is not available for your account.',
                        ])
                    @endif

                    @error('invitation')
                        @include('partials.alerts', [
                            'type' => 'error',
                            'message' => $message,
                        ])
                    @enderror
                </div>
            </section>
        </div>
    </div>
</div>