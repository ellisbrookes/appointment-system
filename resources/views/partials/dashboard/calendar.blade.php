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
      <form method="GET" action="{{ route('appointments.view') }}" class="w-full h-full">
        <input type="hidden" name="selected_day" value="{{ $day }}">
        <div class="relative p-4 h-auto rounded-lg flex items-center justify-center text-lg font-medium 
                    {{ $day == $currentDate->day ? 'bg-blue-600 text-white shadow-lg border-4 border-blue-500 transform scale-105' : '' }}
                    {{ isset($selectedDay) && $selectedDay == $day ? 'bg-blue-600 text-white shadow-lg border-4 border-blue-500 transform scale-105' : 'bg-gray-100 text-gray-700 hover:bg-blue-100 hover:text-blue-600' }} 
                    transition-all duration-300 ease-in-out cursor-pointer">
          <button type="submit">{{ $day }}</button>
        </div>
      </form>
    @endfor
  </div>

  <!-- Labels (Booked, Blocked, Unavailable) at the Bottom -->
  <div class="mt-8 w-full flex justify-around">
    <div class="flex items-center space-x-2 bg-green-100 text-green-600 rounded-lg px-3 py-1 shadow-md cursor-pointer hover:bg-green-200">
      <span class="text-sm font-semibold">Booked</span>
    </div>
    <div class="flex items-center space-x-2 bg-yellow-100 text-yellow-600 rounded-lg px-3 py-1 shadow-md cursor-pointer hover:bg-yellow-200">
      <span class="text-sm font-semibold">Blocked</span>
    </div>
    <div class="flex items-center space-x-2 bg-red-100 text-red-600 rounded-lg px-3 py-1 shadow-md cursor-pointer hover:bg-red-200">
      <span class="text-sm font-semibold">Unavailable</span>
    </div>
  </div>

  <!-- Display Time Slots if a day is selected -->
  @isset($selectedDay)
    <div class="mt-6">
      <h2 class="text-xl font-semibold text-gray-800">Available Time Slots for {{ $selectedDay }}</h2>
      <div class="w-full text-sm text-gray-600 mt-4 space-y-2">
        @foreach(['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM'] as $time)
          <div class="py-2 px-4 bg-gray-100 rounded-md shadow hover:bg-blue-200 cursor-pointer transition-all duration-300 ease-in-out">
            {{ $time }}
          </div>
        @endforeach
      </div>
    </div>
  @endisset
</div>
