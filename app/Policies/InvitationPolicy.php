<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;

class InvitationPolicy
{
    public function create(User $user, ?Company $company = null): bool
    {
        return $company === null || $company->owner_id === $user->id;
    }

    public function accept(User $user, Invitation $invitation): bool
    {
        return $user->isEmployee() && $invitation->canBeAcceptedBy($user);
    }
}