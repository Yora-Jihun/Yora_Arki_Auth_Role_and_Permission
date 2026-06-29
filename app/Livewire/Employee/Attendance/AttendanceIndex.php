<?php

namespace App\Livewire\Employee\Attendance;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class AttendanceIndex extends Component
{
    public function checkIn(): void
    {
        $membership = $this->currentMembership();
        $company = $membership['company'];

        if (! $company instanceof Company) {
            $this->addError('attendance', 'You must join a company before checking in.');

            return;
        }

        $attendance = Attendance::firstOrCreate([
            'user_id' => auth()->id(),
            'company_id' => $company->id,
            'date' => Carbon::today(),
        ], [
            'department_id' => $membership['department_id'],
            'status' => AttendanceStatus::Pending,
        ]);

        $this->authorize('checkIn', $attendance);

        if ($attendance->status === AttendanceStatus::CheckedIn) {
            $this->addError('attendance', 'You already checked in today.');

            return;
        }

        $attendance->checkIn(Carbon::now());

        session()->flash('status', 'Check-in recorded successfully.');
    }

    public function checkOut(): void
    {
        $membership = $this->currentMembership();
        $company = $membership['company'];

        if (! $company instanceof Company) {
            $this->addError('attendance', 'You must join a company before checking out.');

            return;
        }

        $attendance = Attendance::where('user_id', auth()->id())
            ->where('company_id', $company->id)
            ->where('date', Carbon::today())
            ->first();

        if (! $attendance instanceof Attendance) {
            $this->addError('attendance', 'No check-in record found for today.');

            return;
        }

        $this->authorize('checkOut', $attendance);

        if ($attendance->status === AttendanceStatus::CheckedOut) {
            $this->addError('attendance', 'You already checked out today.');

            return;
        }

        $attendance->checkOut(Carbon::now());

        session()->flash('status', 'Check-out recorded successfully.');
    }

    public function render(): View
    {
        $membership = $this->currentMembership();
        $company = $membership['company'] ?? null;
        $departmentId = $membership['department_id'] ?? null;
        $departmentName = $departmentId ? Department::whereKey($departmentId)->value('name') : null;
        $today = Attendance::where('user_id', auth()->id())
            ->when($company, fn ($query) => $query->where('company_id', $company->id))
            ->where('date', Carbon::today())
            ->first();

        return view('livewire.employee.attendance.attendance-index', [
            'membership' => $company,
            'department_name' => $departmentName,
            'today' => $today,
            'history' => Attendance::where('user_id', auth()->id())
                ->with(['company', 'department'])
                ->latest('date')
                ->take(10)
                ->get(),
        ])->layout('layouts.dashboard');
    }

    /**
     * @return array{company: Company|null, department_id: int|null}
     */
    private function currentMembership(): array
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return ['company' => null, 'department_id' => null];
        }

        $membership = DB::table('company_user')
            ->where('user_id', $user->id)
            ->first();

        if (! $membership instanceof \stdClass || $membership->company_id === null) {
            return ['company' => null, 'department_id' => null];
        }

        $company = Company::query()->whereKey($membership->company_id)->first();

        if (! $company instanceof Company) {
            return ['company' => null, 'department_id' => null];
        }

        return [
            'company' => $company,
            'department_id' => $membership->department_id,
        ];
    }
}
