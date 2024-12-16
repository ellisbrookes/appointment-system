@extends('partials.dashboard.layout')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-xl p-6">
            <h2 class="text-3xl font-semibold mb-6">Review your appointment</h2>

            <form action="{{ route('dashboard.appointments.create.step.three.post') }}" method="POST">
                @csrf

                <!-- Service Selection -->
                 <div class="mb-8">
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full bg-white shadow-lg border border-gray-300 rounded-lg">
                            <tbody class="text-black">
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="border px-6 py-4">Service</td>
                                    <td class="border px-6 py-4">{{ $appointment['service'] ?? '' }}</td>
                                </tr>
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="border px-6 py-4">Date</td>
                                    <td class="border px-6 py-4">{{ $appointment['date'] ?? '' }}</td>
                                </tr>
                                <tr class="hover:bg-gray-100 transition duration-200">
                                    <td class="border px-6 py-4">Time</td>
                                    <td class="border px-6 py-4">{{ $appointment['timeslot'] ?? '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 text-center">
                    <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
