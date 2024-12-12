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
    <body>
        <div x-data="{ open: true }" class="flex">
            <!-- Sidebar -->
            @include('partials.dashboard.navigation')

            <main :class="open ? 'ml-64' : 'ml-16'" class="flex-1 p-6 transition-all duration-250 ease-in-out">
                @yield('content') <!-- Only the main content should be rendered here -->
            </main>
        </div>
    </body>
</html>
