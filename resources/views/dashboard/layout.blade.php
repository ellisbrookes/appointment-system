<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Include Sidebar Navigation -->
        @include('dashboard.navigation')

        <!-- Main Content Area -->
        <main class="ml-64 flex-grow p-8 bg-gray-800 text-white">
            @yield('content')
        </main>
    </div>
</body>
</html>
