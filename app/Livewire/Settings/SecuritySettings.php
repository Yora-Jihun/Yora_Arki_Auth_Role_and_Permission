<?php

namespace App\Livewire\Settings;

use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class SecuritySettings extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $delete_email = '';

    public string $delete_otp = '';

    public int $delete_cooldown = 0;

    public string $deleteError = '';

    public bool $showDeleteModal = false;

    private OtpService $otpService;

    private const CHANGE_PASSWORD_IP_RATE_LIMIT = 10;

    private const CHANGE_PASSWORD_IP_RATE_TTL = 60;

    public function boot(OtpService $otpService): void
    {
        $this->otpService = $otpService;
    }

    private function validateChangePasswordRateLimit(): void
    {
        $ip = request()->ip();
        $key = "password_change_attempts_{$ip}";
        $attempts = Cache::get($key, 0);

        if ($attempts >= self::CHANGE_PASSWORD_IP_RATE_LIMIT) {
            throw ValidationException::withMessages([
                'current_password' => 'Too many attempts. Please wait before trying again.',
            ]);
        }

        Cache::put($key, $attempts + 1, now()->addMinutes(self::CHANGE_PASSWORD_IP_RATE_TTL / 60));
    }

    private function clearChangePasswordRateLimit(): void
    {
        $ip = request()->ip();
        Cache::forget("password_change_attempts_{$ip}");
    }

    public function mount(): void
    {
        $this->delete_email = auth()->user()->email ?? '';
    }

    public function openDeleteModal(): void
    {
        $this->showDeleteModal = true;
        $this->deleteError = '';
        $this->delete_otp = '';
        $this->delete_cooldown = $this->otpService->getResendCooldownRemaining($this->delete_email, 'account_deletion');
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deleteError = '';
        $this->delete_otp = '';
    }

    public function submit(): void
    {
        $this->validateChangePasswordRateLimit();

        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::defaults()->min(8), 'confirmed'],
        ]);

        if (! password_verify($this->current_password, auth()->user()->password)) {
            $this->addError('current_password', 'The provided password does not match your current password.');

            return;
        }

        auth()->user()->update([
            'password' => $this->password,
        ]);

        $this->clearChangePasswordRateLimit();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('status', 'Password updated successfully');
    }

    public function sendDeleteOtp(): void
    {
        $this->validate([
            'delete_email' => ['required', 'string', 'email'],
        ]);

        $this->deleteError = '';

        try {
            $this->otpService->send($this->delete_email, 'account_deletion');
            $this->delete_cooldown = $this->otpService->getResendCooldownRemaining($this->delete_email, 'account_deletion');
        } catch (ValidationException $e) {
            $this->deleteError = $e->getMessage();
        }
    }

    public function verifyDeleteOtp(): void
    {
        $this->validate([
            'delete_otp' => ['required', 'digits:6'],
        ]);

        $this->deleteError = '';

        if (! $this->otpService->verify($this->delete_email, $this->delete_otp, 'account_deletion', request()->ip())) {
            $this->deleteError = 'Invalid or expired verification code.';
            $this->delete_otp = '';

            return;
        }

        $user = auth()->user();

        Auth::logout();

        $user->delete();

        $this->redirect(route('welcome'), navigate: true);
    }

    public function tick(): void
    {
        if ($this->showDeleteModal) {
            $this->delete_cooldown = $this->otpService->getResendCooldownRemaining($this->delete_email, 'account_deletion');
        }
    }

    public function render(): View
    {
        return view('livewire.settings.security-settings')
            ->layout('layouts.dashboard');
    }
}
