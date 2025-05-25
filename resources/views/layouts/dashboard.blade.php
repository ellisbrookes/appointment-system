<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

  @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  @endif
</head>
<body x-data="{ isSidebarOpen: true, isDropdownOpen: false, isUserSidebarOpen: false }" class="text-gray-800 dark:text-white">
  <!-- Sidebar -->
  @include('dashboard.partials.sidebar')
  @include('dashboard.partials.top-nav')

  <main :class="isSidebarOpen ? 'ml-64' : 'ml-16'" class="flex flex-col flex-1 py-8 px-6 bg-white dark:bg-gray-900 h-screen">
    @if (session('alert'))
      <x-alert :type="session('alert')['type']">
        {{ session('alert')['message'] }}
      </x-alert>
    @endif

    @yield('content')
  </main>

  <!-- Blur Overlay -->
  <!-- @if(auth()->user()->subscribed('basic') === false)
    <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-slate-800 rounded-lg p-6 max-w-lg mx-auto text-center shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-2">Your subscription is inactive</h1>
        <p class="mb-4 text-sm">Please renew your subscription to continue using the app.</p>
        <a href="{{ route('billing') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-6 py-2 rounded-md transition duration-200">
          Renew Subscription
        </a>
      </div>
    </div>
  @endif -->
</body>
</html>
