<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard</title>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />
        <script
            src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js"
            defer
        ></script>
        @if (file_exists(public_path("build/manifest.json")) || file_exists(public_path("hot")))
            @vite(["resources/css/app.css", "resources/js/app.js"])
        @endif
    </head>
    <body
        @php
            $navigationStyle = optional(Auth::user()->settings)["navigation_style"] ?? "sidebar";
        @endphp
        x-data="{
        navigationStyle: '{{ $navigationStyle }}',
        isSidebarOpen: true,
        isDropdownOpen: false,
        isUserSidebarOpen: false,
        isCompanyDropdownOpen: false,
        isAppointmentsDropdownOpen: false,
    }"
        class="text-gray-800 dark:text-white"
    >
        @if ($navigationStyle === "sidebar")
            @include("components.dashboard.sidebar")
        @elseif ($navigationStyle === "top_nav")
            @include("components.dashboard.top-nav")
        @endif

        {{-- Main Content --}}
        <main
            :class="{
            'ml-64': isSidebarOpen && navigationStyle === 'sidebar',
            'ml-16': !isSidebarOpen && navigationStyle === 'sidebar',
            'ml-0': !isSidebarOpen && navigationStyle === 'top_nav'
        }"
            class="flex h-screen flex-1 flex-col bg-white px-6 py-8 dark:bg-gray-900"
        >
            @if (session("alert"))
                <x-shared.alert :type="session('alert')['type']">
                    {{ session("alert")["message"] }}
                </x-shared.alert>
            @endif

            @yield("content")
        </main>
    </body>
</html>
