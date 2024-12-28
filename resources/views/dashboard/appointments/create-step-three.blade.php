@extends('partials.dashboard.layout')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-2xl p-6">
            <h2 class="text-3xl font-semibold mb-4">Review Your Appointment</h2>

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

                <!-- Previous and Submit Buttons -->
                <div class="flex justify-between mt-4">
                    <a href="{{ route('dashboard.appointments.create.step.two') }}" class="bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition duration-200">Previous Step</a>
                    <form action="{{ route('dashboard.appointments.submit')}}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                            Confirm
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
@endsection
