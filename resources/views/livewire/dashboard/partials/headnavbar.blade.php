<div class="sticky top-0 z-30 h-[60px] bg-white/90 backdrop-blur flex items-center justify-end px-6 mb-4">
        <div class="flex items-center gap-3">
            <div class="relative group">
                <button type="button" class="relative p-2 rounded-full hover:bg-gray-100 transition group">
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-900" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9" />
                        <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    @if($this->notificationCount() > 0)
                        <span class="absolute top-1 right-1 min-w-[18px] h-[18px] flex items-center justify-center bg-rose-500 text-white text-[10px] font-bold rounded-full px-1.5 leading-none shadow-sm ring-2 ring-white">{{ $this->notificationCount() }}</span>
                    @endif
                </button>

                <div class="absolute right-0 top-full mt-2 w-[380px] max-w-[calc(100vw-2rem)] bg-white border border-gray-100 shadow-2xl shadow-slate-200/70 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-4 text-white">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold">Notifications</p>
                                <p class="text-xs text-emerald-50 mt-1">Latest activity from your workspace</p>
                            </div>
                            @if($this->notificationCount() > 0)
                                <button type="button" wire:click="markAllNotificationsRead" class="text-[11px] font-medium text-emerald-50 hover:text-white transition">
                                    Mark all read
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="max-h-[420px] overflow-y-auto divide-y divide-gray-100">
                        @forelse($this->notifications() as $notification)
                            <a href="{{ data_get($notification->data, 'url', '#') }}" wire:click="markNotificationRead('{{ $notification->id }}')" class="block p-4 hover:bg-gray-50 transition">
                                <div class="flex gap-3">
                                    <div class="mt-0.5 flex-shrink-0">
                                        @if($this->notificationIcon(data_get($notification->data, 'type', '')) === 'mail')
                                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center ring-1 ring-emerald-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center ring-1 ring-indigo-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .53-.21 1.04-.59 1.41L4 17h5a6 6 0 006-6c0-.85-.18-1.66-.5-2.39" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a2.38 2.38 0 001.02-.23" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate">{{ data_get($notification->data, 'title', 'Notification') }}</p>
                                                @if(! $notification->read_at)
                                                    <span class="inline-flex mt-1 items-center gap-1 text-[11px] font-medium text-emerald-700">
                                                         <span class="w-1.5 h-1.5 bg-emerald-500"></span>
                                                        New
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-gray-400 whitespace-nowrap">{{ $this->notificationTime($notification) }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 leading-5">{{ data_get($notification->data, 'message', '') }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center">
                                <div class="mx-auto w-12 h-12 bg-gray-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .53-.21 1.04-.59 1.41L4 17h5a6 6 0 006-6c0-.85-.18-1.66-.5-2.39" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a2.38 2.38 0 001.02-.23" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-900 mt-3">You are all caught up</p>
                                <p class="text-xs text-gray-500 mt-1">No workspace activity yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="w-px h-6 bg-gray-200"></div>

            <div class="relative group">
                <div class="w-[36px] h-[36px] rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shadow-sm cursor-pointer overflow-hidden ring-2 ring-white">
                    <img src="{{ $this->avatarUrl() }}" class="w-[36px] h-[36px] rounded-full object-cover" alt="Avatar">
                </div>

                <div class="absolute right-0 top-full mt-2 w-56 bg-white border border-gray-100 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50">
                    <div class="p-4 border-b border-gray-50">
                        <span class="block text-sm font-semibold text-gray-900">{{ auth()->user()->fullname ?? 'User' }}</span>
                        <span class="block text-xs text-gray-400 mt-0.5">{{ auth()->user()->roleLabel() }}</span>
                    </div>

                    <div class="p-2 space-y-0.5">
                        <a href="{{ route('profile-settings') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            Profile Settings
                        </a>
                        <a href="{{ route('security-settings') }}" wire:navigate class="flex items-center gap-2.5 px-3 py-2 text-[13px] text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Security
                        </a>
                        <div class="border-t border-gray-50 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-[13px] text-red-600 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                    <polyline points="16 17 21 12 16 7" />
                                    <line x1="21" x2="9" y1="12" y2="12" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>