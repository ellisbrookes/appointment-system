@extends('partials.dashboard.layout')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-3xl p-8 bg-white shadow-xl rounded-lg">
            <h2 class="text-3xl font-semibold text-center text-gray-800 mb-8">Review Your Appointment</h2>

            <form action="{{ route('dashboard.appointments.create.step.three.post') }}" method="POST">
                @csrf

                <!-- Appointment Information -->
                 <div class="mb-8">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full bg-white shadow-md border border-gray-300 rounded-lg">
                            <thead>
                                <tr class="bg-gray-200 text-gray-600">
                                    <th class="px-6 py-4 text-left">Information</th>
                                    <th class="px-6 py-4 text-left">Details</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="border px-6 py-4 font-medium">Service</td>
                                    <td class="border px-6 py-4">{{ $appointment['service'] ?? '' }}</td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="border px-6 py-4 font-medium">Date</td>
                                    <td class="border px-6 py-4">
                                        {{ \Carbon\Carbon::parse($appointment['date'])->format('d/m/Y') ?? '' }}
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="border px-6 py-4 font-medium">Time</td>
                                    <td class="border px-6 py-4">{{ $appointment['timeslot'] ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Previous and Submit Buttons -->
                <div class="flex justify-between mt-6">
                    <a href="{{ route('dashboard.appointments.create.step.two') }}" 
                       class="bg-gray-400 text-white py-3 px-6 rounded-md hover:bg-gray-500 transition duration-200">
                        Previous Step
                    </a>
                    <button type="submit" 
                            class="bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                        Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
