<aside
    :class="isSidebarOpen ? 'w-64' : 'w-16'"
    class="bg-gray-800 flex flex-col text-white h-screen fixed transition-all duration-250 ease-in-out"
>
    <!-- Sidebar Title and Toggle Button inside Sidebar -->
    <div
        class="flex flex-col flex-1 space-y-4">
        <button
            @click="isSidebarOpen = !isSidebarOpen"
            :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
            class="flex items-center p-4 text-lg font-bold w-full hover:bg-blue-700"
        >
            <span
                :class="isSidebarOpen ? '' : 'hidden'"
                class="text-lg font-bold"
            >
                Appointment System
            </span>

            <i :class="isSidebarOpen ? 'fas fa-arrow-left' : 'fas fa-bars'"></i>
        </button>

        <!-- Navigation links -->
        <ul :class="isSidebarOpen ? 'px-4' : ''" class="space-y-2">
            <!-- Homepage link -->
            <li>
                <a
                    href="/dashboard"
                    :class="isSidebarOpen ? 'rounded-md px-4 p-2' : 'justify-center rounded-none p-4'"
                    class="flex items-center w-full space-x-4 hover:bg-blue-700"
                >
                    <i class="fas fa-house text-lg"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Home</span>
                </a>
            </li>

            <!-- Appointments Link -->
            <li>
                <a
                    href="#"
                    @click.prevent="isDropdownOpen = !isDropdownOpen"
                    :class="isSidebarOpen ? 'rounded-md px-4 py-2 justify-between' : 'justify-center rounded-none p-4'"
                    class="flex items-center space-x-2 hover:bg-blue-700"
                >
                    <div>
                        <i
                            :class="isSidebarOpen ? 'mr-4' : ''"
                            class="fas fa-calendar-check text-lg"
                        ></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Appointments</span>
                    </div>

                    <i
                        :class="isDropdownOpen ? 'rotate-180' : ''"
                        class="fas fa-chevron-down text-sm transition-transform duration-300"
                    ></i>
                </a>

                <!-- Dropdown menu -->
                <ul
                    x-transition
                    x-show="isDropdownOpen"
                    class="mt-2"
                >
                    <li>
                        <a
                            href="/dashboard/appointments"
                            :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                            class="flex items-center w-full space-x-4 hover:bg-blue-700"
                        >
                            <i class="fas fa-eye"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">View Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a
                            href="/dashboard/appointments/create-step-one"
                            :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                            class="flex items-center w-full space-x-4 hover:bg-blue-700"
                        >
                            <i class="fas fa-plus-circle"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">Create Appointment</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Admin Link -->
            <li>
                <a
                    href="/dashboard/admin"
                    :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                    class="flex items-center w-full space-x-4 hover:bg-blue-700"
                >
                    <i class="fas fa-cogs text-lg"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Admin</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Profile Section at the bottom -->
    <div
        class="relative group"
        x-transition
        x-show="isDropdownOpen"
        class="mt-2"
    >
        <button
            :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
            class="flex items-center p-4 w-full hover:bg-blue-700"
        >
            <div
                :class="isSidebarOpen ? '' : 'justify-center'"
                class="flex items-center space-x-2"
            >
                <i class="fas fa-user-circle text-lg"></i>
                <span :class="isSidebarOpen ? '' : 'hidden'">User Name</span>
            </div>

            <!-- Dropdown arrow -->
            <i
                :class="isSidebarOpen ? '' : 'hidden'"
                class="fas fa-chevron-up ml-2 transition-transform transform group-hover:rotate-180"
            ></i>
        </button>

        <!-- Dropdown Menu that goes up -->
        <div
            class="absolute left-0 bottom-full mb-2 w-full bg-gray-700 rounded-md shadow-lg hidden group-focus-within:block z-10"
            :class="isSidebarOpen ? '' : 'text-center'"
            @click.prevent="isSidebarOpen = !isSidebarOpen"
        >
            <ul>
                <li>
                    <a
                        href="/profile"
                        class="px-4 py-2 block hover:bg-blue-700 items-center"
                    >
                        <i class="fas fa-user" :class="isSidebarOpen ? 'mr-2' : ''"></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Profile</span>
                    </a>
                </li>
                <li>
                    <a
                        href="/account-settings"
                        class="px-4 py-2 block hover:bg-blue-700 items-center"
                    >
                        <i class="fas fa-cogs" :class="isSidebarOpen ? 'mr-2' : ''"></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Account Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
