@extends('layouts.dashboard')

@section('content')
    @php
        $user = auth()->user();
        $companies = $user->companies()->withCount(['employees', 'departments', 'invitations'])->latest()->take(5)->get();
        $companyCount = $user->companies()->count();
        $totalEmployees = \Illuminate\Support\Facades\DB::table('companies')
            ->join('company_user', 'companies.id', '=', 'company_user.company_id')
            ->where('companies.owner_id', $user->id)
            ->count('company_user.user_id');
        $companyIds = \App\Models\Company::where('owner_id', $user->id)->select('id');
        $membership = $user->companies()->withPivot('department_id')->first();
        $todayDate = \Illuminate\Support\Carbon::today();
        $attendanceChartLabels = [];
        $attendanceChartHours = [];
        $weeklyHours = 0;
        $presentDays = 0;
        $checkedOutDays = 0;

        for ($day = 0; $day < 7; $day++) {
            $chartDate = $todayDate->copy()->subDays(6 - $day);
            $chartAttendance = $membership
                ? $user->attendances()->where('company_id', $membership->id)->where('date', $chartDate)->first()
                : null;
            $workMinutes = $chartAttendance?->check_in_at && $chartAttendance?->check_out_at
                ? $chartAttendance->check_in_at->diffInMinutes($chartAttendance->check_out_at)
                : 0;
            $workHours = round($workMinutes / 60, 2);

            $attendanceChartLabels[] = $chartDate->format('D');
            $attendanceChartHours[] = $workHours;
            $weeklyHours += $workHours;
            $presentDays += $workMinutes > 0 ? 1 : 0;
            $checkedOutDays += $chartAttendance?->status?->value === \App\Enums\AttendanceStatus::CheckedOut->value ? 1 : 0;
        }

        $pendingInvitations = \App\Models\Invitation::whereIn('company_id', $companyIds)
            ->where('status', \App\Enums\InvitationStatus::Pending->value)
            ->count();
        $presentToday = \App\Models\Attendance::whereIn('company_id', $companyIds)
            ->where('date', \Illuminate\Support\Carbon::today())
            ->where('status', \App\Enums\AttendanceStatus::CheckedIn->value)
            ->count();
        $todayAttendance = $membership
            ? $user->attendances()->where('company_id', $membership->id)->where('date', \Illuminate\Support\Carbon::today())->first()
            : null;
        $employeePendingInvitations = \App\Models\Invitation::where(function ($query) use ($user): void {
            $query->where('email', $user->email)
                ->orWhere('employee_id', $user->id);
        })
            ->where('status', \App\Enums\InvitationStatus::Pending->value)
            ->count();
    @endphp

    <main class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-emerald-700">{{ $user->roleLabel() }} Dashboard</h1>
                <p class="text-sm text-gray-500 mt-0.5">Welcome back, {{ $user->fullname ?? 'User' }}</p>
            </div>
        </div>

        @if($user->isEmployer())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Companies</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $companyCount }}</p>
                </div>
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Total Employees</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalEmployees }}</p>
                </div>
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Present Today</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $presentToday }}</p>
                </div>
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Pending Invitations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pendingInvitations }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white p-6 border border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Company Overview</p>
                    <div class="space-y-3">
                        @forelse($companies as $company)
                            <a href="{{ route('employer.companies.show', $company) }}" wire:navigate class="flex items-center justify-between border border-gray-100 p-4 hover:border-emerald-200 transition">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $company->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $company->departments_count }} departments · {{ $company->employees_count }} employees</p>
                                </div>
                                <span class="text-xs font-medium text-emerald-700">Manage</span>
                            </a>
                        @empty
                            <div class="border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">
                                No companies yet. Create your first company to begin.
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Employer Actions</p>
                    <div class="space-y-2">
                        <a href="{{ route('employer.companies') }}" wire:navigate class="block w-full text-left px-4 py-3 bg-emerald-50 hover:bg-emerald-100 text-sm font-medium text-emerald-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M5 21V7l8-4 8 4v14"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 21v-6h6v6"/>
                            </svg>
                            Create Company
                        </a>
                        <a href="{{ $companies->first()?->getRouteKey() ? route('employer.companies.show', $companies->first()) : route('employer.companies') }}" wire:navigate class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 text-sm text-gray-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 19.5v-6m-6 6v-6m6 0V7.5"/><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5v-3a2 2 0 0 1 2-2h13a2 2 0 0 1 2 2v3"/><rect width="20" height="15" x="2" y="7.5" rx="2" ry="2"/>
                            </svg>
                            Invite Employee
                        </a>
                        <a href="{{ $companies->first()?->getRouteKey() ? route('employer.companies.show', $companies->first()) : route('employer.companies') }}" wire:navigate class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 text-sm text-gray-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h18"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5h18"/>
                            </svg>
                            Manage Departments
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 border border-gray-100">
                <p class="text-sm font-semibold text-gray-900 mb-2">Company Overview</p>
                <p class="text-xs text-gray-500 mb-5">Employee and department count by company.</p>
                <div class="h-72">
                    <canvas id="employerCompanyChart"></canvas>
                </div>
            </div>

            @php
                $employerChartLabels = $companies->pluck('name')->toArray();
                $employerChartEmployees = $companies->pluck('employees_count')->toArray();
                $employerChartDepartments = $companies->pluck('departments_count')->toArray();
            @endphp
            <script>
                const renderEmployerDashboardChart = () => {
                    const canvas = document.getElementById('employerCompanyChart');
                    if (!canvas || typeof Chart === 'undefined') { return; }
                    Chart.getChart(canvas)?.destroy();

                    new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: @json($employerChartLabels),
                            datasets: [
                                {
                                    label: 'Employees',
                                    data: @json($employerChartEmployees),
                                    backgroundColor: 'rgba(5, 150, 105, 0.7)',
                                    borderColor: '#059669',
                                    borderWidth: 1,
                                    borderRadius: 4,
                                },
                                {
                                    label: 'Departments',
                                    data: @json($employerChartDepartments),
                                    backgroundColor: 'rgba(107, 114, 128, 0.5)',
                                    borderColor: '#6B7280',
                                    borderWidth: 1,
                                    borderRadius: 4,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { intersect: false, mode: 'index' },
                            plugins: {
                                legend: { display: true, position: 'top', labels: { usePointStyle: true, pointStyle: 'rectRounded', padding: 16, font: { size: 12 } } },
                                tooltip: {
                                    backgroundColor: '#1F2937',
                                    titleFont: { size: 13 },
                                    bodyFont: { size: 12 },
                                    padding: 10,
                                    cornerRadius: 6,
                                },
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#F3F4F6' } },
                            },
                        },
                    });
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', renderEmployerDashboardChart);
                } else {
                    renderEmployerDashboardChart();
                }

                document.addEventListener('livewire:navigated', renderEmployerDashboardChart);
            </script>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Today's Status</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayAttendance?->status?->label() ?? 'Not Timed In' }}</p>
                </div>
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Clock In</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayAttendance?->check_in_at?->format('h:i A') ?? '--' }}</p>
                </div>
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Clock Out</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayAttendance?->check_out_at?->format('h:i A') ?? '--' }}</p>
                </div>
                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium mb-1">Pending Invitations</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $employeePendingInvitations }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white p-6 border border-gray-100">
                    <div class="flex items-start justify-between gap-4 mb-5">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Attendance Trend</p>
                            <p class="text-xs text-gray-500 mt-1">Last 7 working days based on clock-in and clock-out time.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">{{ number_format($weeklyHours, 1) }}h</span>
                            <span class="inline-flex items-center bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-600">{{ $presentDays }}/7 days</span>
                        </div>
                    </div>

                    <div class="h-72">
                        <canvas id="employeeAttendanceChart"></canvas>
                    </div>

                    <script>
                        const renderEmployeeDashboardChart = () => {
                            if (typeof window.renderEmployeeAttendanceChart !== 'function') {
                                return;
                            }

                            window.renderEmployeeAttendanceChart({
                                labels: @json($attendanceChartLabels),
                                hours: @json($attendanceChartHours),
                            });
                        };

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', renderEmployeeDashboardChart);
                        } else {
                            renderEmployeeDashboardChart();
                        }

                        document.addEventListener('livewire:navigated', renderEmployeeDashboardChart);
                    </script>
                </div>

                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Employee Actions</p>
                    <div class="space-y-2">
                        <a href="{{ route('employee.attendance') }}" wire:navigate class="block w-full text-left px-4 py-3 bg-emerald-50 hover:bg-emerald-100 text-sm font-medium text-emerald-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                            Clock In / Clock Out
                        </a>
                        <a href="{{ route('employee.invitations') }}" wire:navigate class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 text-sm text-gray-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m22 6-10 7L2 6"/>
                            </svg>
                            View Invitations
                        </a>
                        <button class="block w-full text-left px-4 py-3 bg-gray-50 hover:bg-gray-100 text-sm text-gray-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 2v4"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4"/><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18"/>
                            </svg>
                            Create Leave Request
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white p-6 border border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Current Membership</p>
                    @if($membership)
                        <div class="space-y-3">
                            <div class="border border-gray-100 p-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $membership->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $membership->pivot->department_id ? 'Department assigned' : 'No department assigned' }}</p>
                            </div>
                            <div class="border border-gray-100 p-4">
                                <p class="text-sm font-semibold text-gray-900">{{ $todayAttendance?->status?->label() ?? 'No time in today' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Use the attendance page to time in or time out.</p>
                            </div>
                        </div>
                    @else
<div class="border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">
                             Accept an invitation to join a company before time in.
                         </div>
                    @endif
                </div>

                <div class="bg-white p-6 border border-gray-100">
                    <p class="text-sm font-semibold text-gray-900 mb-4">Weekly Summary</p>
                    <div class="space-y-3">
                            <div class="bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">Clock Out Days</p>
                            <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $checkedOutDays }}</p>
                        </div>
                            <div class="bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">Present Days</p>
                            <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $presentDays }}</p>
                        </div>
                            <div class="bg-gray-50 p-4">
                            <p class="text-xs text-gray-500">Pending Invitations</p>
                            <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $employeePendingInvitations }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>
@endsection