<div class="bg-white shadow rounded-lg p-8">
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Welcome Back</h2>

    <form wire:submit="submit" class="space-y-6">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 p-4">
                <p class="text-sm text-red-700">Invalid credentials. Please try again.</p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    wire:model="email"
                    id="email"
                    required
                    autofocus
                    autocomplete="email"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                    type="password"
                    wire:model="password"
                    id="password"
                    required
                    autocomplete="current-password"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center">
            <input
                type="checkbox"
                wire:model="remember"
                id="remember"
                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>

        <div>
            <button
                type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Sign in
            </button>
        </div>

        <p class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" wire:navigate class="font-medium text-indigo-600 hover:text-indigo-500">
                Register here
            </a>
        </p>
    </form>
</div>
