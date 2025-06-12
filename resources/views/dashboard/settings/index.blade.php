@extends('dashboard.layout')

@section('content')
  <h1 class="text-4xl font-bold mb-2 text-center">Settings</h1>
  <p class="text-lg font-semibold text-center mb-6">Here you can find all the settings</p>

  <div class="flex justify-center items-center">
    <form action="{{ route('store') }}" method="POST" class="w-full max-w-7xl">
      @csrf
      @method('PUT')

      {{-- Appearance Section --}}
      <div>
        <h2 class="text-2xl font-bold mb-4">Appearance</h2>
        <x-shared.input-label for="navigation_style" :value="__('Navigation Type')" />

        <select name="settings[navigation_style]" id="navigation_style"
          class="block mt-1 w-full bg-transparent dark:bg-gray-800 focus:border-gray-500 dark:focus:border-gray-600 focus:ring-gray-500 dark:focus:ring-gray-600 rounded-md">
          <option value="sidebar" {{ old('settings.navigation_style', $settings['navigation_style'] ?? '') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
          <option value="top_nav" {{ old('settings.navigation_style', $settings['navigation_style'] ?? '') === 'top_nav' ? 'selected' : '' }}>Top nav</option>
        </select>

        <p class="mt-2 text-sm text-gray-500">
          Navigation will be set on sidebar by default
        </p>
      </div>

      {{-- Timeslot Section --}}
      <div class="mt-4">
        <h2 class="text-2xl font-bold mb-4">Timeslot</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          {{-- Start Time --}}
          <div>
            <x-shared.input-label for="settings[timeslot_start]" :value="'Start Time'" />
            <input type="time" name="settings[timeslot_start]" value="{{ old('settings.timeslot_start', $settings['timeslot_start'] ?? '') }}"
              class="w-full rounded-md border-gray-300 dark:bg-gray-800">
          </div>

          {{-- End Time --}}
          <div>
            <x-shared.input-label for="settings[timeslot_end]" :value="'End Time'" />
            <input type="time" name="settings[timeslot_end]" value="{{ old('settings.timeslot_end', $settings['timeslot_end'] ?? '') }}"
              class="w-full rounded-md border-gray-300 dark:bg-gray-800">
          </div>

          {{-- Interval --}}
          <div>
            <x-shared.input-label for="settings[timeslot_interval]" :value="'Gap (in minutes)'" />
            <input type="number" name="settings[timeslot_interval]" min="5" max="120" step="5"
              value="{{ old('settings.timeslot_interval', $settings['timeslot_interval'] ?? 30) }}"
              class="w-full rounded-md border-gray-300 dark:bg-gray-800">
          </div>
        </div>

        <p class="mt-2 text-sm text-gray-500">
          Timeslots will be automatically generated between start and end time, using intervals of 30 minutes.
        </p>
      </div>

      {{-- Submit --}}
      <x-shared.primary-button class="mt-6">
        {{ __('Save') }}
      </x-shared.primary-button>
    </form>
  </div>

  {{-- Script for Adding New Timeslot Inputs --}}
  <script>
    function addTimeslotInput() {
      const wrapper = document.getElementById('timeslot-fields');
      const input = document.createElement('input');
      input.type = 'time';
      input.name = 'settings[timeslots][]';
      input.className = 'w-32 rounded-md border-gray-300 dark:bg-gray-800 mt-1';
      wrapper.appendChild(input);
    }
  </script>
@endsection
