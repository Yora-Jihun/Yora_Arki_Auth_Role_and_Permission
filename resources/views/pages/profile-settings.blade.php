<x-dashboard>
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
                    <h2 class="text-lg font-semibold text-gray-900">Jerome Edica</h2>
                    <p class="text-sm text-gray-500">Employee</p>
                    <p class="text-xs text-gray-400 mt-1">Joined January 2026</p>
                </div>
            </div>

            <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">First Name</label>
                    <input type="text" value="Jerome" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 focus:outline-none focus:border-blue-300 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Last Name</label>
                    <input type="text" value="Edica" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 rounded-lg focus:outline-none focus:border-blue-300 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Email</label>
                    <input type="email" value="jerome@example.com" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 rounded-lg focus:outline-none focus:border-blue-300 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1.5">Phone</label>
                    <input type="text" value="+63 912 345 6789" class="w-full bg-gray-50 px-3 py-2 text-sm text-gray-900 border border-gray-100 rounded-lg focus:outline-none focus:border-blue-300 focus:bg-white transition">
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">Save Changes</button>
            </div>
        </div>
    </main>
</x-dashboard>
