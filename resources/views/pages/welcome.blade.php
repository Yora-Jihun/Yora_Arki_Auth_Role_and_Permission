<x-landing>
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Our App</h1>
        <p class="text-lg text-gray-600 mb-8 text-center max-w-md">
            A simple and secure authentication system built with Laravel, Livewire, and Tailwind CSS.
        </p>
        <div class="flex space-x-4">
            <a
                href="{{ route('login') }}"
                wire:navigate
                class="rounded-md bg-indigo-600 px-6 py-3 text-white font-semibold hover:bg-indigo-500"
            >
                Login
            </a>
            <a
                href="{{ route('register') }}"
                wire:navigate
                class="rounded-md border border-gray-300 px-6 py-3 text-gray-700 font-semibold hover:bg-gray-50"
            >
                Register
            </a>
        </div>
    </div>
</x-landing>
