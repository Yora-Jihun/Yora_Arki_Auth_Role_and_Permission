<?php

namespace App\Livewire\Employee\Invitation;

use App\Enums\InvitationStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Department;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;

class InvitationIndex extends Component
{
    public string $employer_id = '';

    public ?User $searchedEmployer = null;

    public ?int $join_company_id = null;

    public ?int $join_department_id = null;

    public bool $employer_searched = false;

    public function updatedEmployerId(): void
    {
        $this->searchedEmployer = $this->findEmployerById($this->employer_id);
        $this->employer_searched = $this->searchedEmployer instanceof User;
        $this->join_company_id = null;
        $this->join_department_id = null;
    }

    private function findEmployerById(string $employerId): ?User
    {
        $employerId = strtoupper(trim($employerId));

        if ($employerId === '') {
            return null;
        }

        return User::query()
            ->where('role', UserRole::Employer->value)
            ->where('employer_id', $employerId)
            ->with(['companies.departments'])
            ->first();
    }

    public function selectCompany(int $companyId): void
    {
        $this->join_company_id = $companyId;
        $this->join_department_id = null;
    }

    public function joinCompany(): void
    {
        $data = $this->validate([
            'employer_id' => ['required', 'string', 'max:32'],
            'join_company_id' => ['required', 'integer', 'exists:companies,id'],
            'join_department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ]);

        $employer = $this->findEmployerById($this->employer_id);

        if (! $employer instanceof User) {
            $this->addError('employer_id', 'Employer ID not found. Ask the employer to check their profile ID.');

            return;
        }

        $company = $employer->companies()->whereKey($data['join_company_id'])->first();

        if (! $company instanceof Company) {
            $this->addError('join_company_id', 'Selected company was not found.');

            return;
        }

        $department = $data['join_department_id']
            ? $company->departments()->whereKey($data['join_department_id'])->first()
            : null;

        if ($data['join_department_id'] && ! $department instanceof Department) {
            $this->addError('join_department_id', 'Selected department does not belong to this company.');

            return;
        }

        if ($company->employees()->where('user_id', auth()->id())->exists()) {
            $this->addError('join_company_id', 'You already joined this company.');

            return;
        }

        $company->employees()->syncWithoutDetaching([
            auth()->id() => ['department_id' => $department?->id],
        ]);

        session()->flash('status', 'You joined '.$company->name.'.');

        $this->reset(['employer_id', 'searchedEmployer', 'join_company_id', 'join_department_id', 'employer_searched']);
    }

    public function render(): View
    {
        return view('livewire.employee.invitation.invitation-index', [
            'invitations' => Invitation::where(function ($query): void {
                $query->where('email', auth()->user()->email)
                    ->orWhere('employee_id', auth()->id());
            })
                ->where(function ($query): void {
                    $query->where('accepted_by', auth()->id())
                        ->orWhere('status', InvitationStatus::Pending->value);
                })
                ->with(['company', 'department'])
                ->latest()
                ->get(),
        ])->layout('layouts.dashboard');
    }
}