<?php

namespace App\Livewire\Employer\Company;

use App\Enums\InvitationStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\Department;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\InvitationCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class CompanyShow extends Component
{
    public Company $company;

    public string $department_name = '';

    public string $department_description = '';

    public string $invitation_employee_id = '';

    public ?User $invitation_employee = null;

    public ?int $invitation_department_id = null;

    public string $department_search = '';

    public string $employee_search = '';

    public string $invitation_search = '';

    public string $active_tab = 'departments';

    public function mount(Company $company): void
    {
        $this->authorize('view', $company);

        $this->company = $company;
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function departmentRules(): array
    {
        return [
            'department_name' => ['required', 'string', 'max:255'],
            'department_description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function invitationRules(): array
    {
        return [
            'invitation_employee_id' => ['required', 'string', 'max:32'],
            'invitation_department_id' => ['nullable', 'integer', 'exists:departments,id'],
        ];
    }

    public function createDepartment(): void
    {
        $this->authorize('create', [Department::class, $this->company]);

        $data = $this->validate($this->departmentRules());

        $this->company->departments()->create([
            'name' => $data['department_name'],
            'description' => $data['department_description'],
        ]);

        session()->flash('department_status', 'Department created successfully.');

        $this->reset(['department_name', 'department_description']);
    }

    public function updatedInvitationEmployeeId(): void
    {
        $this->invitation_employee = $this->findEmployeeById($this->invitation_employee_id);
    }

    public function selectTab(string $tab): void
    {
        $this->active_tab = in_array($tab, ['departments', 'employees', 'invitations'], true) ? $tab : 'departments';
    }

    private function findEmployeeById(string $employeeId): ?User
    {
        $employeeId = strtoupper(trim($employeeId));

        if ($employeeId === '') {
            return null;
        }

        return User::query()
            ->where('role', UserRole::Employee->value)
            ->where('employee_id', $employeeId)
            ->first();
    }

    public function sendInvitation(): void
    {
        $this->authorize('create', $this->company);

        $this->invitation_employee_id = strtoupper(trim($this->invitation_employee_id));

        $data = $this->validate($this->invitationRules());

        $employee = $this->findEmployeeById($data['invitation_employee_id']);

        if (! $employee instanceof User) {
            $this->addError('invitation_employee_id', 'Employee ID not found. Ask the employee to register first.');

            return;
        }

        if ($this->company->employees()->where('user_id', $employee->id)->exists()) {
            $this->addError('invitation_employee_id', 'This employee already belongs to this company.');

            return;
        }

        if ($this->company->invitations()->where('employee_id', $employee->id)->where('status', InvitationStatus::Pending->value)->exists()) {
            $this->addError('invitation_employee_id', 'An active invitation already exists for this employee.');

            return;
        }

        $department = $data['invitation_department_id']
            ? Department::query()
                ->where('company_id', $this->company->id)
                ->whereKey($data['invitation_department_id'])
                ->first()
            : null;

        if ($data['invitation_department_id'] && ! $department instanceof Department) {
            $this->addError('invitation_department_id', 'The selected department does not belong to this company.');

            return;
        }

        $invitation = Invitation::create([
            'company_id' => $this->company->id,
            'department_id' => $department?->id,
            'invited_by' => auth()->id(),
            'employee_id' => $employee->id,
            'email' => '',
            'token' => Str::random(32),
            'status' => InvitationStatus::Pending,
            'expires_at' => now()->addDays(7),
        ]);

        $employee->notify(new InvitationCreated($invitation));

        session()->flash('invitation_status', 'Invitation created successfully.');

        $this->reset(['invitation_employee_id', 'invitation_employee', 'invitation_department_id']);
    }

    public function render(): View
    {
        $departments = $this->company->departments()
            ->when($this->department_search !== '', function ($query): void {
                $query->where('name', 'like', '%'.$this->department_search.'%')
                    ->orWhere('description', 'like', '%'.$this->department_search.'%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $employees = $this->company->employees()
            ->withPivot('department_id')
            ->when($this->employee_search !== '', function ($query): void {
                $query->where('users.fullname', 'like', '%'.$this->employee_search.'%')
                    ->orWhere('users.email', 'like', '%'.$this->employee_search.'%')
                    ->orWhere('users.employee_id', 'like', '%'.strtoupper($this->employee_search).'%');
            })
            ->orderBy('company_user.updated_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        $pivotMap = DB::table('company_user')
            ->where('company_id', $this->company->id)
            ->pluck('department_id', 'user_id');

        $departmentMap = Department::query()
            ->whereIn('id', $pivotMap->filter()->values()->all())
            ->pluck('name', 'id');

        $invitations = $this->company->invitations()
            ->when($this->invitation_search !== '', function ($query): void {
                $query->where('email', 'like', '%'.$this->invitation_search.'%')
                    ->orWhere('employee_id', 'like', '%'.$this->invitation_search.'%');
            })
            ->with(['department', 'acceptedBy'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('livewire.employer.company.company-show', [
            'departments' => $departments,
            'department_map' => $departmentMap->toArray(),
            'pivot_map' => $pivotMap->toArray(),
            'invitations' => $invitations,
            'employees' => $employees,
        ])->layout('layouts.dashboard');
    }
}
