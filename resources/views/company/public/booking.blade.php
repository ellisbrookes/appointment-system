@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold text-center mt-5">Book an Appointment</h1>
    <h2 class="text-2xl text-center text-gray-700 mb-2">{{ $company->name }}</h2>
    <p class="text-center text-gray-600 mt-2 mb-5">{{ $company->description }}</p>
    
    @if(session('success'))
        <div class="bg-green-100 text-green-800 py-3 px-5 rounded-lg mb-6 max-w-md mx-auto">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-md mx-auto">
        <form action="{{ route('company.public.booking.submit', $company->url) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="appointment_date" class="block text-sm font-medium text-gray-700">Preferred Date</label>
                <input type="date" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('appointment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="appointment_time" class="block text-sm font-medium text-gray-700">Preferred Time</label>
                <select id="appointment_time" name="appointment_time" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Select a time</option>
                    @foreach($availableTimeSlots as $time => $display)
                        <option value="{{ $time }}" {{ old('appointment_time') == $time ? 'selected' : '' }}>{{ $display }}</option>
                    @endforeach
                </select>
                @error('appointment_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="service" class="block text-sm font-medium text-gray-700">Service (Optional)</label>
                <input type="text" id="service" name="service" value="{{ old('service') }}"
                       placeholder="e.g., Consultation, Cleaning, etc."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('service')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message (Optional)</label>
                <textarea id="message" name="message" rows="3" placeholder="Tell us about your appointment needs..."
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                    Book Appointment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
