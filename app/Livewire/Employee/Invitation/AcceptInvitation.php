<?php

namespace App\Livewire\Employee\Invitation;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class AcceptInvitation extends Component
{
    public Invitation $invitation;

    public bool $accepted = false;

    public function mount(string $token): void
    {
        $invitation = Invitation::where('token', $token)
            ->with(['company', 'department'])
            ->first();

        if (! $invitation instanceof Invitation) {
            abort(404);
        }

        $this->invitation = $invitation;
        $this->accepted = $invitation->accepted_by === auth()->id();
    }

    public function accept(): void
    {
        $this->authorize('accept', $this->invitation);

        $acceptedBy = $this->invitation->employee_id
            ? User::query()->whereKey($this->invitation->employee_id)->first()
            : auth()->user();

        if (! $acceptedBy instanceof User) {
            $this->addError('invitation', 'This invitation is not available for your account.');

            return;
        }

        DB::table('company_user')->updateOrInsert(
            [
                'company_id' => $this->invitation->company_id,
                'user_id' => $acceptedBy->id,
            ],
            [
                'department_id' => $this->invitation->department_id,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->invitation->markAccepted($acceptedBy);

        session()->flash('status', 'Invitation accepted. You joined the company.');

        $this->redirect(route('employee.attendance'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.employee.invitation.accept-invitation')->layout('layouts.auth');
    }
}
