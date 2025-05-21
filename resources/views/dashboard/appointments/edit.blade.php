@extends('partials.dashboard.layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto space-y-6">
        <div>
            <h2 class="font-semibold text-2xl text-black dark:text-white leading-tight">
                Edit Appointment
            </h2>
        </div>

        <div class="p-6 sm:p-8 bg-white border border-gray-400 dark:bg-gray-900 sm:rounded-lg shadow">
            <form action="{{ route('dashboard.appointments.update', $appointment->id) }}" method="POST" class="max-w-xl mx-auto">
                @csrf
                @method('PUT')

                <!-- Service Field -->
                <div class="mb-5">
                    <label for="service" class="block font-semibold dark:text-white mb-1">Service</label>
                    <input
                        type="text"
                        id="service"
                        name="service"
                        value="{{ old('service', $appointment->service) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    @error('service')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Field -->
                <div class="mb-5">
                    <label for="date" class="block font-semibold dark:text-white mb-1">Date</label>
                    <input
                        type="date"
                        id="date"
                        name="date"
                        value="{{ old('date', $appointment->date) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timeslot Field -->
                <div class="mb-5">
                    <label for="timeslot" class="block font-semibold dark:text-white mb-1">Timeslot</label>
                    <input
                        type="time"
                        id="timeslot"
                        name="timeslot"
                        value="{{ old('timeslot', $appointment->timeslot) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    @error('timeslot')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- User Dropdown -->
                <div class="mb-6">
                    <label for="user_id" class="block font-semibold dark:text-white mb-1">Select User</label>
                    <select
                        id="user_id"
                        name="user_id"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == old('user_id', $appointment->user_id) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition duration-150 ease-in-out"
                >
                    Update Appointment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
