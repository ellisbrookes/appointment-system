<nav
    class="flex justify-between border-b bg-white transition-all duration-250 ease-in-out dark:bg-gray-900"
>
    <!-- Sidebar Title & Toggle -->
    <div class="flex">
        <a
            href="/dashboard"
            class="border-x px-6 py-4 text-lg hover:bg-gray-200 dark:hover:bg-gray-600"
        >
            Appointment System
        </a>

        <!-- Navigation Links -->
        <div class="flex">
            <!-- Home -->
            <a
                href="/dashboard"
                class="block flex items-center justify-center space-x-4 border-r px-6 py-4 hover:bg-gray-200 dark:hover:bg-gray-600"
            >
                <i class="fas fa-house text-lg"></i>
                <span>Home</span>
            </a>

            <!-- Appointments -->
            <div class="relative">
                <a
                    href="#"
                    @click.prevent="isDropdownOpen = !isDropdownOpen"
                    class="relative block flex items-center justify-center space-x-4 border-r px-6 py-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-calendar-check text-lg"></i>
                        <span>Appointments</span>
                    </div>

                    <i
                        :class="isDropdownOpen ? 'rotate-180' : ''"
                        class="fas fa-chevron-down text-sm transition-transform duration-300"
                    ></i>
                </a>

                <ul
                    x-show="isDropdownOpen"
                    x-transition
                    class="absolute left-0 z-10 w-full border bg-white dark:bg-gray-900"
                >
                    <li>
                        <a
                            href="/dashboard/appointments"
                            class="block flex items-center space-x-4 border-b px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <i class="fas fa-eye"></i>
                            <span>View Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a
                            href="/dashboard/appointments/create-step-one"
                            class="block flex items-center space-x-4 border-b px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <i class="fas fa-plus-circle"></i>
                            <span>Create Appointment</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- User Profile -->
    <div class="relative">
        <a
            href="#"
            @click.prevent="isUserSidebarOpen = !isUserSidebarOpen"
            class="relative block flex items-center justify-center space-x-4 border-x px-6 py-4 hover:bg-gray-200 dark:hover:bg-gray-600"
        >
            <div class="flex items-center space-x-4">
                <i class="fas fa-user-circle text-lg"></i>
                <span>{{ Auth::user()->name ?? "Guest" }}</span>
            </div>

            <i
                :class="isUserSidebarOpen ? 'rotate-180' : ''"
                class="fas fa-chevron-down text-sm transition-transform duration-300"
            ></i>
        </a>

        <ul
            x-show="isUserSidebarOpen"
            x-transition
            class="absolute left-0 z-10 w-full border bg-white dark:bg-gray-900"
        >
            <li>
                <a
                    href="{{ route("dashboard.profile.edit") }}"
                    class="block flex items-center space-x-4 border-t px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a
                    href="{{ route("billing") }}"
                    class="block flex items-center space-x-4 border-t px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i class="fas fa-shopping-cart"></i>
                    <span>Billing</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route("logout") }}">
                    @csrf
                    <button
                        type="submit"
                        class="block flex w-full items-center space-x-4 border-t px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
                    >
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
