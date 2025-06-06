@extends('dashboard.layout')

@section('content')
    <div class="flex flex-col justify-center mx-auto w-full max-w-2xl p-6">
        <h2 class="text-3xl font-semibold mb-4 dark:text-gray-200">Choose a Service</h2>

        <form action="{{ route('dashboard.appointments.create.step.one.post') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Service Selection -->
            <div class="mt-4">
                <label for="service" class="block text-gray-800 dark:text-white text-lg">Choose a Service:</label>
                <select name="service" id="service"
                        class="w-full p-3 mt-2 border border-gray-300 dark:border-gray-200 rounded-md focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:text-white dark:bg-gray-800"
                        value="{{ $appointment->service ?? '' }}">
                    <option value="" disabled selected>Select a service</option>
                    <option value="hair" class=>Hair</option>
                    <option value="nails">Nails</option>
                    <option value="wax">Wax</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="mt-4 text-center">
                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">
                    Next Step
                </button>
            </div>
        </form>
    </div>
@endsection
