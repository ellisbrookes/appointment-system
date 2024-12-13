<!-- Header with Month Display -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-4xl font-bold text-gray-800">Create an appointment</h1>
</div>

<!-- Calendar Grid -->
<div class="border-2 border-gray-500 rounded-md p-6">
    <!-- Header with Month Display -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-4xl font-bold">{{ $currentDate->format('F Y') }}</h1>
    </div>

    <div class="grid grid-cols-7 gap-4 text-center">
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="font-bold tracking-wider">{{ $day }}</div>
        @endforeach

        @for($i = 0; $i < $firstDayOfMonth; $i++)
            <div></div>
        @endfor

        @for($day = 1; $day <= $daysInMonth; $day++)
            <form action="{{ route('appointments.view') }}" method="GET">
                <input type="hidden" name="selected_day" value="{{ $day }}">

                <!-- Button that submits the form -->
                <button
                    type="submit"
                    class="w-full h-full p-4 rounded-md text-md text-white transition-all duration-250 ease-in-out cursor-pointer
                        {{ $day == $currentDate->day ? 'cursor-pointer font-bold' : '' }}
                        {{ isset($selectedDay) && $selectedDay == $day ? 'bg-blue-700 shadow-lg transform scale-105 font-bold' : 'bg-gray-800 hover:bg-blue-700' }}"
                >
                    <span>{{ $day }}</span>
                </button>
            </form>
        @endfor
    </div>
</div>

<!-- Display Time Slots if a day is selected -->
@isset($selectedDay)
<div class="mt-4 border-2 border-gray-500 rounded-md p-6">
    <h2 class="text-xl font-bold text-gray-800">Time Slots for {{ $fullDate }}</h2>

    <div class="grid grid-cols-4 gap-4 mt-6">
        @foreach(['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'] as $time)
            <div class="py-2 px-4 bg-gray-800 text-white rounded-md shadow hover:bg-blue-700 cursor-pointer transition-all duration-250 ease-in-out">
                {{ $time }}
            </div>
        @endforeach
    </div>

    <!-- Labels (Booked, Blocked, Unavailable) -->
    <div class="mt-8 w-full flex space-x-4 justify-center">
        <div class="flex items-center space-x-2 bg-green-100 text-green-600 rounded-md px-4 py-2 cursor-pointer hover:bg-green-200">
            <span class="font-bold">Booked</span>
        </div>

        <div class="flex items-center space-x-2 bg-yellow-100 text-yellow-600 rounded-md px-4 py-2 cursor-pointer hover:bg-yellow-200">
            <span class="font-bold">Blocked</span>
        </div>

        <div class="flex items-center space-x-2 bg-red-100 text-red-600 rounded-md px-4 py-2 cursor-pointer hover:bg-red-200">
            <span class="font-bold">Unavailable</span>
        </div>
    </div>
</div>
@endisset
