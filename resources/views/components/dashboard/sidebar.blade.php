<aside
    :class="isSidebarOpen ? 'w-64' : 'w-16'"
    class="fixed z-40 flex h-screen flex-col border-r bg-white transition-all duration-250 ease-in-out dark:bg-gray-900"
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
                    class="flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i class="fas fa-house text-lg"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Home</span>
                </a>
            </li>

            <!-- Appointments -->
            <li>
                <details class="group">
                    <summary
                        :class="isSidebarOpen ? 'px-4 py-2 justify-between space-x-4' : 'justify-center p-4'"
                        class="flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer list-none"
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
                                :class="isSidebarOpen ? 'ml-4' : ''"
                                class="fas fa-chevron-down text-sm transition-transform duration-300 group-open:rotate-180"
                            ></i>
                        </div>
                    </summary>

                    <ul class="mt-2 space-y-1">
                        <li>
                            <a
                                href="/dashboard/appointments"
                                :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                                class="flex w-full items-center border-b px-4 text-sm hover:bg-gray-200 dark:hover:bg-gray-600"
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
                                class="flex w-full items-center border-b px-4 text-sm hover:bg-gray-200 dark:hover:bg-gray-600"
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
                </details>
            </li>

            <!-- Company -->
            <li>
                <details class="group">
                    <summary
                        :class="isSidebarOpen ? 'px-4 py-2 justify-between space-x-4' : 'justify-center p-4'"
                        class="flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer list-none"
                    >
                        <div class="flex w-full items-center justify-between">
                            <div>
                                <i
                                    :class="isSidebarOpen ? 'mr-4' : 'mr-2'"
                                    class="fas fa-building text-lg"
                                ></i>
                                <span :class="isSidebarOpen ? '' : 'hidden'">
                                    Company
                                </span>
                            </div>

                            <i
                                :class="isSidebarOpen ? 'ml-4' : ''"
                                class="fas fa-chevron-down text-sm transition-transform duration-300 group-open:rotate-180"
                            ></i>
                        </div>
                    </summary>

                    <ul class="mt-2 space-y-1">
                        <li>
                            <a
                                href="/dashboard/companies"
                                :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                                class="flex w-full items-center border-b px-4 text-sm hover:bg-gray-200 dark:hover:bg-gray-600"
                            >
                                <i
                                    :class="isSidebarOpen ? 'ml-4' : ''"
                                    class="fas fa-eye"
                                ></i>
                                <span :class="isSidebarOpen ? '' : 'hidden'">
                                    View Company
                                </span>
                            </a>
                        </li>
                        <li>
                            <a
                                href="/dashboard/companies/create"
                                :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                                class="flex w-full items-center border-b px-4 text-sm hover:bg-gray-200 dark:hover:bg-gray-600"
                            >
                                <i
                                    :class="isSidebarOpen ? 'ml-4' : ''"
                                    class="fas fa-plus-circle"
                                ></i>
                                <span :class="isSidebarOpen ? '' : 'hidden'">
                                    Create Company
                                </span>
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

            <!-- Admin -->
            @if (Auth::user()->admin)
                <li>
                    <a
                        href="/dashboard/admin"
                        :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                        class="flex w-full items-center border-b px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
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
    <details class="group relative">
        <summary
            :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
            class="flex w-full items-center border-t p-4 hover:bg-gray-200 dark:hover:bg-gray-600 cursor-pointer list-none"
        >
            <div class="flex w-full items-center">
                <i class="fas fa-user-circle mr-4 text-lg"></i>
                <span :class="isSidebarOpen ? '' : 'hidden'">
                    {{ Auth::user()->name ?? "Guest" }}
                </span>
            </div>

            <i
                class="fas fa-chevron-down text-sm transition-transform duration-300 group-open:rotate-180"
            ></i>
        </summary>

        <ul class="absolute bottom-full left-0 z-10 w-full">
            <li>
                <a
                    href="{{ route("dashboard") }}"
                    :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                    class="flex w-full items-center border-t px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
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
                    class="flex w-full items-center border-t px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i
                        :class="isSidebarOpen ? 'ml-4' : ''"
                        class="fas fa-shopping-cart"
                    ></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Billing</span>
                </a>
            </li>
            <li>
                <a
                    href="{{ route("settings") }}"
                    :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                    class="flex w-full items-center border-t px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    <i
                        :class="isSidebarOpen ? 'ml-4' : ''"
                        class="fas fa-gear"
                    ></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Settings</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route("logout") }}">
                    @csrf
                    <button
                        type="submit"
                        :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                        class="flex w-full items-center border-t px-4 hover:bg-gray-200 dark:hover:bg-gray-600"
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
    </details>
</aside>
