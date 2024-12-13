@extends('partials.dashboard.layout')

@section('content')
    <div class="container min-h-screen flex items-center justify-center">
        <div class="w-full max-w-xl p-6 bg-white shadow-lg rounded-lg">
            <h2 class="text-3xl font-semibold text-center mb-6">Choose a Service</h2>

            <form action="{{ route('appointments.create.step.one') }}" method="POST">
                @csrf

                <!-- Service Selection -->
                <div class="mt-6">
                    <label for="service" class="block text-gray-700 text-lg">Choose a Service:</label>
                    <select name="service" id="service" class="w-full p-3 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select a service</option>
                        <option value="1">Service 1</option>
                        <option value="2">Service 2</option>
                        <option value="3">Service 3</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 text-center">
                    <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Next Step
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
