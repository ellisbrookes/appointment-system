@extends("dashboard.layout")

@section("content")
    <div class="mx-auto flex w-full max-w-7xl flex-col items-center">
        <h1 class="mb-2 text-4xl font-bold">
            Welcome, {{ auth()->user()->name }}
        </h1>
        <p class="mb-6 text-lg font-semibold">
            Here you will find all the information you need
        </p>

        <!-- Appointment Tiles -->
        <div
            class="mb-8 grid w-full max-w-4xl grid-cols-1 gap-6 md:grid-cols-3"
        >
            <a
                href="{{ route("dashboard.appointments.index", ["status" => "open"]) }}"
                class="rounded-lg border p-6 shadow transition hover:bg-gray-200 dark:hover:bg-gray-600"
            >
                <h2 class="text-xl font-semibold">Open</h2>
                <p class="mt-2 text-3xl font-bold text-green-600">
                    {{ $appointmentsCount ?? 0 }}
                </p>
                <p>open appointments</p>
            </a>

            <a
                href="{{ route("dashboard.appointments.index", ["status" => "closed"]) }}"
                class="rounded-lg border p-6 shadow transition hover:bg-gray-200 dark:hover:bg-gray-600"
            >
                <h2 class="text-xl font-semibold">Closed</h2>
                <p class="mt-2 text-3xl font-bold text-red-600">
                    {{ $closedCount ?? 0 }}
                </p>
                <p>closed appointments</p>
            </a>

            <a
                href="{{ route("dashboard.appointments.index", ["status" => "cancelled"]) }}"
                class="rounded-lg border p-6 shadow transition hover:bg-gray-200 dark:hover:bg-gray-600"
            >
                <h2 class="text-xl font-semibold">Cancelled</h2>
                <p class="mt-2 text-3xl font-bold text-yellow-600">
                    {{ $cancelledCount ?? 0 }}
                </p>
                <p>cancelled appointments</p>
            </a>
        </div>

        <!-- Activity Log -->
        <div class="w-full max-w-4xl rounded-lg border p-6 shadow">
            <h2 class="mb-4 text-2xl font-semibold">Activity Log</h2>
            <ul class="space-y-6">
                @forelse ($recentAppointments as $appt)
                    <li
                        class="flex items-start space-x-4 text-gray-800 dark:text-gray-200"
                    >
                        <!-- Status Dot -->
                        <span
                            class="{{ $appt->status === "open" ? "bg-green-500" : "" }} {{ $appt->status === "cancelled" ? "bg-yellow-400" : "" }} mt-1.5 inline-block h-3 w-3 flex-shrink-0 rounded-full"
                            aria-hidden="true"
                        ></span>

                        <div>
                            <p class="text-base font-medium">
                                @if ($appt->status === "open")
                                    You booked an appointment on
                                @elseif ($appt->status === "cancelled")
                                    You cancelled an appointment on
                                @else
                                    Appointment status:
                                    {{ ucfirst($appt->status) }} on
                                @endif
                                <span class="font-semibold text-blue-600">
                                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('jS F Y \a\t g:i A') }}
                                </span>
                            </p>
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                            >
                                @if ($appt->status === "cancelled" && $appt->cancelled_at)
                                    {{ \Carbon\Carbon::parse($appt->cancelled_at)->diffForHumans() }}
                                @else
                                    {{ \Carbon\Carbon::parse($appt->created_at)->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </li>
                @empty
                    <li class="text-gray-500">No recent activity.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
