@php
    use Carbon\Carbon;
@endphp

@extends('dashboard.layout')

@section('title', 'Calendar - ' . $selectedDate->format('F j, Y'))

@section('content')
    <div class="container mx-auto px-6">
        <!-- Header Section -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    <i class="fas fa-calendar-day mr-2"></i>
                    {{ $selectedDate->format('l, F j, Y') }}
                </h1>
                <p class="mt-1 text-gray-600 dark:text-gray-300">
                    {{ $company->name }} - Appointments for this day
                </p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard.company.calendar.index') }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                    <i class="fas fa-calendar mr-2"></i>
                    Back to Calendar
                </a>
                <a href="{{ route('dashboard.appointments.create.step.one') }}"
                   class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-plus mr-2"></i>
                    New Appointment
                </a>
            </div>
        </div>

        <!-- Day Navigation -->
        <div class="mb-6 flex items-center justify-center space-x-4">
            <a href="{{ route('dashboard.company.calendar.date', $selectedDate->copy()->subDay()->format('Y-m-d')) }}"
               class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                <i class="fas fa-chevron-left mr-1"></i>
                Previous Day
            </a>
            
            <span class="text-lg font-medium text-gray-900 dark:text-white">
                {{ $selectedDate->format('F j, Y') }}
            </span>
            
            <a href="{{ route('dashboard.company.calendar.date', $selectedDate->copy()->addDay()->format('Y-m-d')) }}"
               class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                Next Day
                <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>

        <!-- Appointments List -->
        @if ($appointments->isEmpty())
            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <i class="fas fa-calendar-times text-xl text-gray-400"></i>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                    No appointments scheduled
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    There are no appointments scheduled for {{ $selectedDate->format('F j, Y') }}.
                </p>
                <a href="{{ route('dashboard.appointments.create.step.one') }}"
                   class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <i class="fas fa-plus mr-2"></i>
                    Schedule Appointment
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($appointments as $appointment)
                    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <!-- Time -->
                                    <div class="flex-shrink-0">
                                        <div class="rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                                            <div class="text-center">
                                                <div class="text-lg font-semibold text-blue-900 dark:text-blue-200">
                                                    {{ Carbon::parse($appointment->timeslot)->format('H:i') }}
                                                </div>
                                                <div class="text-xs text-blue-600 dark:text-blue-400">
                                                    {{ Carbon::parse($appointment->timeslot)->format('A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Appointment Details -->
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                            {{ $appointment->service }}
                                        </h3>
                                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                            @if ($appointment->user)
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $appointment->user->name }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-envelope mr-1"></i>
                                                    {{ $appointment->user->email }}
                                                </div>
                                            @else
                                                <div class="flex items-center">
                                                    <i class="fas fa-user mr-1"></i>
                                                    Guest User
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Status and Actions -->
                                <div class="flex items-center space-x-4">
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                                               {{ $appointment->status === 'open' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                               {{ $appointment->status === 'closed' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                               {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                        <i class="fas 
                                               {{ $appointment->status === 'open' ? 'fa-clock' : '' }}
                                               {{ $appointment->status === 'closed' ? 'fa-check' : '' }}
                                               {{ $appointment->status === 'cancelled' ? 'fa-times' : '' }}
                                               mr-1"></i>
                                        {{ ucfirst($appointment->status) }}
                                    </span>

                                    <!-- Action Dropdown -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" 
                                                class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 dark:bg-gray-800">
                                            <div class="py-1">
                                                <a href="{{ route('dashboard.appointments.edit', $appointment->id) }}"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <i class="fas fa-edit mr-2"></i>Edit
                                                </a>
                                                @if ($appointment->status !== 'cancelled')
                                                    <form method="POST" action="{{ route('dashboard.appointments.destroy', $appointment->id) }}" 
                                                          onsubmit="return confirm('Are you sure you want to cancel this appointment?')" class="block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                                            <i class="fas fa-times mr-2"></i>Cancel
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary for the day -->
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total for Day</p>
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
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Open</p>
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
        @endif
    </div>
@endsection
