<aside class="flex flex-col transition-all duration-300 bg-[#343a40] shadow-sm z-40" x-data="{ 
        isMobile: window.innerWidth < 1024,
        openSections: {
            main: true,
            academic: true,
            activities: true,
            research: true
        },
        toggleSection(section) {
            this.openSections[section] = !this.openSections[section];
        }
    }" :class="{
        'fixed top-0 left-0 w-64 h-screen border-r border-gray-700': !isMobile,
        'fixed top-16 left-0 right-0 h-screen border-r border-gray-700': isMobile && $store.layout.sidebarOpen,
        'hidden': isMobile && !$store.layout.sidebarOpen
    }" @resize.window="
        isMobile = window.innerWidth < 1024;
        if (!isMobile) $store.layout.sidebarOpen = false;
    ">

    <div class="flex items-center h-16 px-4 border-b border-gray-700" x-show="!isMobile">
        <div class="flex items-center">
            <div class="flex items-center justify-center">
                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M9.37,5.51C9.19,5.33,9.19,5.03,9.37,4.83,9.53,4.67,9.84,4.67,10.02,4.83L12.70,7.51,13.82,3.42C13.88,3.21,14.08,3.06,14.29,3.07,14.51,3.06,14.71,3.21,14.77,3.42L15.89,7.51,18.57,4.83C18.75,4.65,19.05,4.65,19.23,4.83,19.41,5.01,19.41,5.33,19.23,5.51L16.53,8.19,20.65,9.33C20.85,9.39,21.00,9.58,21.01,9.80,21.01,10.01,20.85,10.21,20.65,10.27L16.55,11.39,19.23,14.07C19.41,14.25,19.41,14.55,19.23,14.73,19.05,14.91,18.75,14.91,18.57,14.73L15.89,12.05,14.77,16.15C14.71,16.35,14.51,16.50,14.3,16.49,14.08,16.5,13.88,16.35,13.82,16.15L12.7,12.03,10.02,14.73C9.84,14.91,9.54,14.91,9.36,14.73,9.18,14.55,9.18,14.25,9.36,14.07L12.04,11.39,7.94,10.27C7.74,10.21,7.59,10.01,7.59,9.80,7.58,9.58,7.74,9.38,7.94,9.32L12.04,8.20L9.37,5.51Z">
                    </path>
                </svg>
            </div>
            <span class="ml-2 font-medium text-white text-lg">SIAKAD</span>
        </div>
    </div>

    <!-- Mobile Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-700 bg-[#343a40]" x-show="isMobile">
        <div class="flex items-center justify-center">
            <div class="flex items-center justify-center">
                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="currentColor">
                    <path
                        d="M9.37,5.51C9.19,5.33,9.19,5.03,9.37,4.83,9.53,4.67,9.84,4.67,10.02,4.83L12.70,7.51,13.82,3.42C13.88,3.21,14.08,3.06,14.29,3.07,14.51,3.06,14.71,3.21,14.77,3.42L15.89,7.51,18.57,4.83C18.75,4.65,19.05,4.65,19.23,4.83,19.41,5.01,19.41,5.33,19.23,5.51L16.53,8.19,20.65,9.33C20.85,9.39,21.00,9.58,21.01,9.80,21.01,10.01,20.85,10.21,20.65,10.27L16.55,11.39,19.23,14.07C19.41,14.25,19.41,14.55,19.23,14.73,19.05,14.91,18.75,14.91,18.57,14.73L15.89,12.05,14.77,16.15C14.71,16.35,14.51,16.50,14.3,16.49,14.08,16.5,13.88,16.35,13.82,16.15L12.7,12.03,10.02,14.73C9.84,14.91,9.54,14.91,9.36,14.73,9.18,14.55,9.18,14.25,9.36,14.07L12.04,11.39,7.94,10.27C7.74,10.21,7.59,10.01,7.59,9.80,7.58,9.58,7.74,9.38,7.94,9.32L12.04,8.20L9.37,5.51Z">
                    </path>
                </svg>
            </div>
            <span class="ml-2 font-medium text-white text-lg">SIAKAD</span>
        </div>
        <button @click="$store.layout.sidebarOpen = false" class="p-2 rounded-md text-gray-400 hover:text-gray-200">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Sidebar Content (Scrollable) -->
    <div class="flex-1 overflow-y-auto py-2">
        <!-- Navigation Menu (works for both mobile and desktop) -->
        <nav class="mt-1 px-2">
            <!-- Main Menu -->
            <div class="mb-3">
                <h5 class="px-3 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                    Menu Utama
                </h5>
                <ul class="nav flex-column" x-show="openSections.main" x-collapse>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/dashboard.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/dashboard.php') || $_SERVER['REQUEST_URI'] === '/dashboard.php' ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="truncate">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/profile.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/profile.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="truncate">Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Academic Groups -->
            <div class="mb-3">
                <h5 class="px-3 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                    Data Akademik
                </h5>
                <ul class="nav flex-column" x-show="openSections.academic" x-collapse>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/dosen.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/dosen.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="truncate">Dosen</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/prodi.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/prodi.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="truncate">Program Studi</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/bidang_ilmu.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/bidang_ilmu.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="truncate">Bidang Ilmu</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Activities Group -->
            <div class="mb-3">
                <h5 class="px-3 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                    Kegiatan
                </h5>
                <ul class="nav flex-column" x-show="openSections.activities" x-collapse>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/kegiatan.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/kegiatan.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="truncate">Data Kegiatan</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/jenis_kegiatan.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/jenis_kegiatan.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="truncate">Jenis Kegiatan</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Research Group -->
            <div class="mb-3">
                <h5 class="px-3 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                    Penelitian
                </h5>
                <ul class="nav flex-column" x-show="openSections.research" x-collapse>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/penelitian.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/penelitian.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="truncate">Data Penelitian</span>
                        </a>
                    </li>
                    <li class="nav-item mb-1">
                        <a href="<?= APP_URL ?>/tim_penelitian.php"
                            class="flex items-center px-3 py-2 text-sm font-medium rounded-sm group transition-colors
                            <?= isCurrentPage('/tim_penelitian.php') ? 'bg-[#3c8dbc]/90 text-white' : 'text-gray-300 hover:bg-[#3c8dbc]/25 hover:text-white' ?>">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="truncate">Tim Penelitian</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Footer with Logout -->
            <div class="pt-2 mt-6 border-t border-gray-700">
                <a href="<?= APP_URL ?>/logout.php"
                    class="flex items-center px-3 py-2 text-sm font-medium text-red-400 hover:bg-red-600/20 hover:text-red-300 rounded-sm">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="truncate">Logout</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

<!-- Mobile Sidebar Overlay -->
<div x-data="{ isMobile: window.innerWidth < 1024 }" x-show="isMobile && $store.layout.sidebarOpen"
    @resize.window="isMobile = window.innerWidth < 1024" @click="$store.layout.sidebarOpen = false"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 z-30">
</div>