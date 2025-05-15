<?php
/**
 * Main Layout Template
 * This is the main layout template that wraps all content
 */
?>
<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= APP_URL ?>/assets/img/favicon.ico" type="image/x-icon">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1'
                        }
                    }
                }
            }
        }
    </script>

    <!-- DataTables with Tailwind CSS styling -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Tailwind CSS Custom Styles -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">

    <style>
        /* Fixed layout structure */
        body {
            overflow-x: hidden;
        }

        /* Custom styles for DataTables with Tailwind */
        .dataTables_wrapper {
            @apply font-sans text-sm;
        }

        table.dataTable {
            @apply w-full border-collapse;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            @apply py-3 px-4 text-left font-medium text-gray-700 border-b border-gray-200 dark:text-gray-300 dark:border-gray-700;
        }

        table.dataTable tbody tr {
            @apply bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700;
        }

        table.dataTable tbody td {
            @apply py-3 px-4 border-b border-gray-200 dark:border-gray-700;
        }

        .dataTables_length,
        .dataTables_filter,
        .dataTables_info,
        .dataTables_paginate {
            @apply py-3;
        }

        .dataTables_length select,
        .dataTables_filter input {
            @apply px-2 py-1 border border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white;
        }

        /* Smooth transitions */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out, margin 0.3s ease-in-out;
        }

        .fade-transition {
            transition: opacity 0.2s ease-in-out;
        }

        /* Responsive Layout Styles */
        .main-layout {
            min-height: 100vh;
        }

        .content-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-area {
            flex: 1;
            overflow-y: auto;
        }

        /* Sidebar adjustments for desktop */
        @media (min-width: 1024px) {
            .content-wrapper {
                margin-left: 16rem;
                /* w-64 = 16rem */
            }
        }
    </style>


    <!-- Page-specific CSS -->
    <?php if (isset($pageStyles)): ?>
        <?php foreach ($pageStyles as $style): ?>
            <link rel="stylesheet" href="<?= $style ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Additional Styles -->
    <?php if (isset($styles)): ?>
        <?= $styles ?>
    <?php endif; ?>

    <!-- Alpine.js Store for Layout State -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('layout', {
                sidebarOpen: false,
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                }
            });
        });
    </script>
</head>

<body class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">

    <!-- Flash Messages System -->
    <?php
    $flashMessage = getFlashMessage();
    // Clear the message immediately after retrieval to prevent re-display
    clearFlashMessage();
    ?>
    <?php if ($flashMessage): ?>
        <div class="fixed bottom-4 right-4 z-50 w-full max-w-sm" x-data="{ show: true }" x-show="show"
            x-init="setTimeout(() => show = false, 5000)" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border-l-4 <?= $flashMessage['type'] === 'success' ? 'border-green-500' : ($flashMessage['type'] === 'danger' ? 'border-red-500' : ($flashMessage['type'] === 'warning' ? 'border-yellow-500' : 'border-blue-500')) ?>">
                <div class="p-4 flex items-start">
                    <?php if ($flashMessage['type'] === 'success'): ?>
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    <?php elseif ($flashMessage['type'] === 'danger'): ?>
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    <?php elseif ($flashMessage['type'] === 'warning'): ?>
                        <svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    <?php else: ?>
                        <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    <?php endif; ?>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm leading-5 text-gray-700 dark:text-gray-200"><?= $flashMessage['message'] ?></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false"
                            class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Layout with Improved Structure -->
    <div class="main-layout">
        <!-- Sidebar Component -->
        <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper transition-all duration-300 sidebar-transition">
            <!-- Top Navbar -->
            <header
                class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
                <?php include_once __DIR__ . '/../includes/navbar.php'; ?>
            </header>

            <!-- Main Content Area (Scrollable) -->
            <div class="content-area">
                <div class="p-4 md:p-6 lg:p-8">
                    <?= $content ?>
                </div>
            </div>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <?php include_once __DIR__ . '/../includes/footer.php'; ?>
            </footer>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            if (typeof $.fn.DataTable !== 'undefined') {
                $('.datatable').DataTable({
                    responsive: true,
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: {
                            previous: "«",
                            next: "»"
                        }
                    }
                });
            }
        });
    </script>

    <!-- Additional Scripts -->
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
</body>

</html>