@php
    use Carbon\Carbon;

    $prev = Carbon::create($year, $month)->subMonth();
    $next = Carbon::create($year, $month)->addMonth();

    $currentMonthTitle = Carbon::create($year, $month)->format("F Y");
    $timeslotDateTitle = Carbon::parse($currentDate)->format("jS F Y");

    $dayCounter = 1;
    $calendarCells = ceil(($startDayOfWeek + $daysInMonth) / 7) * 7;
@endphp

@extends("dashboard.layout")

@section("content")
    <div class="mx-auto flex w-full max-w-5xl flex-col justify-center p-6">
        <h2 class="mb-4 text-3xl font-semibold dark:text-gray-300">
            Select an appointment date and time
        </h2>

        <form
            action="{{ route("dashboard.appointments.create.step.two.post") }}"
            method="POST"
            class="flex w-full flex-col space-y-4"
        >
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
            <div class="rounded-md border p-6">
                <!-- Header with Month Display -->
                <div class="mb-6 flex items-center justify-between">
                    <h2
                        class="text-4xl font-bold text-gray-800 dark:text-white"
                    >
                        {{ $currentMonthTitle }}
                    </h2>

                    <!-- Navigation -->
                    <div>
                        <x-shared.primary-button
                            :href="route('dashboard.appointments.create.step.two', ['month' => $prev->month, 'year' => $prev->year])"
                        >
                            {{ __("Prev") }}
                        </x-shared.primary-button>

                        <x-shared.primary-button
                            :href="route('dashboard.appointments.create.step.two', ['month' => $next->month, 'year' => $next->year])"
                        >
                            {{ __("Next") }}
                        </x-shared.primary-button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-4 text-center">
                    @foreach (["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"] as $day)
                        <div
                            class="font-bold tracking-wider text-gray-800 dark:text-white"
                        >
                            {{ $day }}
                        </div>
                    @endforeach

                    <input
                        type="hidden"
                        id="date"
                        name="date"
                        value="{{ $currentDate }}"
                    />

                    @for ($i = 0; $i < $calendarCells; $i++)
                        @if ($i < $startDayOfWeek || $dayCounter > $daysInMonth)
                            <div></div>
                        @else
                            @php
                                $currentLoopDate = Carbon::create($year, $month, $dayCounter)->toDateString();
                                $isToday = $currentLoopDate === $currentDate;
                            @endphp

                            <div
                                class="{{ $isToday ? "bg-blue-300 font-bold" : "hover:bg-gray-200 dark:hover:bg-gray-800" }} flex h-10 cursor-pointer items-center justify-center rounded"
                                onclick="selectDate('{{ $currentLoopDate }}', this)"
                            >
                                {{ $dayCounter++ }}
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            <!-- Display Time Slots if a day is selected -->
            @isset($currentDate)
                <div class="rounded-md border p-6">
                    <h2 class="text-xl font-bold">
                        Timeslots for
                        <span id="timeslot-date">
                            {{ $timeslotDateTitle }}
                        </span>
                    </h2>

                    <input
                        type="hidden"
                        id="timeslot"
                        name="timeslot"
                        value="{{ isset($timeslots[0]) ? Carbon::parse($timeslots[0])->format("H:i") : "" }}"
                    />

                    <div class="mt-6 grid grid-cols-4 gap-4">
                        @foreach ($timeslots as $timeslot)
                            @php
                                if (! ($timeslot instanceof Carbon)) {
                                    $timeslot = Carbon::parse($timeslot);
                                }
                            @endphp

                            <div
                                class="text-md flex cursor-pointer justify-center rounded-md py-3 hover:bg-gray-200 dark:hover:bg-gray-800"
                                onclick="selectTimeslot('{{ $timeslot->format("H:i") }}', this)"
                            >
                                {{ $timeslot->format("H:i") }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Labels (Booked, Blocked, Unavailable) -->
                    <div class="mt-8 flex w-full justify-center space-x-4">
                        <div
                            class="flex cursor-pointer items-center space-x-2 rounded-md bg-green-100 px-4 py-2 text-green-600 hover:bg-green-200"
                        >
                            <span class="font-bold">Booked</span>
                        </div>

                        <div
                            class="flex cursor-pointer items-center space-x-2 rounded-md bg-yellow-100 px-4 py-2 text-yellow-600 hover:bg-yellow-200"
                        >
                            <span class="font-bold">Limited</span>
                        </div>

                        <div
                            class="flex cursor-pointer items-center space-x-2 rounded-md bg-red-100 px-4 py-2 text-red-600 hover:bg-red-200"
                        >
                            <span class="font-bold">Unavailable</span>
                        </div>
                    </div>
                </div>
            @endisset

            <!-- Step Navigation Buttons -->
            <div class="flex justify-center space-x-2 text-center">
                <x-shared.primary-button
                    :href="route('dashboard.appointments.create.step.one')"
                    class="bg-gray-600 hover:bg-gray-700 focus:ring-gray-500"
                >
                    {{ __("Previous Step") }}
                </x-shared.primary-button>

                <x-shared.primary-button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500"
                >
                    {{ __("Next Step") }}
                </x-shared.primary-button>
            </div>
        </form>
    </div>

    <script>
        let selectedCell = document.querySelector('.bg-blue-300');

        function selectDate(date, cell) {
            if (
                selectedCell &&
                !selectedCell.classList.contains('bg-blue-300')
            ) {
                selectedCell.classList.remove(
                    'bg-blue-500',
                    'text-white',
                    'font-bold',
                );
                selectedCell.classList.add(
                    'hover:bg-gray-200',
                    'dark:hover:bg-gray-800',
                );
            }

            if (!cell.classList.contains('bg-blue-300')) {
                selectedCell = cell;
                cell.classList.remove(
                    'hover:bg-gray-200',
                    'dark:hover:bg-gray-800',
                );
                cell.classList.add('bg-blue-500', 'text-white', 'font-bold');
            }

            document.getElementById('date').value = date;
        }

        let selectedTimeslot = document.querySelector('.bg-blue-300');

        function selectTimeslot(timeslot, cell) {
            if (
                selectedTimeslot &&
                !selectedTimeslot.classList.contains('bg-blue-300')
            ) {
                selectedTimeslot.classList.remove(
                    'bg-blue-500',
                    'text-white',
                    'font-bold',
                );
                selectedTimeslot.classList.add(
                    'hover:bg-gray-200',
                    'dark:hover:bg-gray-800',
                );
            }

            if (!cell.classList.contains('bg-blue-300')) {
                selectedTimeslot = cell;
                cell.classList.remove(
                    'hover:bg-gray-200',
                    'dark:hover:bg-gray-800',
                );
                cell.classList.add('bg-blue-500', 'text-white', 'font-bold');
            }

            document.getElementById('timeslot').value = timeslot;
        }
    </script>
@endsection
