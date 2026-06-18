<main class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profile Settings</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage your account information and preferences</p>
        </div>
    </div>

    <div class="bg-white border border-gray-100 p-6">
        <div class="flex items-center gap-6 mb-8 pb-6 border-b border-gray-50">
            <div class="w-[72px] h-[72px] rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-sm overflow-hidden ring-2 ring-white">
                <img src="{{ asset('assets/images/Jerome_Edica.jpg') }}" class="w-[72px] h-[72px] rounded-full object-cover" alt="Jerome Edica">
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ auth()->user()->fullname ?? 'User' }}</h2>
                <p class="text-sm text-gray-500">Employee</p>
                <p class="text-xs text-gray-500 mt-1">Joined January 2026</p>
            </div>
        </div>

        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="first-name" class="block text-xs font-medium text-gray-500 mb-1.5">First Name</label>
                <input id="first-name" type="text" wire:model="first_name" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 focus:outline-none focus:border-blue-300 focus:bg-white transition">
            </div>
            <div>
                <label for="middle-name" class="block text-xs font-medium text-gray-500 mb-1.5">Middle Name</label>
                <input id="middle-name" type="text" wire:model="middle_name" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 focus:outline-none focus:border-blue-300 focus:bg-white transition">
            </div>
            <div>
                <label for="last-name" class="block text-xs font-medium text-gray-500 mb-1.5">Last Name</label>
                <input id="last-name" type="text" wire:model="last_name" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 focus:outline-none focus:border-blue-300 focus:bg-white transition">
            </div>
            <div>
                <label for="suffix" class="block text-xs font-medium text-gray-500 mb-1.5">Suffix</label>
                <select id="suffix" wire:model="suffix" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 focus:outline-none focus:border-blue-300 focus:bg-white transition appearance-none">
                    <option value="">None</option>
                    <option value="Jr">Jr</option>
                    <option value="Sr">Sr</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                </select>
            </div>
            <div>
                <label for="email" class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                <input id="email" type="email" wire:model="email" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 focus:outline-none focus:border-blue-300 focus:bg-white transition">
            </div>
            <div>
                <label for="country-code-contact" class="block text-xs font-medium text-gray-500 mb-1.5">Contact Number</label>
                <div class="flex">
                    <div class="relative shrink-0 w-24">
                        <select id="country-code" wire:model="country_code" class="w-full bg-gray-50 pl-2 pr-7 py-2 text-sm text-gray-900 border border-gray-100 border-r-0 rounded-l appearance-none focus:outline-none focus:border-blue-300 focus:bg-white transition">
                            <option value="+1">&#x1F1FA;&#x1F1F8; +1</option> <!-- US -->
                            <option value="+44">&#x1F1EC;&#x1F1E7; +44</option> <!-- UK -->
                            <option value="+63" selected>&#x1F1F5;&#x1F1ED; +63</option> <!-- Philippines -->
                            <option value="+81">&#x1F1EF;&#x1F1F5; +81</option> <!-- Japan -->
                            <option value="+82">&#x1F1F0;&#x1F1F7; +82</option> <!-- South Korea -->
                            <option value="+91">&#x1F1EE;&#x1F1F3; +91</option> <!-- India -->
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-1.5 pointer-events-none">
                            <svg class="h-3.5 w-3.5 text-gray-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0L5.293 8.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <input id="contact-to" type="text" wire:model="contact_to" placeholder="912 345 6789" class="flex-1 min-w-0 bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 rounded-r focus:outline-none focus:border-blue-300 focus:bg-white transition">
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button wire:click="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">Save Changes</button>
        </div>

        @script
        <script>
            Livewire.on('notify', (message) => alert(message));

        </script>
        @endscript
    </div>
</main>
