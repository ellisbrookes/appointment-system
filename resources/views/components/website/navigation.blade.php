<nav
    class="w-full border-b border-gray-400 py-4 dark:border-white dark:bg-gray-800"
>
    <div class="container mx-auto flex items-center justify-between">
        <p class="text-xl font-bold text-black dark:text-white">
            Skedulaa
        </p>

        <div class="flex items-center gap-6">
            <x-shared.link route="home" :active="request()->routeIs('home')">
                Home
            </x-shared.link>

            <x-shared.link route="home" :active="false">
                Features
            </x-shared.link>

            <x-shared.link
                route="pricing"
                :active="request()->routeIs('pricing')"
            >
                Pricing
            </x-shared.link>

            <x-shared.link route="home" :active="false">Contact</x-shared.link>
        </div>

        @auth
            <x-shared.link route="dashboard">
                {{ Auth::user()->name }}
            </x-shared.link>
        @else
            <x-shared.link route="login">Get Started</x-shared.link>
        @endauth
    </div>
</nav>
