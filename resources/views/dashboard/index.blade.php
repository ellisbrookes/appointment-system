@extends("dashboard.layout")

@section("content")
<div class="mx-auto flex w-full max-w-7xl flex-col items-center">
    <h1 class="mb-2 text-4xl font-bold">
        Welcome, {{ auth()->user()->name }}
    </h1>
    <p class="font-semibold mb-6 text-lg">
        Here you will find all the information you need
    </p>

    <!-- Appointment Tiles -->
    <div class="mb-8 grid w-full max-w-4xl grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-lg border p-6 shadow">
            <h2 class="text-xl font-semibold">Open</h2>
            <p class="mt-2 text-3xl font-bold text-green-600">
                {{ $appointmentsCount ?? 0 }}
            </p>
            <p class="text-gray-600">currently open appointments</p>
        </div>
        <div class="rounded-lg border p-6 shadow">
            <h2 class="text-xl font-semibold">Cancelled</h2>
            <p class="mt-2 text-3xl font-bold text-yellow-600">
                {{ $cancelledCount ?? 0 }}
            </p>
            <p class="text-gray-600">cancelled appointments</p>
        </div>
        <div class="rounded-lg border p-6 shadow">
            <h2 class="text-xl font-semibold">Closed</h2>
            <p class="mt-2 text-3xl font-bold text-red-600">
                {{ $closedCount ?? 0 }}
            </p>
            <p class="text-gray-600">closed appointments</p>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="w-full max-w-4xl rounded-lg border p-6 shadow">
        <h2 class="mb-4 text-2xl font-semibold">Activity Log</h2>
        <ul class="space-y-3">
            @forelse($recentAppointments as $appt)
                <li class="text-gray-800 dark:text-gray-200">
                    @if($appt->status === 'open')
                        You booked an appointment on
                    @elseif($appt->status === 'cancelled')
                        You cancelled an appointment on
                    @else
                        Appointment status: {{ ucfirst($appt->status) }} on
                    @endif
                    <span class="font-semibold text-blue-600">
                        {{ \Carbon\Carbon::parse($appt->created_at)->format('jS F Y \a\t g:i A') }}
                    </span>
                </li>
            @empty
                <li class="text-gray-500">No recent activity.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
