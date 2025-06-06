<aside :class="isSidebarOpen ? 'w-64' : 'w-16'"
       class="bg-white dark:bg-gray-900 flex flex-col border-r border-gray-400 h-screen fixed transition-all duration-250 ease-in-out">
    <!-- Sidebar Title & Toggle -->
    <div class="flex flex-col flex-1 space-y-4">
        <button
            @click="isSidebarOpen = !isSidebarOpen"
            :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
            class="flex items-center p-4 text-lg font-bold w-full hover:bg-gray-200 dark:hover:bg-gray-600 border-b border-gray-400"
        >
            <span :class="isSidebarOpen ? '' : 'hidden'">Appointment System</span>
            <i :class="isSidebarOpen ? 'fas fa-arrow-left' : 'fas fa-bars'"></i>
        </button>

        <!-- Navigation Links -->
        <ul class="space-y-2">
            <!-- Home -->
            <li>
                <a
                    href="/dashboard"
                    :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                    class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-b border-gray-400 block px-4 w-full"
                >
                    <i class="fas fa-house text-lg"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Home</span>
                </a>
            </li>

            <!-- Appointments -->
            <li>
                <a href="#"
                   @click.prevent="isDropdownOpen = !isDropdownOpen"
                   :class="isSidebarOpen ? 'px-4 py-2 justify-between space-x-4' : 'justify-center p-4'"
                   class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-b border-gray-400 block px-4 w-full"
                >
                    <div class="flex items-center justify-between w-full">
                        <div>
                            <i :class="isSidebarOpen ? 'mr-4' : 'mr-2'" class="fas fa-calendar-check text-lg"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">Appointments</span>
                        </div>

                        <i
                            :class="[isDropdownOpen ? 'rotate-180' : '', isSidebarOpen ? 'ml-4' : '']"
                            class="fas fa-chevron-down text-sm transition-transform duration-300"
                        >
                        </i>
                    </div>
                </a>

                <ul x-show="isDropdownOpen" x-transition class="mt-2 space-y-1">
                    <li>
                        <a href="/dashboard/appointments"
                           :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                           class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-b border-gray-400 block px-4 w-full"
                        >
                            <i :class="isSidebarOpen ? 'ml-4' : ''" class="fas fa-eye"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">View Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard/appointments/create-step-one"
                           :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                           class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-b border-gray-400 block px-4 w-full"
                        >
                            <i :class="isSidebarOpen ? 'ml-4' : ''" class="fas fa-plus-circle"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">Create Appointment</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Admin -->
            {{-- @if (Auth::user()->admin)
                <li>
                    <a href="/dashboard/admin"
                       :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                       class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-b border-gray-400 block px-4 w-full"
                    >
                        <i class="fas fa-cogs text-lg"></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Admin</span>
                    </a>
                </li>
            @endif --}}
        </ul>
    </div>

    <!-- User Profile -->
    <div class="relative group">
        <a href="#"
           @click.prevent="isUserSidebarOpen = !isUserSidebarOpen"
           :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
           class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-t border-gray-400 block p-4 w-full"
        >
            <div class="flex items-center w-full">
                <i class="fas fa-user-circle text-lg mr-4"></i>
                <span :class="isSidebarOpen ? '' : 'hidden'">{{ Auth::user()->name ?? 'Guest' }}</span>
            </div>

            <i :class="isUserSidebarOpen ? 'rotate-180' : ''"
               class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
        </a>

        <ul x-show="isUserSidebarOpen" x-transition
            class="absolute left-0 bottom-full w-full z-10">
            <li>
                <a href="{{ route('dashboard') }}"
                   :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                   class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-t border-gray-400 block px-4 w-full"

                >
                    <i :class="isSidebarOpen ? 'ml-4' : ''" class="fas fa-user"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('billing') }}"
                   :class="isSidebarOpen ? 'px-4 py-2 space-x-4' : 'justify-center p-4'"
                   class="flex items-center hover:bg-gray-200 dark:hover:bg-gray-600 border-t border-gray-400 block px-4 w-full"
                >
                    <i :class="isSidebarOpen ? 'ml-4' : ''" class="fas fa-shopping-cart"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Billing</span>
                </a>
            </li>
            <li>
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="flex items-center px-4 py-2 w-full hover:bg-blue-700"
                >
                    @csrf
                    <button
                        type="submit"
                        :class="isSidebarOpen ? '' : 'text-center'"
                        class="flex items-center w-full"
                    >
                        <i
                            class="fas fa-sign-out-alt"
                            :class="isSidebarOpen ? 'mr-2' : ''"
                        ></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
