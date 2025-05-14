@extends('partials.dashboard.layout')

@section('content')
<div class="container mx-auto p-8">
    <div class="w-full bg-white shadow-md rounded-lg overflow-hidden p-6">
        <h2 class="text-3xl font-bold text-gray-700 mb-6">Edit Appointment</h2>

        <!-- Edit Appointment Form -->
        <form action="{{ route('dashboard.appointments.update', $appointment->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Service Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Service</label>
                <input type="text" name="service" value="{{ old('service', $appointment->service) }}" class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Date Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Date</label>
                <input type="date" name="date" value="{{ old('date', $appointment->date) }}" class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- Timeslot Field -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Timeslot</label>
                <input type="time" name="timeslot" value="{{ old('timeslot', $appointment->timeslot) }}" class="w-full border rounded-lg px-4 py-2">
            </div>

            <!-- User Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Select User</label>
                <select name="user_id" class="w-full border rounded-lg px-4 py-2">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $user->id == old('user_id', $appointment->user_id) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Update Appointment
            </button>
        </form>
    </div>
</div>
@endsection
