<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function create(User $user): bool
    {
        return $user->isEmployee();
    }

    public function view(User $user, Attendance $attendance): bool
    {
        return $attendance->user_id === $user->id
            || $attendance->company->owner_id === $user->id;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function checkIn(User $user, Attendance $attendance): bool
    {
        return $attendance->user_id === $user->id && $user->isEmployee();
    }

    public function checkOut(User $user, Attendance $attendance): bool
    {
        return $attendance->user_id === $user->id && $user->isEmployee();
    }
}