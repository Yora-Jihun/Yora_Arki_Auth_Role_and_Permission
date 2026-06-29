@props([
    'active' => 'attendance',
])

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    .sidebar-item { transition: all 0.18s ease; }
    .sidebar-item:hover { background: #F3F4F6; color: #111827; }
    .sidebar-item.active {
        background: #F3F4F6;
        color: #111827;
        font-weight: 600;
        position: relative;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    .sidebar-item.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        bottom: 8px;
        width: 4px;
        height: auto;
        background: #059669;
        border-radius: 0 3px 3px 0;
    }
    aside::-webkit-scrollbar,
    main::-webkit-scrollbar {
        width: 5px;
    }
    aside::-webkit-scrollbar-track,
    main::-webkit-scrollbar-track {
        background: transparent;
    }
    aside::-webkit-scrollbar-thumb,
    main::-webkit-scrollbar-thumb {
        background: #059669;
        border-radius: 10px;
    }
    aside,
    main {
        scrollbar-width: thin;
        scrollbar-color: #059669 transparent;
    }

    #sidebar.collapsed { width: 76px !important; }
    #sidebar.collapsed .sidebar-label { display: none; }
    #sidebar.collapsed .logo-wrapper { justify-content: center; }
    #sidebar.collapsed .search-wrapper { display: none; }
    #sidebar.collapsed .nav-text { display: none; }
    #sidebar.collapsed .sidebar-item { justify-content: center; padding-left: 0.6rem; padding-right: 0.6rem; }
    #sidebar.collapsed .sidebar-item svg { margin: 0; }
    #sidebar.collapsed .bottom-item { justify-content: center; padding-left: 0.6rem; padding-right: 0.6rem; }
    #sidebar.collapsed #sidebarToggle { transform: rotate(180deg); }
    main { transition: margin-left 0.3s ease; }
</style>

<aside id="sidebar" class="w-[250px] fixed left-0 top-0 h-screen bg-white border-r border-[#EAEAEA] flex flex-col z-50 transition-all duration-300">
     <div class="absolute -right-3 top-6">
        <button id="sidebarToggle" type="button" aria-label="Toggle Sidebar" class="w-6 h-6 bg-white border border-gray-200 rounded-full shadow-sm flex items-center justify-center text-gray-500 hover:bg-gray-50 transition">
            <svg id="collapseIcon" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </button>
    </div>
    <div class="px-3 pt-6 pb-2 flex items-center justify-between logo-wrapper">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-600 flex items-center justify-center shadow-sm flex-shrink-0">
                <span class="text-white font-bold text-sm">YA</span>
            </div>
            <span class="sidebar-label text-[15px] font-semibold text-gray-950 tracking-tight whitespace-nowrap">Yora Arki</span>
        </a>
    </div>

   <div class="px-3 pb-4 search-wrapper">
        <div class="relative">
            <input type="text" placeholder="Search menu..." aria-label="Search menu" class="w-full bg-gray-50 px-3 py-2 pl-9 text-xs text-gray-600 border border-gray-100 focus:outline-none focus:border-emerald-300 focus:bg-white transition">
            <svg class="w-3.5 h-3.5 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
        </div>
    </div>

<nav class="flex-1 overflow-y-auto overflow-x-hidden px-3 space-y-0.5">
        <a href="{{ route('dashboard') }}" wire:navigate
           class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 {{ $active === 'dashboard' ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0 {{ $active === 'dashboard' ? '!text-emerald-600' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span class="nav-text whitespace-nowrap">Dashboard</span>
        </a>

        @if(auth()->user()->isEmployer())
            <a href="{{ route('employer.companies') }}" wire:navigate
               class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 {{ $active === 'company' ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0 {{ $active === 'company' ? '!text-emerald-600' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M3 21h18"/><path d="M5 21V7l8-4 8 4v14"/><path d="M9 21v-6h6v6"/><path d="M9 10h.01"/><path d="M15 10h.01"/>
                </svg>
                <span class="nav-text whitespace-nowrap">Company</span>
            </a>
        @endif

        @if(auth()->user()->isEmployee())
            <a href="{{ route('employee.invitations') }}" wire:navigate
               class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 {{ $active === 'invitations' ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0 {{ $active === 'invitations' ? '!text-emerald-600' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z"/><path d="m22 6-10 7L2 6"/>
                </svg>
                <span class="nav-text whitespace-nowrap">Invitations</span>
            </a>

            <a href="{{ route('employee.attendance') }}" wire:navigate
               class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 {{ $active === 'attendance' ? 'active' : '' }}">
                <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0 {{ $active === 'attendance' ? '!text-emerald-600' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                <span class="nav-text whitespace-nowrap">Attendance</span>
            </a>
        @endif

        <a href="#" wire:navigate
           class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 {{ $active === 'planning' ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0 {{ $active === 'planning' ? '!text-emerald-600' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/>
            </svg>
            <span class="nav-text whitespace-nowrap">Planning</span>
        </a>

        <a href="#" wire:navigate
           class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 {{ $active === 'time' ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0 {{ $active === 'time' ? '!text-emerald-600' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            <span class="nav-text whitespace-nowrap">Time Track</span>
        </a>
    </nav>

    <div class="px-3 py-4 border-t border-gray-100 space-y-0.5">
        <a href="#" wire:navigate class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 bottom-item">
            <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
            </svg>
            <span class="nav-text whitespace-nowrap">Help</span>
        </a>
        <a href="{{ route('profile-settings') }}" wire:navigate class="sidebar-item flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 bottom-item {{ $active === 'settings' ? 'active' : '' }}">
              <svg class="w-[18px] h-[18px] text-gray-500 flex-shrink-0 {{ $active === 'settings' ? '!text-emerald-600' : '' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.26.604.852.997 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z"/>
            </svg>
            <span class="nav-text whitespace-nowrap">Settings</span>
        </a>
    </div>
</aside>

   <script>
        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const toggle = document.getElementById('sidebarToggle');
            const collapseIcon = document.getElementById('collapseIcon');

            if (!sidebar) return;

            if (toggle) {
                toggle.removeEventListener('click', handleToggle);
                toggle.addEventListener('click', handleToggle);
            }

            updateSidebarState();

            window.removeEventListener('resize', handleResize);
            window.addEventListener('resize', handleResize);
        }

        function handleToggle() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            const isCollapsed = sidebar.classList.toggle('collapsed');
            if (isCollapsed) {
                sidebar.style.width = '76px';
                if (main) main.style.marginLeft = '76px';
            } else {
                sidebar.style.width = '250px';
                if (main) main.style.marginLeft = '250px';
            }
        }

        function handleResize() {
            updateSidebarState();
        }

        function updateSidebarState() {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('mainContent');
            if (!sidebar) return;

            if (window.innerWidth < 768) {
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                }
                sidebar.style.width = '76px';
                if (main) main.style.marginLeft = '76px';
            } else {
                sidebar.classList.remove('collapsed');
                sidebar.style.width = '250px';
                if (main) main.style.marginLeft = '250px';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            initSidebar();
        });

        document.addEventListener('livewire:navigated', function () {
            initSidebar();
        });
    </script>