<div class="bg-white shadow rounded-lg p-8">
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Create an Account</h2>

    <form wire:submit="submit" class="space-y-6">
        @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-50 p-4">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-red-700">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    wire:model="first_name"
                    id="first_name"
                    required
                    autofocus
                    autocomplete="given-name"
                    placeholder="John"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                >
                @error('first_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                <input
                    type="text"
                    wire:model="middle_name"
                    id="middle_name"
                    autocomplete="additional-name"
                    placeholder="Optional"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                >
                @error('middle_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    wire:model="last_name"
                    id="last_name"
                    required
                    autocomplete="family-name"
                    placeholder="Doe"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                >
                @error('last_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                <select
                    wire:model="suffix"
                    id="suffix"
                    autocomplete="honorific-suffix"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                >
                    <option value="">None</option>
                    <option value="Jr.">Jr.</option>
                    <option value="Sr.">Sr.</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                    <option value="V">V</option>
                </select>
                @error('suffix')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input
                type="email"
                wire:model="email"
                id="email"
                required
                autocomplete="email"
                placeholder="you@example.com"
                class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    wire:model="password"
                    id="password"
                    required
                    autocomplete="new-password"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                <input
                    type="password"
                    wire:model="password_confirmation"
                    id="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="block w-full border-0 border-b-2 border-gray-300 bg-transparent py-2 text-gray-900 placeholder-gray-500 focus:border-indigo-500 focus:outline-none focus:ring-0 sm:text-sm"
                >
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <button
                type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                Register
            </button>
        </div>

        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" wire:navigate class="font-medium text-indigo-600 hover:text-indigo-500">
                Login here
            </a>
        </p>
    </form>
</div>
