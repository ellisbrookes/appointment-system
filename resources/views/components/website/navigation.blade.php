<nav class="dark:bg-gray-800 py-4 w-full border-b border-gray-400 dark:border-white">
  <div class="container mx-auto flex justify-between items-center">
    <p class="text-xl font-bold dark:text-white text-black">Appointment System</p>

    <ul class="flex gap-6">
      <x-shared.nav-link>
        <li>
          <a href="{{ route('index') }}" class="text-black dark:text-white hover:underline">Home</a>
        </li>
        <li>
          <a href="#" class="text-black dark:text-white hover:underline">Features</a>
        </li>
        <li>
          <a href="{{ route('pricing') }}" class="text-black dark:text-white hover:underline">Pricing</a>
        </li>
        <li>
          <a href="#" class="text-black dark:text-white hover:underline">Contact</a>
        </li>
      </x-shared.nav-link>
    </ul>

    @auth
      <a href="{{ route('dashboard') }}" class="rounded-md border px-6 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
        {{ Auth::user()->name }}
      </a>
    @else
      <a href="{{ route('login') }}" class="rounded-md border px-6 py-2 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
        Get Started
      </a>
    @endauth
  </div>
</nav>
