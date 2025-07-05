@php
    use Carbon\Carbon;
@endphp

@extends('dashboard.layout')

@section('title', 'Company Calendar')

@section('content')
    <div class="container mx-auto px-6">
        <!-- Header Section -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ $company->name }} Calendar
                </h1>
                <p class="mt-1 text-gray-600 dark:text-gray-300">
                    View and manage company appointments
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard.appointments.create.step.one') }}"
                   class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-plus mr-2"></i>
                    New Appointment
                </a>
            </div>
        </div>

        <!-- Calendar Navigation -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard.company.calendar.index', ['year' => $currentDate->copy()->subMonth()->year, 'month' => $currentDate->copy()->subMonth()->month]) }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-chevron-left mr-1"></i>
                    Previous
                </a>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ $currentDate->format('F Y') }}
                </h2>
                <a href="{{ route('dashboard.company.calendar.index', ['year' => $currentDate->copy()->addMonth()->year, 'month' => $currentDate->copy()->addMonth()->month]) }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    Next
                    <i class="fas fa-chevron-right ml-1"></i>
                </a>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard.company.calendar.index') }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-home mr-1"></i>
                    Today
                </a>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <!-- Calendar Header -->
            <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-700">
                <div class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Mon</div>
                <div class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Tue</div>
                <div class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Wed</div>
                <div class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Thu</div>
                <div class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Fri</div>
                <div class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Sat</div>
                <div class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Sun</div>
            </div>

            <!-- Calendar Body -->
            <div class="grid grid-cols-7">
                @foreach ($calendarData as $week)
                    @foreach ($week as $day)
                        <div class="relative min-h-[120px] border-b border-r border-gray-200 p-2 dark:border-gray-700 
                                   {{ $day['isCurrentMonth'] ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-900' }}
                                   {{ $day['isToday'] ? 'ring-2 ring-blue-500' : '' }}
                                   hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            
                            <!-- Date Number -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium 
                                           {{ $day['isCurrentMonth'] ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-600' }}
                                           {{ $day['isToday'] ? 'text-blue-600 dark:text-blue-400 font-bold' : '' }}">
                                    {{ $day['date']->format('j') }}
                                </span>
                                
                                @if ($day['appointmentCount'] > 0)
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ $day['appointmentCount'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Appointments -->
                            @if ($day['appointmentCount'] > 0)
                                <div class="space-y-1">
                                    @foreach ($day['appointments']->take(3) as $appointment)
                                        <div class="rounded px-2 py-1 text-xs 
                                                   {{ $appointment->status === 'open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                   {{ $appointment->status === 'closed' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                                   {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                            <div class="truncate">
                                                {{ Carbon::parse($appointment->timeslot)->format('H:i') }} - {{ $appointment->service }}
                                            </div>
                                            @if ($appointment->user)
                                                <div class="truncate text-xs opacity-75">
                                                    {{ $appointment->user->name }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                    
                                    @if ($day['appointmentCount'] > 3)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            +{{ $day['appointmentCount'] - 3 }} more
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Click overlay to view day -->
                            <a href="{{ route('dashboard.company.calendar.date', $day['date']->format('Y-m-d')) }}"
                               class="absolute inset-0 rounded hover:bg-black hover:bg-opacity-5"></a>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 flex items-center justify-center space-x-6 text-sm">
            <div class="flex items-center">
                <div class="mr-2 h-3 w-3 rounded bg-green-100 dark:bg-green-900"></div>
                <span class="text-gray-600 dark:text-gray-400">Open</span>
            </div>
            <div class="flex items-center">
                <div class="mr-2 h-3 w-3 rounded bg-gray-100 dark:bg-gray-700"></div>
                <span class="text-gray-600 dark:text-gray-400">Closed</span>
            </div>
            <div class="flex items-center">
                <div class="mr-2 h-3 w-3 rounded bg-red-100 dark:bg-red-900"></div>
                <span class="text-gray-600 dark:text-gray-400">Cancelled</span>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Appointments</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $appointments->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Open Appointments</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $appointments->where('status', 'open')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cancelled</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $appointments->where('status', 'cancelled')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
