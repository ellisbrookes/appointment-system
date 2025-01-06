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
        <div
            x-data="{
                isSidebarOpen: true,
                isDropdownOpen: false,
                isUserSidebarOpen: false
            }"
            class="flex relative"
        >
            <!-- Sidebar -->
            @include('partials.dashboard.navigation')

            <main :class="isSidebarOpen ? 'ml-64' : 'ml-16'" class="flex-1 p-6">
                @if (session('alert'))
                    <x-alert :type="session('alert')['type']">
                        {{ session('alert')['message'] }}
                    </x-alert>
                @endif

                @yield('content')
            </main>

            <!-- Blur Overlay -->
            {{-- @if(Subscription::query()->active())
                <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                    <div class="text-center text-white">
                        <h1 class="text-2xl font-bold">Your subscription is inactive.</h1>
                        <p>Please renew your subscription to access the app.</p>
                        <a href="{{ route('subscription.renew') }}" class="mt-4 bg-blue-500 px-4 py-2 rounded text-white">
                            Renew Subscription
                        </a>
                    </div>
                </div>
            @endif --}}
        </div>
    </body>
</html>
