<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Services\OtpService;
use App\Support\EmailFormatter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';

    public int $cooldown = 0;

    public string $error = '';

    private OtpService $otpService;

    public function boot(OtpService $otpService): void
    {
        $this->otpService = $otpService;
    }

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns'],
        ];
    }

    public function submit(): void
    {
        $this->email = EmailFormatter::sanitize($this->email) ?? '';

        $this->validate();

        $userExists = User::where('email', $this->email)->exists();

        if (! $userExists) {
            $this->error = 'Account not found.';

            return;
        }

        $this->otpService->send($this->email, 'password_reset');

        session()->put('password_reset_email', $this->email);

        $this->cooldown = $this->otpService->getResendCooldownRemaining($this->email, 'password_reset');

        $this->redirect(route('password.reset'));
    }

    public function resend(): void
    {
        $userExists = User::where('email', $this->email)->exists();

        if (! $userExists) {
            $this->error = 'Account not found.';

            return;
        }

        try {
            $this->otpService->send($this->email, 'password_reset');

            $this->cooldown = $this->otpService->getResendCooldownRemaining($this->email, 'password_reset');
        } catch (ValidationException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function tick(): void
    {
        if ($this->cooldown > 0) {
            $this->cooldown = $this->otpService->getResendCooldownRemaining($this->email, 'password_reset');
        }
    }

    public function render(): View
    {
        return view('livewire.auth.forgot-password')
            ->layout('layouts.auth');
    }
}
