@extends('dashboard.layout')

@section('content')
  <h1 class="text-4xl font-bold mb-2 text-center">Settings</h1>
  <p class="text-lg font-semibold text-center mb-6">Here you can find all the settings</p>

  <div class="flex justify-center items-center">
    <form action="{{ route('store') }}" method="POST" class="w-full max-w-7xl">
      @csrf
      @method('PUT')

      <div>
        <h2 class="text-2xl font-bold mb-4">Appearance</h2>
        <x-shared.input-label for="navigation_style" :value="__('Navigation Style')" />

        <select name="settings[navigation_style]" id="navigation_style" class="block mt-1 w-full bg-transparent dark:bg-gray-800 focus:border-gray-500 dark:focus:border-gray-600 focus:ring-gray-500 dark:focus:ring-gray-600 rounded-md">
          <option value="sidebar" {{ old('settings.navigation_style', $settings['navigation_style'] ?? '') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
          <option value="top_nav" {{ old('settings.navigation_style', $settings['navigation_style'] ?? '') === 'top_nav' ? 'selected' : '' }}>Top nav</option>
        </select>
      </div>

      <x-shared.primary-button class="mt-4">
        {{ __('Save') }}
      </x-shared.primary-button>  
    </form>
  </div>
@endsection
