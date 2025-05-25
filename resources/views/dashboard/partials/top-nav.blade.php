<nav class="flex justify-between bg-white dark:bg-gray-900 border-b transition-all duration-250 ease-in-out">
  <!-- Sidebar Title & Toggle -->
  <div class="flex">
    <a href="/dashboard" class="text-lg py-4 px-6 hover:bg-gray-200 dark:hover:bg-gray-600 border-x">
      Appointment System
    </a>

    <!-- Navigation Links -->
    <div class="flex">
      <!-- Home -->
      <a href="/dashboard" class="flex items-center justify-center space-x-4 py-4 px-6 hover:bg-gray-200 dark:hover:bg-gray-600 border-r block">
        <i class="fas fa-house text-lg"></i>
        <span>Home</span>
      </a>

      <!-- Appointments -->
       <div class="relative">
        <a href="#" @click.prevent="isDropdownOpen = !isDropdownOpen" class="relative flex items-center justify-center space-x-4 py-4 px-6 hover:bg-gray-200 dark:hover:bg-gray-600 border-r block">
          <div class="flex items-center space-x-4">
            <i class="fas fa-calendar-check text-lg"></i>
            <span>Appointments</span>
          </div>

          <i :class="isDropdownOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
        </a>


        <ul x-show="isDropdownOpen" x-transition class="absolute bg-gray-900 w-full left-0 border z-10">
          <li>
            <a href="/dashboard/appointments" class="flex items-center p-4 space-x-4 hover:bg-gray-200 dark:hover:bg-gray-600 border-b block px-4">
              <i class="fas fa-eye"></i>
              <span>View Appointments</span>
            </a>
          </li>
          <li>
            <a href="/dashboard/appointments/create-step-one" class="flex items-center p-4 space-x-4 hover:bg-gray-200 dark:hover:bg-gray-600 border-b block px-4">
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
    <a href="#" @click.prevent="isUserSidebarOpen = !isUserSidebarOpen" class="relative flex items-center justify-center space-x-4 py-4 px-6 hover:bg-gray-200 dark:hover:bg-gray-600 border-x block">
      <div class="flex items-center space-x-4">
        <i class="fas fa-user-circle text-lg"></i>
        <span>{{ Auth::user()->name ?? 'Guest' }}</span>
      </div>

      <i :class="isUserSidebarOpen ? 'rotate-180' : ''" class="fas fa-chevron-down text-sm transition-transform duration-300"></i>
    </a>

    <ul x-show="isUserSidebarOpen" x-transition class="absolute bg-gray-900 w-full left-0 border z-10">
      <li>
        <a href="{{ route('dashboard.profile.edit') }}" class="flex items-center p-4 space-x-4 hover:bg-gray-200 dark:hover:bg-gray-600 border-t block px-4">
          <i class="fas fa-user"></i>
          <span>Profile</span>
        </a>
      </li>
      <li>
        <a href="{{ route('billing') }}" class="flex items-center p-4 space-x-4 hover:bg-gray-200 dark:hover:bg-gray-600 border-t block px-4">
          <i class="fas fa-shopping-cart"></i>
          <span>Billing</span>
        </a>
      </li>
      <li>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="flex items-center w-full p-4 space-x-4 hover:bg-gray-200 dark:hover:bg-gray-600 border-t block px-4">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
          </button>
        </form>
      </li>
    </ul>
  </div>
</nav>
