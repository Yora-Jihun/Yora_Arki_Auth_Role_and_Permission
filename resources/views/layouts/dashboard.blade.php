<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <meta name="description" content="Yora Arki Dashboard - Manage your account and settings">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
</head>
<body class="min-h-screen bg-gray-100">
    <div class="flex h-screen">
        @include('livewire.dashboard.partials.sidebar', ['active' => match(request()->route()->getName()) { 'dashboard' => 'dashboard', 'employer.companies', 'employer.companies.show' => 'company', 'employee.invitations', 'employee.invitations.accept' => 'invitations', 'employee.attendance' => 'attendance', 'profile-settings', 'security-settings' => 'settings', default => 'dashboard' }])
        <main id="mainContent" class="flex-1 overflow-y-auto overflow-x-hidden bg-[#F8F9FB] transition-all duration-300" style="margin-left: 250px;">
            @livewire('dashboard.partials.headnavbar')
            <div class="px-8 pt-4 min-h-full">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot }}
                @endif
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>
