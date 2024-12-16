@extends('partials.dashboard.layout')

@section('content')
    <div class="container mx-auto p-8">
        <div class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-4xl font-bold text-center text-gray-700">Appointments List</h2>
                <a href="{{ route('dashboard.appointments.create.step.one') }}"
                   class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200">
                   Book Appointment
                </a>
            </div>

            @if ($appointments->isEmpty())
                <p class="text-center text-gray-500 py-4">No booked appointments</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 rounded-lg text-sm">
                        <thead>
                            <tr class="bg-gray-800 text-white uppercase text-left">
                                <th class="px-6 py-3">Service</th>
                                <th class="px-6 py-3">Date</th>
                                <th class="px-6 py-3">Timeslot</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appointment)
                                <tr class="even:bg-gray-50 hover:bg-gray-100">
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $appointment->service }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">{{ $appointment->timeslot }}</td>
                                    <td class="px-6 py-4 border-b border-gray-200 text-center">
                                        <a href="#"
                                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 mr-2">
                                           Edit
                                        </a>
                                        <button type="button"
                                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                                            Cancel
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
