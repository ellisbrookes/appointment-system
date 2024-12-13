@extends('partials.dashboard.layout')

@section('content')
    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        <!-- Step 1: Select a Service -->
        @if($step == 1)
            <h3>Select a service</h3>
            <select name="service" required>
                <option value="" disabled selected>Select a service</option>
                <option value="service1">Service 1</option>
                <option value="service2">Service 2</option>
            </select>
            <button type="submit" name="step" value="2">Next</button>
        @elseif($step == 2)
            <!-- Step 2: Calendar Selection -->
            <div class="bg-white p-8 rounded-lg shadow-xl w-full h-full flex flex-col justify-between">
                <!-- Header with Month Display -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-4xl font-semibold text-gray-800">{{ $currentDate->format('F Y') }}</h1>
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 gap-4 text-center">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="font-semibold text-gray-500 uppercase tracking-wider">{{ $day }}</div>
                    @endforeach

                    @for($i = 0; $i < $firstDayOfMonth; $i++)
                        <div></div>
                    @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        <div class="relative p-4 h-auto rounded-lg flex items-center justify-center text-lg font-medium
                                    {{ $day == $currentDate->day ? 'border-2 border-blue-600 bg-blue-100 text-blue-600 cursor-pointer' : '' }} 
                                    {{ isset($selectedDay) && $selectedDay == $day ? 'bg-blue-600 text-white shadow-lg transform scale-105' : 'bg-gray-100 text-gray-700 hover:bg-blue-100 hover:text-blue-600' }}
                                    transition-all duration-300 ease-in-out cursor-pointer">
                            <button type="submit" name="selected_day" value="{{ $day }}" class="absolute inset-0 w-full h-full"></button>
                            <span class="z-10">{{ $day }}</span>
                        </div>
                    @endfor
                </div>

                <!-- Hidden Input for Selected Day -->
                <input type="hidden" name="selected_day" value="{{ old('selected_day', $selectedDay) }}">

                <!-- Submit Button -->
                <button type="submit" name="step" value="3">Next</button>
            </div>
        @elseif($step == 3)
            <!-- Step 3: User Details -->
            <div>
                <h2>Step 3: Your Details</h2>
                <input type="text" name="user_name" placeholder="Your Name" required>
                <!-- Add other form fields as necessary -->

                <div class="flex justify-between mt-6">
                    <button type="submit" name="step" value="2">Previous</button>
                    <button type="submit" name="step" value="4">Submit</button>
                </div>
            </div>
        @elseif($step == 4)
            <!-- Step 4: Submit Form -->
            <h2>Review your appointment</h2>
            <!-- Show the details here (service, selected day, user name) -->
            <button type="submit">Confirm Appointment</button>
        @endif
    </form>
@endsection
