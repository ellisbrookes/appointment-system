<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ config('app.name') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link
            href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
            rel="stylesheet"
        />
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        />

        <!-- Styles / Scripts -->
        @vite(["resources/css/app.css", "resources/js/app.js"])
    </head>
    <body class="flex h-screen flex-col bg-white text-gray-900">
        <x-website.navigation />

        <main class="relative flex-1">
            @if (session("alert"))
                <x-shared.alert
                    class="absolute w-full"
                    :type="session('alert')['type']"
                >
                    {{ session("alert")["message"] }}
                </x-shared.alert>
            @endif

            @yield("content")
        </main>

        <x-website.footer />
    </body>
</html>
