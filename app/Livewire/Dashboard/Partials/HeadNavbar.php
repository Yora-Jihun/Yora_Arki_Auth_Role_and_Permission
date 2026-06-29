<?php

namespace App\Livewire\Dashboard\Partials;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class HeadNavbar extends Component
{
    public function avatarUrl(): string
    {
        if (auth()->user()?->avatar) {
            return Storage::disk('public')->url('avatars/'.auth()->user()->avatar);
        }

        return asset('assets/images/Jerome_Edica.jpg');
    }

    #[On('profile-updated')]
    public function refreshAvatar(): void {}

    public function notificationCount(): int
    {
        return auth()->user()?->unreadNotifications()->count() ?? 0;
    }

    /**
     * @return Collection<int, DatabaseNotification>
     */
    public function notifications(): Collection
    {
        return auth()->user()?->notifications()->latest()->take(8)->get() ?? collect();
    }

    public function notificationTime(DatabaseNotification $notification): string
    {
        $createdAt = $notification->created_at;

        if ($createdAt === null) {
            return '';
        }

        return $createdAt->diffForHumans();
    }

    public function notificationIcon(string $type): string
    {
        return match ($type) {
            'invitation_received' => 'mail',
            default => 'bell',
        };
    }

    public function markNotificationRead(string $id): void
    {
        $notification = auth()->user()?->notifications()->whereKey($id)->first();

        if ($notification instanceof DatabaseNotification) {
            $notification->markAsRead();
            $this->dispatch('$refresh');
        }
    }

    public function markAllNotificationsRead(): void
    {
        auth()->user()?->unreadNotifications()->update(['read_at' => now()]);
        $this->dispatch('$refresh');
    }

    public function render(): View
    {
        return view('livewire.dashboard.partials.headnavbar');
    }
}
