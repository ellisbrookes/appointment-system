<aside
    :class="isSidebarOpen ? 'w-64' : 'w-16'"
    class="fixed flex h-screen flex-col border-r bg-white transition-all duration-250 ease-in-out dark:bg-gray-900"
>
    <!-- Sidebar Title & Toggle -->
    <div class="flex flex-1 flex-col space-y-4">
        <button
            @click="isSidebarOpen = !isSidebarOpen"
            :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
            class="flex w-full items-center border-b p-4 text-lg font-bold hover:bg-gray-200 dark:hover:bg-gray-600"
        >
            <span :class="isSidebarOpen ? '' : 'hidden'">
                Appointment System
            </span>
            <i :class="isSidebarOpen ? 'fas fa-arrow-left' : 'fas fa-bars'"></i>
        </button>

        <!-- Navigation Links -->
        <ul class="space-y-2">
            <!-- Home -->
            <li>
                <a
                    href="/dashboard"
                    :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                    class="block flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i class="fas fa-house text-lg"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Home</span>
                </a>
            </li>

            <!-- Appointments -->
            <li>
                <a
                    href="#"
                    @click.prevent="isDropdownOpen = !isDropdownOpen"
                    :class="isSidebarOpen ? 'px-4 py-2 justify-between space-x-4' : 'justify-center p-4'"
                    class="block flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <div class="flex w-full items-center justify-between">
                        <div>
                            <i
                                :class="isSidebarOpen ? 'mr-4' : 'mr-2'"
                                class="fas fa-calendar-check text-lg"
                            ></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">
                                Appointments
                            </span>
                        </div>

                        <i
                            :class="[isDropdownOpen ? 'rotate-180' : '', isSidebarOpen ? 'ml-4' : '']"
                            class="fas fa-chevron-down text-sm transition-transform duration-300"
                        ></i>
                    </div>
                </a>

                <ul
                    x-show="isDropdownOpen"
                    x-transition
                    class="mt-2 space-y-1"
                >
                    <li>
                        <a
                            href="/dashboard/appointments"
                            :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                            class="block flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <i
                                :class="isSidebarOpen ? 'ml-4' : ''"
                                class="fas fa-eye"
                            ></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">
                                View Appointments
                            </span>
                        </a>
                    </li>
                    <li>
                        <a
                            href="/dashboard/appointments/create-step-one"
                            :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                            class="block flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <i
                                :class="isSidebarOpen ? 'ml-4' : ''"
                                class="fas fa-plus-circle"
                            ></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">
                                Create Appointment
                            </span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Admin -->
            @if (Auth::user()->admin)
                <li>
                    <a
                        href="/dashboard/admin"
                        :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                        class="block flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                    >
                        <i class="fas fa-cogs text-lg"></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">
                            Admin
                        </span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- User Profile -->
    <div class="relative">
        <a
            href="#"
            @click.prevent="isUserSidebarOpen = !isUserSidebarOpen"
            :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
            class="block flex w-full items-center border-t p-4 hover:bg-gray-200 dark:hover:bg-gray-600"
        >
            <div class="flex w-full items-center">
                <i class="fas fa-user-circle mr-4 text-lg"></i>
                <span :class="isSidebarOpen ? '' : 'hidden'">
                    {{ Auth::user()->name ?? "Guest" }}
                </span>
            </div>

            <i
                :class="isUserSidebarOpen ? 'rotate-180' : ''"
                class="fas fa-chevron-down text-sm transition-transform duration-300"
            ></i>
        </a>

        <ul
            x-show="isUserSidebarOpen"
            x-transition
            class="absolute bottom-full left-0 z-10 w-full"
        >
            <li>
                <a
                    href="{{ route("dashboard.profile.edit") }}"
                    :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                    class="block flex w-full items-center border-t px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i
                        :class="isSidebarOpen ? 'ml-4' : ''"
                        class="fas fa-user"
                    ></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Profile</span>
                </a>
            </li>
            <li>
                <a
                    href="{{ route("billing") }}"
                    :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                    class="block flex w-full items-center border-t px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i
                        :class="isSidebarOpen ? 'ml-4' : ''"
                        class="fas fa-shopping-cart"
                    ></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Billing</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route("logout") }}">
                    @csrf
                    <button
                        type="submit"
                        :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                        class="block flex w-full items-center border-t px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                    >
                        <i
                            :class="isSidebarOpen ? 'ml-4' : ''"
                            class="fas fa-sign-out-alt"
                        ></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">
                            Logout
                        </span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
