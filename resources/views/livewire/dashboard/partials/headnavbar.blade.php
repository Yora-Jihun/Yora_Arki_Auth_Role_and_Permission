<nav class="bg-white shadow-sm border-b border-slate-200 h-16 flex-shrink-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
        <div class="flex justify-between h-full items-center">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center gap-3" aria-label="Yora Arki">
                    <span class="grid h-9 w-9 place-items-center bg-blue-600 text-xs font-bold text-white rounded">
                        YA
                    </span>
                    <span class="text-lg font-semibold tracking-tight text-slate-950">Yora Arki</span>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-slate-700">{{ auth()->user()->fullname ?? 'User' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-600 hover:text-slate-900 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>