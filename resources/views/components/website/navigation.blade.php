<nav
    class="w-full border-b border-gray-400 py-4 dark:border-white dark:bg-gray-800"
>
    <div class="container mx-auto flex items-center justify-between">
        <p class="text-xl font-bold text-black dark:text-white">
            Appointment System
        </p>

        <ul class="flex gap-6">
            <x-shared.nav-link>
                <li>
                    <a
                        href="{{ route("home") }}"
                        class="text-black hover:underline dark:text-white"
                    >
                        Home
                    </a>
                </li>
                <li>
                    <a
                        href="#"
                        class="text-black hover:underline dark:text-white"
                    >
                        Features
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route("pricing") }}"
                        class="text-black hover:underline dark:text-white"
                    >
                        Pricing
                    </a>
                </li>
                <li>
                    <a
                        href="#"
                        class="text-black hover:underline dark:text-white"
                    >
                        Contact
                    </a>
                </li>
            </x-shared.nav-link>
        </ul>

        @auth
            <a
                href="{{ route("dashboard") }}"
                class="rounded-md border px-6 py-2 transition-colors hover:bg-gray-200 dark:hover:bg-gray-600"
            >
                {{ Auth::user()->name }}
            </a>
        @else
            <a
                href="{{ route("login") }}"
                class="rounded-md border px-6 py-2 transition-colors hover:bg-gray-200 dark:hover:bg-gray-600"
            >
                Get Started
            </a>
        @endauth
    </div>
</nav>
