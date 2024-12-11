<div x-data="{ open: true }" class="flex">
    <!-- Sidebar -->
    <div :class="open ? 'w-64' : 'w-16'" class="bg-gray-800 h-screen p-4 space-y-6 fixed transition-all duration-300 ease-in-out z-20">
        
        <!-- Sidebar Title and Toggle Button inside Sidebar -->
        <div class="text-white text-2xl font-bold mb-4 flex items-center justify-between">
            <span :class="open ? '' : 'hidden'">Appointment System</span>
            <!-- Toggle Button inside the sidebar -->
            <button @click="open = !open" class="text-white">
                <i :class="open ? 'fas fa-arrow-left' : 'fas fa-bars'"></i> <!-- FontAwesome icon (hamburger / arrow) -->
            </button>
        </div>
        
        <ul class="space-y-4">
            <!-- Homepage link -->
            <li :class="open ? '' : 'flex justify-center'">
                <a href="/dashboard" class="text-white hover:bg-blue-700 px-4 py-2 rounded-md block flex items-center w-full">
                    <i class="fas fa-house text-xl"></i> <!-- Home Icon -->
                    <span :class="open ? '' : 'hidden'">Home</span>
                </a>
            </li>
            <!-- Appointments Link -->
            <li :class="open ? '' : 'flex justify-center'">
                <a href="/appointments" class="text-white hover:bg-blue-700 px-4 py-2 rounded-md block flex items-center w-full">
                    <i class="fas fa-calendar-check text-xl"></i> <!-- Appointments Icon -->
                    <span :class="open ? '' : 'hidden'">Appointments</span>
                </a>
            </li>
            <!-- Admin Link -->
            <li :class="open ? '' : 'flex justify-center'">
                <a href="/admin" class="text-white hover:bg-blue-700 px-4 py-2 rounded-md block flex items-center w-full">
                    <i class="fas fa-cogs text-xl"></i> <!-- Admin Icon -->
                    <span :class="open ? '' : 'hidden'">Admin</span>
                </a>
            </li>
        </ul>

        <!-- User Profile Section at the bottom -->
        <div class="absolute bottom-4 left-0 w-full px-4">
            <div class="relative group">
                <button class="text-white hover:bg-blue-700 w-full px-4 py-2 rounded-md flex items-center justify-between">
                    <div class="flex items-center" :class="open ? '' : 'justify-center'">
                        <i class="fas fa-user-circle text-xl"></i> <!-- User Icon -->
                        <span :class="open ? '' : 'hidden'">User Name</span>
                    </div>
                    <!-- Dropdown arrow -->
                    <i class="fas fa-chevron-up ml-2 transition-transform transform group-hover:rotate-180" :class="open ? '' : 'hidden'"></i>
                </button>

                <!-- Dropdown Menu that goes up -->
                <div class="absolute left-0 bottom-full mb-2 w-full bg-gray-700 text-white rounded-md shadow-lg hidden group-focus-within:block z-10">
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

    <!-- Main Content Area -->
    <div :class="open ? 'ml-64' : 'ml-16'" class="flex-1 p-6 transition-all duration-300 ease-in-out">
        @yield('content') <!-- Only the main content should be rendered here -->
    </div>
</div>
