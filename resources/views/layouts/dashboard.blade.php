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
        x-data="{
            isSidebarOpen: true,
            isDropdownOpen: false,
            isUserSidebarOpen: false,
        }"
        class="text-gray-800 dark:text-white"
    >
        <!-- Sidebar -->
        @include("dashboard.partials.sidebar")
        @include("dashboard.partials.top-nav")

        <main
            :class="isSidebarOpen ? 'ml-64' : 'ml-16'"
            class="flex h-screen flex-1 flex-col bg-white px-6 py-8 dark:bg-gray-900"
        >
            @if (session("alert"))
                <x-alert :type="session('alert')['type']">
                    {{ session("alert")["message"] }}
                </x-alert>
            @endif

            @yield("content")
        </main>
    </body>
</html>
