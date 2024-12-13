@extends('partials.dashboard.layout')

@section('content')
  <div class="container min-h-screen flex items-center justify-center">
      <div class="w-full max-w-xl p-6 bg-white shadow-lg rounded-lg">
          <div class="text-center mb-6">
              <h2 class="text-3xl font-semibold">{{ $currentDate->format('F Y') }}</h2>
          </div>

          <!-- Calendar Grid -->
          <div class="grid grid-cols-7 gap-4 text-center">
              @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                  <div class="font-bold text-gray-700">{{ $day }}</div>
              @endforeach

              @for($i = 0; $i < $firstDayOfMonth; $i++)
                  <div></div>
              @endfor

              @for($day = 1; $day <= $daysInMonth; $day++)
                  <form action="{{ route('appointments.index') }}" method="GET">
                      <input type="hidden" name="selected_day" value="{{ $day }}">

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

        <!-- Step Navigation Buttons -->
        <div class="mt-6 text-center">
            <a href="{{ route('appointments.create.step.one.post') }}" class="btn btn-danger pull-right">Previous</a>
            <button type="submit" class="px-6 py-2 text-white bg-blue-600 rounded-md">Next</button>
        </div>
      </div>
  </div>
@endsection
