<div
    :class="open ? 'w-64' : 'w-16'"
    class="bg-gray-800 text-white h-screen space-y-4 fixed transition-all duration-250 ease-in-out z-20"
>
    <!-- Sidebar Title and Toggle Button inside Sidebar -->
    <button
        @click="open = !open"
        :class="open ? 'justify-between' : 'justify-center'"
        class="flex items-center p-4 text-lg font-bold w-full hover:bg-blue-700"
    >
        <span
            :class="open ? '' : 'hidden'"
            class="text-lg font-bold"
        >
            Appointment System
        </span>

        <i :class="open ? 'fas fa-arrow-left' : 'fas fa-bars'"></i>
    </button>

    <!-- Navigation links -->
    <ul :class="open ? 'px-4' : ''" class="space-y-2">
        <!-- Homepage link -->
        <li :class="open ? 'justify-between' : 'justify-center'">
            <a
                href="/dashboard"
                :class="open ? 'rounded-md px-4 p-2' : 'justify-center rounded-none p-4'"
                class="flex items-center w-full space-x-4 hover:bg-blue-700"
            >
                <i class="fas fa-house text-lg"></i>
                <span :class="open ? '' : 'hidden'">Home</span>
            </a>
        </li>

        <!-- Appointments Link -->
        <li
            :class="open ? 'justify-between' : 'justify-center'"
            x-data="{ dropdownOpen: false }"
        >
            <a
                href="#"
                @click.prevent="dropdownOpen = !dropdownOpen"
                :class="open ? 'rounded-md px-4 py-2 justify-between' : 'justify-center rounded-none p-4'"
                class="flex items-center w-full space-x-4 hover:bg-blue-700"
            >
                <div>
                    <i
                        :class="open ? 'mr-4' : ''"
                        class="fas fa-calendar-check text-lg"
                    ></i>
                    <span :class="open ? '' : 'hidden'">Appointments</span>
                </div>

                <i
                    class="fas fa-chevron-down text-sm ml-auto transition-transform duration-300"
                    :class="dropdownOpen ? 'rotate-180' : ''"
                ></i>
            </a>

            <!-- Dropdown menu -->
            <ul
                x-transition
                x-show="dropdownOpen"
                class="mt-2 w-full"
            >
                <li>
                    <a
                        href="/dashboard/appointments"
                        :class="open ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                        class="flex items-center w-full space-x-4 hover:bg-blue-700"
                    >
                        <i class="fas fa-eye"></i>
                        <span :class="open ? '' : 'hidden'">View Appointments</span>
                    </a>
                </li>
                <li>
                    <a
                        href="/dashboard/appointments/create-step-one"
                        :class="open ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                        class="flex items-center w-full space-x-4 hover:bg-blue-700"
                    >
                        <i class="fas fa-plus-circle"></i>
                        <span :class="open ? '' : 'hidden'">Create Appointment</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Admin Link -->
        <li :class="open ? 'justify-between' : 'justify-center'">
            <a
                href="/dashboard/admin"
                :class="open ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                class="flex items-center w-full space-x-4 hover:bg-blue-700"
            >
                <i class="fas fa-cogs text-lg"></i>
                <span :class="open ? '' : 'hidden'">Admin</span>
            </a>
        </li>
    </ul>

    <!-- User Profile Section at the bottom -->
    <div class="absolute bottom-0 left-0 w-full">
        <div class="relative group">
            <button
                :class="open ? 'justify-between' : 'justify-center'"
                class="flex items-center p-4 w-full hover:bg-blue-700"
            >
                <div
                    :class="open ? '' : 'justify-center'"
                    class="flex items-center space-x-2"
                >
                    <i class="fas fa-user-circle text-lg"></i>
                    <span :class="open ? '' : 'hidden'">User Name</span>
                </div>

                <!-- Dropdown arrow -->
                <i
                    :class="open ? '' : 'hidden'"
                    class="fas fa-chevron-up ml-2 transition-transform transform group-hover:rotate-180"
                ></i>
            </button>

            <!-- Dropdown Menu that goes up -->
            <div
                class="absolute left-0 bottom-full mb-2 w-full bg-gray-700 rounded-md shadow-lg hidden group-focus-within:block z-10"
                :class="open ? '' : 'text-center'"
                @click.prevent="open = !open"
            >
                <ul>
                    <li>
                        <a
                            href="/profile"
                            class="px-4 py-2 block hover:bg-blue-700 items-center"
                        >
                            <i class="fas fa-user" :class="open ? 'mr-2' : ''"></i>
                            <span :class="open ? '' : 'hidden'">Profile</span>
                        </a>
                    </li>
                    <li>
                        <a
                            href="/account-settings"
                            class="px-4 py-2 block hover:bg-blue-700 items-center"
                        >
                            <i class="fas fa-cogs" :class="open ? 'mr-2' : ''"></i>
                            <span :class="open ? '' : 'hidden'">Account Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
