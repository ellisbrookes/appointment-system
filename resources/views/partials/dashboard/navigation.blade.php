<aside 
    x-data="{
        isSidebarOpen: true,
        isDropdownOpen: false,
        isCompanyDropdownOpen: false,
        isUserSidebarOpen: false
    }"
    :class="isSidebarOpen ? 'w-64' : 'w-16'"
    class="dark:bg-gray-900 flex flex-col dark:text-white border-r border-gray-400 h-screen fixed transition-all duration-250 ease-in-out"
>
    <!-- Sidebar Title & Toggle -->
    <div class="flex flex-col flex-1 space-y-4">
        <button
            @click="isSidebarOpen = !isSidebarOpen"
            :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
            class="flex items-center p-4 text-lg font-bold w-full hover:bg-blue-500 dark:hover:bg-blue-700 border-b border-gray-400"
        >
            <span :class="isSidebarOpen ? '' : 'hidden'">Appointment System</span>
            <i :class="isSidebarOpen ? 'fas fa-arrow-left' : 'fas fa-bars'"></i>
        </button>

        <!-- Navigation Links -->
        <ul :class="isSidebarOpen ? 'px-4' : ''" class="space-y-2">

            <!-- Home -->
            <li>
                <a href="/dashboard"
                   :class="isSidebarOpen ? 'rounded-md px-4 p-2' : 'justify-center rounded-none p-4'"
                   class="flex items-center w-full space-x-4 hover:bg-blue-500 dark:hover:bg-blue-700"
                >
                    <i class="fas fa-house text-lg"></i>
                    <span :class="isSidebarOpen ? '' : 'hidden'">Home</span>
                </a>
            </li>

            <!-- Appointments -->
            <li>
                <a href="#" 
                   @click.prevent="isDropdownOpen = !isDropdownOpen"
                   :class="isSidebarOpen ? 'rounded-md px-4 py-2 justify-between' : 'justify-center rounded-none p-4'"
                   class="flex items-center space-x-2 hover:bg-blue-500 dark:hover:bg-blue-700"
                >
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check text-lg mr-4"></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Appointments</span>
                    </div>
                    <i :class="isDropdownOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                </a>

                <ul x-show="isDropdownOpen" x-transition class="mt-2 space-y-1">
                    <li>
                        <a href="/dashboard/appointments"
                           :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                           class="flex items-center space-x-4 hover:bg-blue-500 dark:hover:bg-blue-700"
                        >
                            <i class="fas fa-eye"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">View Appointments</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard/appointments/create-step-one"
                           :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                           class="flex items-center space-x-4 hover:bg-blue-500 dark:hover:bg-blue-700"
                        >
                            <i class="fas fa-plus-circle"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">Create Appointment</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Company Dropdown -->
            <li>
                <a href="#"
                   @click.prevent="isCompanyDropdownOpen = !isCompanyDropdownOpen"
                   :class="isSidebarOpen ? 'rounded-md px-4 py-2 justify-between' : 'justify-center rounded-none p-4'"
                   class="flex items-center space-x-2 hover:bg-blue-500 dark:hover:bg-blue-700"
                >
                    <div class="flex items-center">
                        <i class="fas fa-building text-lg mr-4"></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Company</span>
                    </div>
                    <i :class="isCompanyDropdownOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
                </a>

                <ul x-show="isCompanyDropdownOpen" x-transition class="mt-2 space-y-1">
                    <li>
                        <a href="/dashboard/company"
                           :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                           class="flex items-center space-x-4 hover:bg-blue-500 dark:hover:bg-blue-700"
                        >
                            <i class="fas fa-eye"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">View Company</span>
                        </a>
                    </li>
                    <li>
                        <a href="/dashboard/company/create"
                           :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                           class="flex items-center space-x-4 hover:bg-blue-500 dark:hover:bg-blue-700"
                        >
                            <i class="fas fa-plus-circle"></i>
                            <span :class="isSidebarOpen ? '' : 'hidden'">Create Company</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Admin -->
            @if (Auth::user()->admin)
                <li>
                    <a href="/dashboard/admin"
                       :class="isSidebarOpen ? 'rounded-md px-4 py-2' : 'justify-center rounded-none p-4'"
                       class="flex items-center w-full space-x-4 hover:bg-blue-500 dark:hover:bg-blue-700"
                    >
                        <i class="fas fa-cogs text-lg"></i>
                        <span :class="isSidebarOpen ? '' : 'hidden'">Admin</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <!-- User Profile -->
    <div class="relative group">
        <a href="#" 
           @click.prevent="isUserSidebarOpen = !isUserSidebarOpen"
           :class="isSidebarOpen ? 'justify-between' : 'justify-center'"
           class="flex items-center p-4 w-full hover:bg-blue-500 dark:hover:bg-blue-700 border-t border-gray-400"
        >
            <div class="flex items-center">
                <i class="fas fa-user-circle text-lg mr-4"></i>
                <span :class="isSidebarOpen ? '' : 'hidden'">{{ Auth::user()->name ?? 'Guest' }}</span>
            </div>
            <i :class="isUserSidebarOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
        </a>

        <ul x-show="isUserSidebarOpen" x-transition class="absolute left-0 bottom-full w-full bg-gray-700 z-10">
            <li>
                <a href="{{ route('dashboard.profile.edit') }}"
                   class="flex items-center px-4 py-2 hover:bg-blue-500 dark:hover:bg-blue-700"
                >
                    <i class="fas fa-user mr-2"></i>
                    <span>Profile</span>
                </a>
            </li>
            <li>
                <a href="{{ route('billing') }}"
                   class="flex items-center px-4 py-2 hover:bg-blue-500 dark:hover:bg-blue-700"
                >
                    <i class="fas fa-shopping-cart mr-2"></i>
                    <span>Billing</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center w-full px-4 py-2 hover:bg-blue-500 dark:hover:bg-blue-700"
                    >
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
