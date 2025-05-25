<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
  </head>
  <body class="min-h-screen flex flex-col"> 
    @include('components.website.navigation')

    <main class="flex-1">
      @if (session('alert'))
        <x-alert :type="session('alert')['type']">
          {{ session('alert')['message'] }}
        </x-alert>
      @endif

      @yield('content')
    </main>

    @include('components.website.footer')
  </body>
</html>
