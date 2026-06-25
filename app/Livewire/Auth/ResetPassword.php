<?php

namespace App\Livewire\Auth;

use App\Services\AuthService;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $otp = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $otpError = '';

    private AuthService $authService;

    public function boot(AuthService $authService): void
    {
        $this->authService = $authService;
    }

    public function mount(): void
    {
        if (! session()->has('password_reset_email')) {
            $this->redirect(route('forgot.password'));
        }
    }

    protected function rules(): array
    {
        return [
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', Password::defaults()->min(8), 'confirmed'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        $email = session()->get('password_reset_email');

        if (! app(\App\Services\OtpService::class)->verify($email, $this->otp, 'password_reset', request()->ip())) {
            $this->otpError = 'Invalid or expired verification code.';

            $this->otp = '';

            return;
        }

        $user = \App\Models\User::where('email', $email)->first();

        if (! $user) {
            $this->addError('password', 'User not found.');

            return;
        }

        $user->update([
            'password' => $this->password,
        ]);

        session()->forget(['password_reset_email']);

        $this->redirect(route('login'));
    }

    public function render(): View
    {
        return view('livewire.auth.reset-password')
            ->layout('layouts.auth');
    }
}
