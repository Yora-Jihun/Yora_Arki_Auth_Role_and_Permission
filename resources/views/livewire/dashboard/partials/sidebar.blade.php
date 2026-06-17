@props([
    'active' => 'dashboard',
])

<aside class="w-64 bg-white shadow-sm border-r border-slate-200 flex flex-col h-full">
    <div class="h-16 border-b border-slate-200 flex items-center px-4 flex-shrink-0">
        <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center gap-3" aria-label="Yora Arki">
            <span class="grid h-8 w-8 place-items-center bg-blue-600 text-xs font-bold text-white rounded">
                YA
            </span>
            <span class="text-base font-semibold tracking-tight text-slate-950">Yora Arki</span>
        </a>
    </div>
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <a href="{{ route('dashboard') }}" wire:navigate 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $active === 'dashboard' ? 'text-slate-900 bg-slate-100' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }} transition">
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1 1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1"/>
            </svg>
            Dashboard
        </a>
        <a href="#" wire:navigate 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 11 8 0M12 14v7m-6 0h12a2 2 0 002-2v-3a2 2 0 00-2-2H6a2 2 0 00-2 2v3a2 2 0 00 2 2z"/>
            </svg>
            Profile
        </a>
        <a href="#" wire:navigate 
           class="flex items-center px-3 py-2 text-sm font-medium rounded-md text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
            <svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V1m0 20v-4m0 0l-3-3m3 3l3-3m-3 6h6a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v2a2 2 0 002 2h6"/>
            </svg>
            Settings
        </a>
    </nav>
    <div class="border-t border-slate-200 p-4 flex-shrink-0">
        <span class="text-xs text-slate-500">© 2026 Yora Arki</span>
    </div>
</aside>