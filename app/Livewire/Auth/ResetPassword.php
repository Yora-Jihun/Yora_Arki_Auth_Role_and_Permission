<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\OtpService;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class ResetPassword extends Component
{
    public string $otp = '';

    public string $password = '';

    public string $password_confirmation = '';

    public int $cooldown = 0;

    public string $otpError = '';

    private OtpService $otpService;

    public function boot(OtpService $otpService): void
    {
        $this->otpService = $otpService;
    }

    public function mount(): void
    {
        if (! session()->has('password_reset_email')) {
            $this->redirect(route('forgot.password'));
        }

        $this->cooldown = $this->otpService->getResendCooldownRemaining(session('password_reset_email'), 'password_reset');
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

        if (! $this->otpService->verify($email, $this->otp, 'password_reset', request()->ip())) {
            $this->otpError = 'Invalid or expired verification code.';

            $this->otp = '';

            return;
        }

        $user = User::where('email', $email)->first();

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

    public function resend(): void
    {
        $email = session()->get('password_reset_email');

        if (! $email) {
            $this->redirect(route('forgot.password'));

            return;
        }

        try {
            $this->otpService->send($email, 'password_reset');
            $this->otp = '';
            $this->cooldown = 60;
            $this->dispatch('otp-resended');
        } catch (ValidationException $e) {
            $this->otpError = $e->getMessage();
        }
    }

    public function tick(): void
    {
        if ($this->cooldown > 0) {
            $this->cooldown--;
        }
    }

    public function render(): View
    {
        return view('livewire.auth.reset-password')
            ->layout('layouts.auth');
    }
}
