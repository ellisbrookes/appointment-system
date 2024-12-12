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
        <span :class="open ? '' : 'hidden'" class="text-lg font-bold">Appointment System</span>
        <i :class="open ? 'fas fa-arrow-left' : 'fas fa-bars'"></i> <!-- FontAwesome icon (hamburger / arrow) -->
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
        <li :class="open ? 'justify-between' : 'justify-center'">
            <a
                href="/appointments"
                :class="open ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                class="flex items-center w-full space-x-4 hover:bg-blue-700"
            >
                <i class="fas fa-calendar-check text-lg"></i>
                <span :class="open ? '' : 'hidden'">Appointments</span>
            </a>
        </li>
        <!-- Admin Link -->
        <li :class="open ? 'justify-between' : 'justify-center'">
            <a
                href="/admin"
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
                <div :class="open ? '' : 'justify-center'" class="flex items-center space-x-2">
                    <i class="fas fa-user-circle text-lg"></i> <!-- User Icon -->
                    <span :class="open ? '' : 'hidden'">User Name</span>
                </div>
                <!-- Dropdown arrow -->
                <i :class="open ? '' : 'hidden'" class="fas fa-chevron-up ml-2 transition-transform transform group-hover:rotate-180"></i>
            </button>

            <!-- Dropdown Menu that goes up -->
            <div class="absolute left-0 bottom-full mb-2 w-full bg-gray-700 rounded-md shadow-lg hidden group-focus-within:block z-10">
                <ul>
                    <li>
                        <a href="/profile" class="px-4 py-2 block hover:bg-blue-700 items-center">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="/account-settings" class="px-4 py-2 block hover:bg-blue-700 items-center">
                            <i class="fas fa-cogs mr-2"></i> Account Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
