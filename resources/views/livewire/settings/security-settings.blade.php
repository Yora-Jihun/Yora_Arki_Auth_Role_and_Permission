<main class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-emerald-700">Security Settings</h1>
            <p class="text-sm text-gray-500 mt-0.5">Update your password to keep your account secure</p>
        </div>
    </div>

    <div class="bg-white border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Change Password</h3>

        @include('partials.alerts', [
        'type' => 'success',
        'message' => session('status'),
        'class' => 'mb-4',
        ])

        <form wire:submit="submit" class="space-y-4 max-w-md">
            @error('auth')
            @include('partials.alerts', [
            'type' => 'error',
            'message' => $message,
            'class' => 'mb-4',
            ])
            @enderror

            <div>
                <x-password-input name="current_password" label="Current Password" type="password" model="current_password" />
            </div>

            <div>
                <x-password-input name="password" label="New Password" type="password" placeholder="At least 8 characters" model="password" />
            </div>

            <div>
                <x-password-input name="password_confirmation" label="Confirm New Password" type="password" placeholder="Re-enter new password" model="password_confirmation" />
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition-all duration-200 hover:shadow-md active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="5" y="11" width="14" height="10" rx="2" />
                        <path d="M8 11V8a4 4 0 0 1 8 0v3" />
                    </svg>

                    <span>Update Password</span>
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white border border-red-200 p-6">
        <h3 class="text-sm font-semibold text-red-700 uppercase tracking-wider mb-2">Danger Zone</h3>
        <p class="text-sm text-gray-500 mb-4">Once you delete your account, all of your data will be permanently removed. This action cannot be undone.</p>

        <button type="button" wire:click="openDeleteModal" class="inline-flex items-center gap-2 px-5 py-2.5 border border-red-600 bg-white text-sm font-medium text-red-600 transition-all duration-200 hover:bg-red-600 hover:text-white hover:shadow-md active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 6h18" />
                <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2" />
                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                <path d="M10 11v6" />
                <path d="M14 11v6" />
            </svg>

            <span>Delete Account Permanently</span>
        </button>
    </div>

    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:click="closeDeleteModal" wire:poll.1s="tick">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-none shadow-xl w-full max-w-md p-6" wire:click.stop>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Account</h3>
            <p class="text-sm text-gray-500 mb-4">
                This will permanently delete your account and all associated data. This action cannot be undone.
            </p>

            @if($deleteError)
            @include('partials.alerts', [
            'type' => 'error',
            'message' => $deleteError,
            'class' => 'mb-4',
            ])
            @endif

            @if($delete_cooldown > 0)
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
                    <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6" wire:model="delete_otp" class="block w-full border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition duration-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10" placeholder="Enter 6-digit code">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                    <button type="button" wire:click="verifyDeleteOtp" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 6l-1 14H6L5 6" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 11v6" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 11v6" />
                        </svg>
                        Delete Account
                    </button>
                </div>
                <p class="text-xs text-gray-400 text-center">Resend code in {{ $delete_cooldown }}s</p>
            </div>
            @else
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deleting account for</label>
                    <div class="block w-full border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900">
                        {{ $delete_email }}
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeDeleteModal" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                    <button type="button" wire:click="sendDeleteOtp" class="px-4 py-2 bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Send Verification Code
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @script
    <script>
        Livewire.on('notify', (message) => alert(message));

    </script>
    @endscript
</main>
