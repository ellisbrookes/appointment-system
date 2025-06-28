@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold text-center mt-5">{{ $company->name }} Calendar</h1>
    <p class="text-center text-gray-600 mt-2 mb-5">View our availability and book an appointment today!</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 py-3 px-5 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-center items-start md:space-x-8">
        <!-- Upcoming Appointments List -->
        <div class="w-full md:w-1/2">
            <h2 class="text-2xl font-semibold mb-4">Upcoming Appointments</h2>
            @if($upcomingAppointments->isEmpty())
                <p class="text-gray-500">No upcoming appointments. Check back soon!</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach($upcomingAppointments as $appointment)
                        <li class="mb-2">
                            <strong>{{ \Carbon\Carbon::parse($appointment->date)->format('l, F j, Y') }} at {{ $appointment->timeslot }}</strong>
                            <span>with {{ $appointment->user->name }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Book Appointment Button -->
        <div class="w-full md:w-1/2 mt-6 md:mt-0">
            <h2 class="text-2xl font-semibold mb-4">Book an Appointment</h2>
            <p class="mb-4">Interested in booking an appointment with us? Use our simple booking form and we'll get back to you soon.</p>
            <a href="{{ route('company.public.booking', $company->url) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                Book Now
            </a>
        </div>
    </div>
</div>
@endsection
