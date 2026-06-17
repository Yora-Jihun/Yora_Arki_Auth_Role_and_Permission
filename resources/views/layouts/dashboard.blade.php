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
        @include('livewire.dashboard.partials.sidebar')
        <main class="flex-1 overflow-y-auto bg-white">
            <div class="border-b border-slate-200 bg-white px-6 h-16 flex items-center justify-end">
                <span class="text-sm text-slate-700">{{ auth()->user()->fullname ?? 'User' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="ml-4">
                    @csrf
                    <button type="submit" class="text-sm text-slate-600 hover:text-slate-900 transition">
                        Logout
                    </button>
                </form>
            </div>
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html>