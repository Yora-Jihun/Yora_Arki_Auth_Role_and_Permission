<x-landing>
    <div class="min-h-screen flex items-center justify-center px-4 py-12 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-100 via-white to-white">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
            <div class="text-center lg:text-left space-y-6">
                <a href="{{ route('welcome') }}" wire:navigate class="inline-flex items-center justify-center gap-3" aria-label="Yora Arki Home">
                    <span class="grid h-14 w-14 place-items-center bg-emerald-600 text-lg font-bold text-white rounded-none shadow-lg shadow-emerald-600/20">
                        YA
                    </span>
                </a>

                <div class="space-y-3">
                    <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-slate-950">
                        Welcome to <span class="text-emerald-600">Yora Arki</span>
                    </h1>
                    <p class="text-base sm:text-lg leading-relaxed text-slate-500 max-w-lg mx-auto lg:mx-0">
                        A modern authentication architecture built with Laravel, Livewire, and Tailwind CSS. Secure, fast, and ready to scale.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a
                        href="{{ route('login') }}"
                        wire:navigate
                        class="inline-flex items-center justify-center bg-[#059669] px-8 py-3.5 text-sm font-semibold text-white rounded-none transition duration-200 hover:bg-[#047857] hover:shadow-lg hover:shadow-emerald-600/20 focus:outline-none focus:ring-4 focus:ring-emerald-500/20"
                    >
                        Sign in
                    </a>
                    <a
                        href="{{ route('register') }}"
                        wire:navigate
                        class="inline-flex items-center justify-center border border-slate-200 bg-white px-8 py-3.5 text-sm font-semibold text-slate-700 rounded-none transition duration-200 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-4 focus:ring-slate-200"
                    >
                        Create account
                    </a>
                </div>

                <p class="text-xs text-slate-400 pt-2">
                    Start local, deploy later.
                </p>
            </div>

            <div class="hidden lg:block">
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-emerald-100/50 to-slate-100/50 rounded-none blur-2xl"></div>
                    <div class="relative bg-white rounded-none border border-slate-100 shadow-xl shadow-slate-200/50 p-8 space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="h-2.5 w-2.5 rounded-none bg-red-400"></div>
                            <div class="h-2.5 w-2.5 rounded-none bg-yellow-400"></div>
                            <div class="h-2.5 w-2.5 rounded-none bg-green-400"></div>
                        </div>
                        <div class="space-y-4">
                            <div class="h-3 w-3/4 rounded bg-slate-100"></div>
                            <div class="h-3 w-1/2 rounded bg-slate-100"></div>
                            <div class="h-3 w-2/3 rounded bg-slate-100"></div>
                            <div class="h-3 w-5/6 rounded bg-slate-100"></div>
                            <div class="h-3 w-1/3 rounded bg-slate-100"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-3 pt-2">
                            <div class="h-20 rounded-none bg-slate-50 border border-slate-100"></div>
                            <div class="h-20 rounded-none bg-slate-50 border border-slate-100"></div>
                            <div class="h-20 rounded-none bg-slate-50 border border-slate-100"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="h-16 rounded-none bg-emerald-50 border border-emerald-100"></div>
                            <div class="h-16 rounded-none bg-slate-50 border border-slate-100"></div>
                        </div>
                        <div class="h-10 rounded-none bg-emerald-200 w-1/3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-landing>