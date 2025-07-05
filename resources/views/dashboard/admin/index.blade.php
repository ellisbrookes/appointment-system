@php
    use Carbon\Carbon;
@endphp

@extends("dashboard.layout")

@section("content")
    <!-- Hero Section -->
    <section id="hero" class="mb-8">
        <h1 class="mb-4 text-4xl font-bold text-black">
            Welcome to Your Admin Dashboard
        </h1>
        <p class="text-lg text-black">
            Manage your upcoming appointments, clients, services, and more.
        </p>
    </section>

    <!-- Quick Stats Cards -->
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- Upcoming Appointments -->
        <div class="rounded-lg border border-gray-300 bg-white p-6 shadow-lg">
            <div class="flex items-center">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full text-green-600 shadow-lg"
                >
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
                <div class="ml-4 text-black">
                    <h3 class="text-lg font-semibold">Upcoming Appointments</h3>
                    <p class="text-3xl font-bold">5</p>
                </div>
            </div>
        </div>

        <!-- Completed Appointments -->
        <div class="rounded-lg border border-gray-300 bg-white p-6 shadow-lg">
            <div class="flex items-center">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full text-yellow-600 shadow-lg"
                >
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div class="ml-4 text-black">
                    <h3 class="text-lg font-semibold">
                        Completed Appointments
                    </h3>
                    <p class="text-3xl font-bold">12</p>
                </div>
            </div>
        </div>

        <!-- Canceled Appointments -->
        <div class="rounded-lg border border-gray-300 bg-white p-6 shadow-lg">
            <div class="flex items-center">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-full text-red-600 shadow-lg"
                >
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
                <div class="ml-4 text-black">
                    <h3 class="text-lg font-semibold">Canceled Appointments</h3>
                    <p class="text-3xl font-bold">2</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments List -->
    <div class="mb-8">
        <h2 class="mb-4 text-2xl font-bold text-black">
            Upcoming Appointments
        </h2>
        <div class="overflow-x-auto">
            <table
                class="w-full table-auto rounded-lg border border-gray-300 bg-white shadow-lg"
            >
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">Client Name</th>
                        <th class="px-6 py-3 text-left">Service</th>
                        <th class="px-6 py-3 text-left">Date</th>
                        <th class="px-6 py-3 text-left">Time</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    <tr class="transition duration-200 hover:bg-gray-100">
                        <td class="border px-6 py-4">John Doe</td>
                        <td class="border px-6 py-4">Haircut</td>
                        <td class="border px-6 py-4">
                            {{ Carbon::parse("2024-06-15")->format("d/m/Y") }}
                        </td>
                        <td class="border px-6 py-4">10:00 AM</td>
                        <td class="border px-6 py-4 text-green-600">
                            Confirmed
                        </td>
                        <td class="border px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-check-circle"></i>
                                Confirm
                            </button>
                            <button
                                class="ml-2 text-red-600 hover:text-red-800"
                            >
                                <i class="fas fa-times-circle"></i>
                                Cancel
                            </button>
                            <button
                                class="ml-2 text-yellow-500 hover:text-yellow-700"
                            >
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr class="transition duration-200 hover:bg-gray-100">
                        <td class="border px-6 py-4">Jane Smith</td>
                        <td class="border px-6 py-4">Hair Coloring</td>
                        <td class="border px-6 py-4">
                            {{ Carbon::parse("2024-06-18")->format("d/m/Y") }}
                        </td>
                        <td class="border px-6 py-4">2:00 PM</td>
                        <td class="border px-6 py-4 text-yellow-600">
                            Pending
                        </td>
                        <td class="border px-6 py-4">
                            <button class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-check-circle"></i>
                                Confirm
                            </button>
                            <button
                                class="ml-2 text-red-600 hover:text-red-800"
                            >
                                <i class="fas fa-times-circle"></i>
                                Cancel
                            </button>
                            <button
                                class="ml-2 text-yellow-500 hover:text-yellow-700"
                            >
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notification Center Section -->
    <div class="mb-8">
        <h2 class="mb-4 text-2xl font-bold text-black">Notification Center</h2>
        <div class="space-y-4">
            <div
                class="flex items-center rounded-lg bg-yellow-100 p-4 text-yellow-800 shadow-lg"
            >
                <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                <div>
                    <h3 class="font-semibold text-black">
                        Upcoming Appointment
                    </h3>
                    <p class="text-black">
                        Reminder: Your appointment with
                        <strong>John Doe</strong>
                        is in 30 minutes!
                    </p>
                </div>
            </div>
            <div
                class="flex items-center rounded-lg bg-blue-100 p-4 text-blue-800 shadow-lg"
            >
                <i class="fas fa-info-circle mr-3 text-xl"></i>
                <div>
                    <h3 class="font-semibold text-black">
                        New Appointment Scheduled
                    </h3>
                    <p class="text-black">
                        You have a new appointment scheduled with
                        <strong>Jane Smith</strong>
                        for a hair coloring.
                    </p>
                </div>
            </div>
            <div
                class="flex items-center rounded-lg bg-green-100 p-4 text-green-800 shadow-lg"
            >
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <div>
                    <h3 class="font-semibold text-black">
                        Appointment Confirmed
                    </h3>
                    <p class="text-black">
                        Your appointment with
                        <strong>John Doe</strong>
                        has been confirmed for
                        <strong>2024-06-15 at 10:00 AM</strong>
                        .
                    </p>
                </div>
            </div>
            <div
                class="flex items-center rounded-lg bg-red-100 p-4 text-red-800 shadow-lg"
            >
                <i class="fas fa-times-circle mr-3 text-xl"></i>
                <div>
                    <h3 class="font-semibold text-black">
                        Appointment Canceled
                    </h3>
                    <p class="text-black">
                        The appointment with
                        <strong>Jane Smith</strong>
                        for a haircut has been canceled.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services List Section -->
    <div class="mb-8">
        <h2 class="mb-4 text-2xl font-bold text-black">Manage Your Services</h2>
        <div class="mb-4 flex items-center justify-between">
            <button
                class="rounded-lg bg-blue-500 px-6 py-2 text-white shadow-lg hover:bg-blue-600"
            >
                <i class="fas fa-plus-circle mr-2"></i>
                Add New Service
            </button>
        </div>
        <div class="overflow-x-auto">
            <table
                class="w-full table-auto rounded-lg border border-gray-300 bg-white shadow-lg"
            >
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left">Service Name</th>
                        <th class="px-6 py-3 text-left">Price</th>
                        <th class="px-6 py-3 text-left">Duration</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="text-black">
                    <tr class="transition duration-200 hover:bg-gray-100">
                        <td class="border px-6 py-4">Haircut</td>
                        <td class="border px-6 py-4">£30</td>
                        <td class="border px-6 py-4">30 min</td>
                        <td class="border px-6 py-4">
                            <button
                                class="text-yellow-500 hover:text-yellow-700"
                            >
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                        </td>
                    </tr>
                    <tr class="transition duration-200 hover:bg-gray-100">
                        <td class="border px-6 py-4">Hair Coloring</td>
                        <td class="border px-6 py-4">£80</td>
                        <td class="border px-6 py-4">90 min</td>
                        <td class="border px-6 py-4">
                            <button
                                class="text-yellow-500 hover:text-yellow-700"
                            >
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
