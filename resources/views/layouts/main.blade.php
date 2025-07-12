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
        @if(app()->environment('testing'))
            <!-- In testing, load minimal CSS to avoid Vite dependency -->
            <style>
                /* Basic Tailwind-like reset for testing */
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body { font-family: sans-serif; line-height: 1.5; }
                .flex { display: flex; }
                .h-screen { height: 100vh; }
                .flex-col { flex-direction: column; }
                .bg-white { background-color: white; }
                .text-gray-900 { color: #111827; }
                .relative { position: relative; }
                .flex-1 { flex: 1; }
                .absolute { position: absolute; }
                .w-full { width: 100%; }
            </style>
        @else
            @vite(["resources/css/app.css", "resources/js/app.js"])
        @endif
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
