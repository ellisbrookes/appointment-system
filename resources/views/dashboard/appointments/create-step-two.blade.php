@extends('partials.dashboard.layout')

@section('content')
<div class="container min-h-screen">
    <div class="w-full p-6 flex flex-col">
        <!-- Header with Month Display -->
        <h1 class="text-4xl font-bold text-gray-800">Select an appointment date and time</h1>

        <form action="{{ route('dashboard.appointments.create.step.two.post') }}" method="POST" class="flex flex-col space-y-4 w-full">
            @csrf

            @if ($errors->any())
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Calendar Grid -->
            <div class="border-2 border-gray-500 rounded-md p-6">
                <!-- Header with Month Display -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-4xl font-bold">{{ $currentDate->format('F Y') }}</h2>

                    <!-- Navigation -->
                    <div>
                        <a href="{{ route('dashboard.appointments.create.step.two', ['date' => $currentDate->copy()->subMonth()->format('d-m-Y')]) }}" class="px-6 py-2 text-white bg-gray-600 rounded-md">Previous</a>

                        <a href="{{ route('dashboard.appointments.create.step.two', ['date' => $currentDate->copy()->addMonth()->format('d-m-Y')]) }}" class="px-6 py-2 text-white bg-blue-600 rounded-md">Next</a>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-4 text-center">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="font-bold tracking-wider">{{ $day }}</div>
                    @endforeach

                    @for($i = 0; $i < $startDayOfWeek; $i++)
                        <div></div>
                    @endfor

                    <input type="hidden" id="date" name="date" value="{{ $selectedDay }}">

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        <button
                            type="button"
                            class="p-4 rounded-md text-md text-white transition-all duration-250 ease-in-out cursor-pointer hover:bg-blue-700 focus:bg-blue-700 focus:font-bold focus:shadow-lg focus:transform focus:scale-105
                                {{ $day == $currentDate->day ? 'cursor-pointer font-bold' : '' }}
                                {{ isset($selectedDay) && $selectedDay == $day ? 'bg-blue-700 shadow-lg transform scale-105 font-bold' : 'bg-gray-800 hover:bg-blue-700' }}"
                            onclick="updateDateField('{{ $currentDate->copy()->day($day)->format('d-m-Y') }}')"
                        >
                            <span>{{ $day }}</span>
                        </button>
                    @endfor
                </div>
            </div>

            <!-- Display Time Slots if a day is selected -->
            @isset($selectedDay)
                <div class="border-2 border-gray-500 rounded-md p-6">
                    <h2 class="text-xl font-bold text-gray-800">
                        Timeslots for <span id="timeslot-date">{{ $currentDate->format('jS F Y') }}</span>
                    </h2>

                    <input type="hidden" id="timeslot" name="timeslot">

                    <div class="grid grid-cols-4 gap-4 mt-6">
                        @foreach($timeslots as $timeslot)
                            <button
                                type="button"
                                name="timeslot"
                                class="bg-gray-800 p-4 rounded-md text-md text-white transition-all duration-250 ease-in-out cursor-pointer hover:bg-blue-700 focus:bg-blue-700 focus:font-bold focus:shadow-lg focus:transform focus:scale-105"
                                onclick="updateTimeslotField('{{ $timeslot }}')"
                            >
                                {{ $timeslot }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Labels (Booked, Blocked, Unavailable) -->
                    <div class="mt-8 w-full flex space-x-4 justify-center">
                        <div class="flex items-center space-x-2 bg-green-100 text-green-600 rounded-md px-4 py-2 cursor-pointer hover:bg-green-200">
                            <span class="font-bold">Booked</span>
                        </div>

                        <div class="flex items-center space-x-2 bg-yellow-100 text-yellow-600 rounded-md px-4 py-2 cursor-pointer hover:bg-yellow-200">
                            <span class="font-bold">Limited</span>
                        </div>

                        <div class="flex items-center space-x-2 bg-red-100 text-red-600 rounded-md px-4 py-2 cursor-pointer hover:bg-red-200">
                            <span class="font-bold">Unavailable</span>
                        </div>
                    </div>
                </div>
            @endisset

            <!-- Step Navigation Buttons -->
            <div class="text-center">
                <a href="{{ route('dashboard.appointments.create.step.one') }}" class="px-6 py-2 text-white bg-gray-600 rounded-md">Previous Step</a>
                <a href="#" class="px-6 py-2 text-white bg-blue-600 rounded-md">Next Step</a>
            </div>
        </form>
    </div>
</div>

<script>
    function updateDateField(value) {
        document.getElementById('date').value = value;
    }

    function updateTimeslotField(value) {
        document.getElementById('timeslot').value = value;
    }
</script>
@endsection
