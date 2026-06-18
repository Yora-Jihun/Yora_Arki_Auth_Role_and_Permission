<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class ProfileSettings extends Component
{
    public string $first_name = '';

    public string $middle_name = '';

    public string $last_name = '';

    public string $suffix = '';

    public string $email = '';

    public string $country_code = '+63';

    public string $contact_to = '';

    public function mount(): void
    {
        $user = auth()->user();

        if ($user) {
            $this->first_name = (string) $user->first_name;
            $this->middle_name = (string) ($user->middle_name ?? '');
            $this->last_name = (string) $user->last_name;
            $this->suffix = (string) ($user->suffix ?? '');
            $this->email = (string) $user->email;
            $this->country_code = (string) ($user->country_code ?? '+63');
            $this->contact_to = (string) ($user->contact_to ?? '');
        }
    }

    public function submit(): void
    {
        $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'email' => ['required', 'email'],
            'country_code' => ['required', 'string', 'max:10'],
            'contact_to' => ['nullable', 'string', 'max:255'],
        ]);

        $user = auth()->user();

        if ($user) {
            $user->update([
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name ?: null,
                'last_name' => $this->last_name,
                'suffix' => $this->suffix ?: null,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'contact_to' => $this->contact_to,
            ]);
        }

        $this->dispatch('notify', 'Profile updated');
    }

    public function render(): View
    {
        return view('livewire.profile-settings')
            ->layout('layouts.dashboard');
    }
}
