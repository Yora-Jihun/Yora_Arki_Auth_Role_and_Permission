<div class="w-full" @if($cooldown > 0) wire:poll.1s="tick" @endif>
    <div class="overflow-hidden bg-white shadow-2xl shadow-slate-200/70 ring-1 ring-slate-900/5 transition-all duration-300 ease-out">
        <div class="grid min-h-screen grid-cols-1 lg:grid-cols-2 lg:min-h-0">
            @include('livewire.auth.partials.side-landing')

            <section class="flex items-center justify-center bg-white p-8 sm:p-12 lg:p-16">
                <div class="w-full max-w-md space-y-6">
                    <div class="space-y-6">
                        <a href="{{ route('welcome') }}" wire:navigate class="inline-flex items-center gap-3 transition-all duration-200 ease-out hover:opacity-90">
                            <span class="grid h-11 w-11 place-items-center bg-emerald-600 text-sm font-bold text-white">
                                YA
                            </span>
                            <span class="text-lg font-semibold tracking-tight text-slate-950">Yora Arki</span>
                        </a>

                        <div class="space-y-2">
                            <h1 class="text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">
                                Set New Password
                            </h1>
                            <p class="text-sm leading-6 text-slate-500">
                                Enter the verification code sent to your email and choose a new password
                            </p>
                        </div>
                    </div>

                    @if($otpError)
                        @include('partials.alerts', [
                            'type' => 'error',
                            'message' => $otpError,
                            'class' => 'mb-4',
                        ])
                    @endif

                    <form wire:submit="submit" class="space-y-4">
                        <div>
                            @include('livewire.auth.partials.input-field', [
                                'name' => 'otp',
                                'label' => 'Verification Code',
                                'type' => 'text',
                                'placeholder' => '123456',
                                'autocomplete' => 'one-time-code',
                                'attributes' => new \Illuminate\View\ComponentAttributeBag([
                                    'wire:model' => 'otp',
                                    'required' => true,
                                    'maxlength' => '6',
                                    'inputmode' => 'numeric',
                                    'pattern' => '[0-9]*',
                                ]),
                            ])
                        </div>

                        <div>
                            <x-password-input
                                name="password"
                                label="New Password"
                                type="password"
                                placeholder="At least 8 characters"
                                autocomplete="new-password"
                                model="password"
                                required
                            />
                        </div>

                        <div>
                            <x-password-input
                                name="password_confirmation"
                                label="Confirm New Password"
                                type="password"
                                placeholder="Re-enter new password"
                                autocomplete="new-password"
                                model="password_confirmation"
                                required
                            />
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center bg-[#059669] px-4 py-3.5 text-sm font-semibold text-white transition duration-200 hover:bg-[#047857] focus:outline-none focus:ring-4 focus:ring-emerald-500/20"
                        >
                            Update Password
                        </button>

                        <div class="flex justify-between text-sm">
                            <a href="{{ route('forgot.password') }}" wire:navigate class="text-slate-600 hover:underline">
                                Change Email
                            </a>
                            @if($cooldown > 0)
                                <span class="text-slate-400 cursor-not-allowed">
                                    Resend Code in {{ $cooldown }}s
                                </span>
                            @else
                                <button type="button" wire:click="resend" class="text-[#059669] hover:underline">
                                    Resend Code
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
