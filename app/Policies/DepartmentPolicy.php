<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    public function create(User $user, ?Company $company = null): bool
    {
        return $company === null || $company->owner_id === $user->id;
    }

    public function view(User $user, Department $department): bool
    {
        return $department->company->owner_id === $user->id
            || $user->companies()->whereKey($department->company_id)->exists();
    }

    public function update(User $user, Department $department): bool
    {
        return $this->create($user, $department->company);
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->create($user, $department->company);
    }
}