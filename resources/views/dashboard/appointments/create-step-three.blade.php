@php use Carbon\Carbon; @endphp
@extends('partials.dashboard.layout')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-2xl p-6">
            <h2 class="text-3xl font-semibold mb-4 dark:text-gray-300">Review Your Appointment</h2>

            <form action="{{ route('dashboard.appointments.create.step.three.post') }}" method="POST">
                @csrf

                <!-- Appointment Information -->
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200 rounded-lg text-sm">
                        <thead>
                        <tr class="bg-gray-800 text-white uppercase text-left">
                            <th class="px-6 py-3">Information</th>
                            <th class="px-6 py-3">Details</th>
                        </tr>
                        </thead>
                        <tbody class="text-gray-700 dark:text-gray-300">
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="border px-6 py-4 font-medium">Service</td>
                            <td class="border px-6 py-4">{{ $appointment['service'] ?? '' }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="border px-6 py-4 font-medium">Date</td>
                            <td class="border px-6 py-4">
                                {{ Carbon::parse($appointment['date'])->format('d/m/Y') ?? '' }}
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="border px-6 py-4 font-medium">Time</td>
                            <td class="border px-6 py-4">{{ $appointment['timeslot'] ?? '' }}</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="border px-6 py-4 font-medium">User</td>
                            <td class="border px-6 py-4">
                                @if (Auth::user()->admin)
                                    <select name="user_id" id="user_id"
                                            class="block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="" disabled selected>Select a user</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    {{ Auth::user()->name }}
                                    <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}">
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Previous and Submit Buttons -->
                <div class="flex justify-between mt-4">
                    <a href="{{ route('dashboard.appointments.create.step.two') }}"
                       class="bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition duration-200">Previous
                        Step</a>

                    <button type="submit"
                            class="bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
