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
<body class="bg-gray-100">
    <div class="flex">
        <!-- Include Sidebar Navigation -->
        @include('dashboard.navigation')

        <!-- Main Content Area -->
        <main class="ml-64 flex-grow p-8 bg-gray-800 text-white">
            @yield('content') <!-- Only this section should render the content -->
        </main>
    </div>
</body>
</html>
