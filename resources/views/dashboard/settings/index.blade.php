@extends('dashboard.layout')

@section('content')
  <h1 class="text-2xl font-bold mb-6">Settings</h1>

  @if (session('alert'))
    <div class="mb-4 p-4 rounded {{ session('alert.type') == 'success' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
      {{ session('alert.message') }}
    </div>
  @endif

  <form action="{{ route('dashboard.settings.update') }}" method="POST" class="max-w-md">
    @csrf

    <label for="navigation_style" class="block font-semibold mb-2">Navigation Style</label>

    <select name="navigation_style" id="navigation_style" class="w-full border rounded p-2">
      <option value="sidebar" {{ old('navigation_style') == 'sidebar' ? 'selected' : '' }}>Sidebar</option>
      <option value="topnav" {{ old('navigation_style') == 'topnav' ? 'selected' : '' }}>Top Navigation</option>
    </select>

    @error('navigation_style')
      <p class="text-red-600 mt-1">{{ $message }}</p>
    @enderror

    <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
      Save Settings
    </button>
  </form>
@endsection
