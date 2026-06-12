<x-app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Dashboard</h2>
            <p class="text-gray-700">
                Welcome, <span class="font-semibold">{{ auth()->user()->fullname }}</span>! You have successfully logged in.
            </p>

            <dl class="mt-4 space-y-2 text-sm text-gray-600">
                <div>
                    <dt class="font-medium text-gray-700">first_name</dt>
                    <dd>{{ auth()->user()->first_name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-700">middle_name</dt>
                    <dd>{{ auth()->user()->middle_name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-700">last_name</dt>
                    <dd>{{ auth()->user()->last_name }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-gray-700">fullname</dt>
                    <dd>{{ auth()->user()->fullname }}</dd>
                </div>
            </dl>

            <p class="text-gray-500 mt-2">Email: {{ auth()->user()->email }}</p>
        </div>
    </div>
</x-app>
