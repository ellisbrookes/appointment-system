@extends("dashboard.layout")

@section("content")
    <div class="mx-auto flex w-full max-w-7xl flex-col items-center px-4 py-8">
        <h1 class="mb-3 text-4xl font-extrabold text-gray-900 dark:text-gray-100">
            Welcome, {{ auth()->user()->name }}
        </h1>
        <p class="font-semibold mb-8 text-lg text-gray-700 dark:text-gray-300">
            Here you will find all the information you need
        </p>

        <!-- Appointment Status Tiles -->
        <div class="grid w-full max-w-4xl grid-cols-1 gap-6 sm:grid-cols-3">
            <!-- Open Appointments -->
            <div class="rounded-lg border border-gray-300 bg-white p-6 text-center shadow-md transition-shadow hover:shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Open</h2>
                <p class="mt-3 text-4xl font-extrabold text-blue-600 dark:text-blue-400">
                    {{ $appointmentsCount ?? 0 }}
                </p>
                <p class="mt-1 text-gray-500 dark:text-gray-400">active appointments</p>
            </div>

            <!-- Cancelled Appointments -->
            <div class="rounded-lg border border-gray-300 bg-white p-6 text-center shadow-md transition-shadow hover:shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Cancelled</h2>
                <p class="mt-3 text-4xl font-extrabold text-red-600 dark:text-red-400">
                    2
                </p>
                <p class="mt-1 text-gray-500 dark:text-gray-400">cancelled appointments</p>
            </div>

            <!-- Closed Appointments -->
            <div class="rounded-lg border border-gray-300 bg-white p-6 text-center shadow-md transition-shadow hover:shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Closed</h2>
                <p class="mt-3 text-4xl font-extrabold text-green-600 dark:text-green-400">
                    5
                </p>
                <p class="mt-1 text-gray-500 dark:text-gray-400">completed appointments</p>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="mt-12 w-full max-w-4xl rounded-lg border border-gray-300 bg-white p-6 shadow-md dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-4 text-2xl font-bold text-gray-800 dark:text-white">Recent Activity</h2>

            <ul class="space-y-4">
                <li class="flex items-start space-x-4">
                    <div class="h-3 w-3 rounded-full bg-blue-500 mt-1"></div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300">
                            You <span class="font-semibold">booked</span> an appointment for <span class="font-semibold">5th June 2025 at 2:00 PM</span>.
                        </p>
                        <span class="text-sm text-gray-400">2 hours ago</span>
                    </div>
                </li>
                <li class="flex items-start space-x-4">
                    <div class="h-3 w-3 rounded-full bg-red-500 mt-1"></div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300">
                            You <span class="font-semibold">cancelled</span> an appointment for <span class="font-semibold">3rd June 2025 at 1:00 PM</span>.
                        </p>
                        <span class="text-sm text-gray-400">Yesterday</span>
                    </div>
                </li>
                <li class="flex items-start space-x-4">
                    <div class="h-3 w-3 rounded-full bg-green-500 mt-1"></div>
                    <div>
                        <p class="text-gray-700 dark:text-gray-300">
                            Appointment on <span class="font-semibold">1st June 2025 at 11:00 AM</span> was <span class="font-semibold">completed</span>.
                        </p>
                        <span class="text-sm text-gray-400">3 days ago</span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection
