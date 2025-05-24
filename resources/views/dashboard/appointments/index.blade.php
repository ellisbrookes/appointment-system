@php use Carbon\Carbon; @endphp

@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-4xl font-bold text-center text-gray-700 dark:text-white">Appointments List</h2>
            <a href="{{ route('dashboard.appointments.create.step.one') }}"
               class="bg-green-600 text-white dark:text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200">
                Book Appointment
            </a>
        </div>

        @if ($appointments->isEmpty())
            <p class="text-center text-gray-500 dark:text-white py-4">No booked appointments</p>
        @else
            <div class="overflow-x-auto">
                <table
                    class="w-full border border-gray-400 border-collapse text-md table-auto md:table-fixed text-center">
                    <thead>
                    <tr class="bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-white">
                        <th class="px-6 py-3 border border-gray-300 text-center">Service</th>
                        <th class="px-6 py-3 border border-gray-300 text-center">Date</th>
                        <th class="px-6 py-3 border border-gray-300 text-center">User</th>
                        <th class="px-6 py-3 border border-gray-300 text-center">Timeslot</th>
                        <th class="px-6 py-3 border border-gray-300 text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($appointments as $appointment)
                        <tr class="even:bg-gray-50 dark:even:bg-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-900">
                            <td class="px-6 py-4 border border-gray-300">{{ $appointment->service }}</td>
                            <td class="px-6 py-4 border border-gray-300">
                                {{ Carbon::parse($appointment->date)->format('jS F Y') }}
                            </td>
                            <td class="px-6 py-4 border border-gray-300">
                                {{ $appointment->user->name ?? 'Guest' }}
                            </td>
                            <td class="px-6 py-4 border border-gray-300">
                                {{ Carbon::parse($appointment->timeslot)->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 border-b border-gray-300">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('dashboard.appointments.edit', $appointment->id) }}"
                                       class="bg-blue-600 text-white py-2 px-4 flex align-center rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                                        Edit
                                    </a>

                                    <form method="POST"
                                          action="{{ route('dashboard.appointments.destroy', $appointment->id) }}"
                                          onsubmit="return confirm('Are you sure you want to cancel this appointment?');"
                                          style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 text-white py-2 px-4 flex items-center rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 transition duration-200">
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
