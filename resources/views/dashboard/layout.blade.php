<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
</head>
<body 
  x-data="{
    navigationStyle: '{{ Auth::user()->settings['navigation_style'] ?? 'sidebar' }}',
    isSidebarOpen: true,
    isDropdownOpen: false,
    isUserSidebarOpen: false
  }" 
  class="text-gray-800 dark:text-white"
>
  @if(Auth::user()->settings['navigation_style'] === 'sidebar')
    @include('components.dashboard.sidebar')
  @elseif (Auth::user()->settings['navigation_style'] === 'top_nav')
    @include('components.dashboard.top-nav')
  @endif
  
  <main
    :class="{
      'ml-64': isSidebarOpen && navigationStyle === 'sidebar',
      'ml-16': !isSidebarOpen && navigationStyle === 'sidebar',
      'ml-0': !isSidebarOpen && navigationStyle === 'top_nav'
    }"
    class="flex flex-col flex-1 py-8 px-6 bg-white dark:bg-gray-900 h-screen"
  >
    @if (session('alert'))
      <x-shared.alert :type="session('alert')['type']">
        {{ session('alert')['message'] }}
      </x-shared.alert>
    @endif

    @yield('content')
  </main>
</body>
</html>
