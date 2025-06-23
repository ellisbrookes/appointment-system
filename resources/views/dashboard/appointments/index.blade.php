@php
    use Carbon\Carbon;
@endphp

@extends("dashboard.layout")

@section("content")
    <div class="container mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h2
                class="text-center text-4xl font-bold text-gray-700 dark:text-white"
            >
                Appointments List
            </h2>
            <a
                href="{{ route("dashboard.appointments.create.step.one") }}"
                class="rounded-lg bg-green-600 px-6 py-3 text-white transition duration-200 hover:bg-green-700 dark:text-white"
            >
                Book Appointment
            </a>
        </div>

        {{-- Filter Form --}}
        <div class="mb-6 flex items-center justify-between">
            <form
                method="GET"
                action="{{ route("dashboard.appointments.index") }}"
                class="flex items-center space-x-3"
            >
                <label for="status">Filter by Status:</label>
                <select
                    name="status"
                    id="status"
                    onchange="this.form.submit()"
                    class="w-48 rounded-xl border border-gray-300 bg-white px-4 py-3 text-base text-gray-700 shadow-sm transition focus:border-blue-400 focus:ring-2 focus:ring-blue-300 focus:outline-none dark:bg-gray-800 dark:text-white"
                >
                    <option value="">All</option>
                    <option
                        value="open"
                        {{ request("status") == "open" ? "selected" : "" }}
                    >
                        Open
                    </option>
                    <option
                        value="closed"
                        {{ request("status") == "closed" ? "selected" : "" }}
                    >
                        Closed
                    </option>
                    <option
                        value="cancelled"
                        {{ request("status") == "cancelled" ? "selected" : "" }}
                    >
                        Cancelled
                    </option>
                </select>
            </form>
        </div>

        @if ($appointments->isEmpty())
            <p class="py-4 text-center text-gray-500 dark:text-white">
                No booked appointments
            </p>
        @else
            <div class="overflow-x-auto">
                <table
                    class="text-md w-full table-auto border-collapse border border-gray-400 text-center md:table-fixed"
                >
                    <thead>
                        <tr
                            class="bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-white"
                        >
                            <th class="border border-gray-300 px-6 py-3">
                                Service
                            </th>
                            <th class="border border-gray-300 px-6 py-3">
                                Date
                            </th>
                            <th class="border border-gray-300 px-6 py-3">
                                User
                            </th>
                            <th class="border border-gray-300 px-6 py-3">
                                Timeslot
                            </th>
                            <th class="border border-gray-300 px-6 py-3">
                                Status
                            </th>
                            <th class="border border-gray-300 px-6 py-3">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointments as $appointment)
                            <tr
                                class="even:bg-gray-50 hover:bg-gray-100 dark:text-white dark:even:bg-gray-900 dark:hover:bg-gray-900"
                            >
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ $appointment->service }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ Carbon::parse($appointment->date)->format("jS F Y") }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4">
                                    {{ $appointment->user->name ?? "Guest" }}
                                </td>
                                <td class="border border-gray-300 px-6 py-4">
                                    @php
                                        $settings = auth()->user()->settings ?? [];
                                        $defaultSettings = [
                                            "time_format" => "24",
                                            "timezone" => "UTC",
                                        ];
                                        $settings = array_merge($defaultSettings, $settings);

                                        $timezone = $settings["timezone"];
                                        $timeFormat = $settings["time_format"];

                                        $timeslot = Carbon::parse($appointment->timeslot)->setTimezone($timezone);
                                        $formattedTime = $timeFormat === "12" ? $timeslot->format("g:i A") : $timeslot->format("H:i");
                                    @endphp

                                    {{ $formattedTime }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 capitalize"
                                >
                                    {{ $appointment->status }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 capitalize"
                                >
                                    {{ $appointment->status }}
                                </td>
                                <td
                                    class="border border-gray-300 px-6 py-4 capitalize"
                                >
                                    {{ $appointment->status }}
                                </td>
                                <td class="border-b border-gray-300 px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <a
                                            href="{{ route("dashboard.appointments.edit", $appointment->id) }}"
                                            class="focus:ring-opacity-50 flex rounded-md bg-blue-600 px-4 py-2 text-white transition duration-200 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route("dashboard.appointments.destroy", $appointment->id) }}"
                                            onsubmit="return confirm('Are you sure you want to cancel this appointment?');"
                                            style="display: inline"
                                        >
                                            @csrf
                                            @method("DELETE")
                                            <button
                                                type="submit"
                                                class="focus:ring-opacity-50 flex items-center rounded-md bg-red-600 px-4 py-2 text-white transition duration-200 hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:outline-none"
                                            >
                                                Cancel
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
