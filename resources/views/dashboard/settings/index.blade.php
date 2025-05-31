@extends('dashboard.layout')

@section('content')
  <h1 class="text-2xl font-bold mb-6">Settings</h1>

  <form action="{{ route('dashboard.settings.store') }}" method="POST">
    @csrf
    @method('PUT')

    <div>
      <label for="navigation_style" class="block font-semibold mb-2">Navigation Style</label>
  
      <select name="settings[navigation_style]" id="navigation_style" class="w-full border rounded p-2 text-gray-300 dark:bg-gray-900">
        <option value="sidebar" {{ old('settings.navigation_style', $settings['navigation_style'] ?? '') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
        <option value="top_nav" {{ old('settings.navigation_style', $settings['navigation_style'] ?? '') === 'top_nav' ? 'selected' : '' }}>Top nav</option>
      </select>
    </div>

    <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      Save Settings
    </button>
  </form>
@endsection
