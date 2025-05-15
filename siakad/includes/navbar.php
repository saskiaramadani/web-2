<nav class="sticky top-0 z-40 bg-[#3c8dbc] border-b border-[#367fa9] shadow-md" x-data="{ 
        userMenuOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true',
        toggleUserMenu() {
            this.userMenuOpen = !this.userMenuOpen;
        },
        closeUserMenu() {
            this.userMenuOpen = false;
        },
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            
            // Apply dark mode to body instead of documentElement
            if (this.darkMode) {
                document.body.classList.add('dark');
            } else {
                document.body.classList.remove('dark');
            }
        }
    }" x-init="
        // Initialize dark mode on page load (applying to body)
        if (darkMode) {
            document.body.classList.add('dark');
        }
    ">
    <div class="max-w-full mx-auto">
        <div class="flex justify-between h-14">
            <!-- Left side - Logo and Toggle -->
            <div class="flex items-center">
                <!-- Mobile Menu Toggle Button -->
                <button @click="$store.layout.sidebarOpen = !$store.layout.sidebarOpen"
                    class="px-3 h-14 flex items-center justify-center text-white hover:bg-[#367fa9] border-r border-[#367fa9] lg:border-none transition-colors duration-150">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>


                <!-- Page Title -->
                <div class="flex items-center px-3 h-14 border-l border-[#367fa9]">
                    <h1 class="text-base font-medium text-white">
                        <?= isset($pageTitle) ? $pageTitle : 'Dashboard' ?>
                    </h1>
                </div>
            </div>

            <!-- Right side - User Profile & Actions -->
            <div class="flex items-center">
                <!-- User Dropdown - AdminLTE Style -->
                <div class="relative" @click.away="userMenuOpen = false">
                    <button @click="toggleUserMenu()"
                        class="h-14 px-3 flex items-center border-l border-[#367fa9] text-white hover:bg-[#367fa9] transition-colors duration-150">
                        <!-- User Avatar -->
                        <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center text-white mr-2">
                            <!-- User initial -->
                            <?= isset($_SESSION['user_name']) ? substr($_SESSION['user_name'], 0, 1) : 'U' ?>
                        </div>
                        <span class="hidden md:block text-sm font-medium">
                            <?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User' ?>
                        </span>
                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" :class="{ 'rotate-180 transform': userMenuOpen }">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div x-cloak x-show="userMenuOpen"
                        class="origin-top-right absolute right-0 mt-0 w-48 bg-white shadow-lg z-30"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">

                        <!-- User info section -->
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-100">
                            <p class="text-sm font-medium text-gray-900">
                                <?= isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User' ?>
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                <?= isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'user@example.com' ?>
                            </p>
                        </div>

                        <!-- Menu items -->
                        <div class="py-1">
                            <a href="<?= APP_URL ?>/profile.php"
                                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-[#3c8dbc]">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-[#3c8dbc]"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile
                            </a>
                        </div>

                        <!-- Logout section -->
                        <div class="py-1 border-t border-gray-200">
                            <a href="<?= APP_URL ?>/logout.php"
                                class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600">
                                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-red-600"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Sign out
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>