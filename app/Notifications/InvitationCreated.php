<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvitationCreated extends Notification
{
    use Queueable;

    public function __construct(protected Invitation $invitation) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Company invitation received',
            'message' => $this->invitation->company->name.' invited you to join as an employee.',
            'type' => 'invitation_received',
            'invitation_id' => $this->invitation->id,
            'url' => route('employee.invitations.accept', $this->invitation->token),
        ];
    }
}