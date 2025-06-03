@php
  use Carbon\Carbon;

  $prev = Carbon::create($year, $month)->subMonth();
  $next = Carbon::create($year, $month)->addMonth();

  $currentMonthTitle = Carbon::create($year, $month)->format('F Y');
  $timeslotDateTitle = Carbon::parse($currentDate)->format('jS F Y');

  $dayCounter = 1;
  $calendarCells = ceil(($startDayOfWeek + $daysInMonth) / 7) * 7;
@endphp

@extends('dashboard.layout')

@section('content')
<div class="flex flex-col justify-center mx-auto w-full max-w-5xl p-6">
  <h2 class="text-3xl font-semibold mb-4 dark:text-gray-300">Select an appointment date and time</h2>

  <form action="{{ route('dashboard.appointments.create.step.two.post') }}" method="POST" class="flex flex-col space-y-4 w-full">
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

    <!-- Calendar Grid -->
    <div class="border rounded-md p-6">
      <!-- Header with Month Display -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-4xl font-bold text-gray-800 dark:text-white">
          {{ $currentMonthTitle }}
        </h2>

        <!-- Navigation -->
        <div>
          <a href="{{ route('dashboard.appointments.create.step.two', ['month' => $prev->month, 'year' => $prev->year]) }}" class="py-3 px-4 text-white bg-gray-600 rounded-md">Prev</a>
          <a href="{{ route('dashboard.appointments.create.step.two', ['month' => $next->month, 'year' => $next->year]) }}" class="py-3 px-4 text-white bg-blue-600 rounded-md">Next</a>
        </div>
      </div>

      <div class="grid grid-cols-7 gap-4 text-center">
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
          <div class="font-bold tracking-wider text-gray-800 dark:text-white">{{ $day }}</div>
        @endforeach

        <input type="hidden" id="date" name="date" value="{{ $currentDate }}">

        @for ($i = 0; $i < $calendarCells; $i++)
          @if ($i < $startDayOfWeek || $dayCounter > $daysInMonth)
            <div></div>
          @else
            @php
              $currentLoopDate = Carbon::create($year, $month, $dayCounter)->toDateString();
              $isToday = $currentLoopDate === $currentDate;
            @endphp
            <div
              class="h-10 flex items-center justify-center rounded cursor-pointer
              {{ $isToday ? 'bg-blue-300 font-bold' : 'hover:bg-gray-200 dark:hover:bg-gray-800' }}"
              onclick="selectDate('{{ $currentLoopDate }}', this)">
              {{ $dayCounter++ }}
            </div>
          @endif
        @endfor
      </div>
    </div>

    <!-- Display Time Slots if a day is selected -->
    @isset($currentDate)
      <div class="border rounded-md p-6">
        <h2 class="text-xl font-bold">
          Timeslots for <span id="timeslot-date">{{ $timeslotDateTitle }}</span>
        </h2>

        <input type="hidden" id="timeslot" name="timeslot" value="{{ isset($timeslots[0]) ? (Carbon::parse($timeslots[0])->format('H:i')) : '' }}">

        <div class="grid grid-cols-4 gap-4 mt-6">
          @foreach ($timeslots as $timeslot)
            @php
              if (!($timeslot instanceof Carbon)) {
                $timeslot = Carbon::parse($timeslot);
              }
            @endphp
            <div
              class="flex justify-center py-3 rounded-md text-md cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-800"
              onclick="selectTimeslot('{{ $timeslot->format('H:i') }}', this)">
              {{ $timeslot->format('H:i') }}
            </div>
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
    <div class="text-center flex justify-center space-x-2">
      <a href="{{ route('dashboard.appointments.create.step.one') }}" class="bg-gray-600 text-white py-3 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 transition duration-200">Previous Step</a>

      <button type="submit" class="bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-200">Next Step</button>
    </div>
  </form>
</div>

<script>
  let selectedCell = document.querySelector('.bg-blue-300');

  function selectDate(date, cell) {
    if (selectedCell && !selectedCell.classList.contains('bg-blue-300')) {
      selectedCell.classList.remove('bg-blue-500', 'text-white', 'font-bold');
      selectedCell.classList.add('hover:bg-gray-200', 'dark:hover:bg-gray-800');
    }

    if (!cell.classList.contains('bg-blue-300')) {
      selectedCell = cell;
      cell.classList.remove('hover:bg-gray-200', 'dark:hover:bg-gray-800');
      cell.classList.add('bg-blue-500', 'text-white', 'font-bold');
    }

    document.getElementById('date').value = date;
  }

  let selectedTimeslot = document.querySelector('.bg-blue-300');

  function selectTimeslot(timeslot, cell) {
    if (selectedTimeslot && !selectedTimeslot.classList.contains('bg-blue-300')) {
      selectedTimeslot.classList.remove('bg-blue-500', 'text-white', 'font-bold');
      selectedTimeslot.classList.add('hover:bg-gray-200', 'dark:hover:bg-gray-800');
    }

    if (!cell.classList.contains('bg-blue-300')) {
      selectedTimeslot = cell;
      cell.classList.remove('hover:bg-gray-200', 'dark:hover:bg-gray-800');
      cell.classList.add('bg-blue-500', 'text-white', 'font-bold');
    }

    document.getElementById('timeslot').value = timeslot;
  }
</script>
@endsection
