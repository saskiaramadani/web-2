<?php
/**
 * Dashboard Page
 * 
 * This is the main dashboard page of the application.
 */

// Include configuration
require_once 'config/app.php';
require_once 'helpers/functions.php';
require_once 'helpers/layout.php';
require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Dosen.php';
require_once 'models/Kegiatan.php';
require_once 'models/Penelitian.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

// Get statistics for dashboard
$dosenModel = new Dosen();
$kegiatanModel = new Kegiatan();
$bidangIlmuModel = new BidangIlmu();
$penelitianModel = new Penelitian();

$totalDosen = $dosenModel->count();
$totalKegiatan = $kegiatanModel->count();
$totalbidangIlmu = $bidangIlmuModel->count();
$totalPenelitian = $penelitianModel->count();

// Get recent activities
$recentActivities = $kegiatanModel->getRecent(5);

// Get activity statistics for chart
$activityStats = $kegiatanModel->getKegiatanStats();
$chartLabels = [];
$chartData = [];

foreach ($activityStats as $stat) {
    $chartLabels[] = $stat['nama'];
    $chartData[] = $stat['jumlah'];
}

// Start output buffering to capture content
ob_start();
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang di Sistem Manajemen Kegiatan Dosen</p>
    </div>
    <div class="mt-3 sm:mt-0 flex space-x-2">
        <span
            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
            <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <?= date('d M Y') ?>
        </span>
    </div>
</div>

<!-- Welcome Card -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-sm mb-6 overflow-hidden">
    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center">
            <div
                class="flex-shrink-0 w-16 h-16 rounded-full bg-blue-100 bg-opacity-25 flex items-center justify-center mb-4 sm:mb-0 sm:mr-6">
                <svg class="w-10 h-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-white dark:text-white">Selamat Datang,
                    <?= getCurrentUserName() ?>!
                </h2>
                <p class="text-blue-100 mt-1 dark:text-white">Akses semua fitur manajemen kegiatan dan penelitian dosen
                    dari dashboard
                    ini.</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Dosen Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Dosen</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?= $totalDosen ?></h3>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
            <div class="bg-blue-600 h-1.5 rounded-full" style="width: 75%"></div>
        </div>
        <div class="mt-3">
            <a href="<?= APP_URL ?>/dosen.php"
                class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline inline-flex items-center">
                Lihat Detail
                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Total Kegiatan Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Kegiatan</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?= $totalKegiatan ?></h3>
            </div>
            <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
            <div class="bg-green-600 h-1.5 rounded-full" style="width: 65%"></div>
        </div>
        <div class="mt-3">
            <a href="<?= APP_URL ?>/kegiatan.php"
                class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline inline-flex items-center">
                Lihat Detail
                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Total Penelitian Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Penelitian</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?= $totalPenelitian ?>
                </h3>
            </div>
            <div class="bg-cyan-100 dark:bg-cyan-900/30 p-3 rounded-lg">
                <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
            <div class="bg-cyan-600 h-1.5 rounded-full" style="width: 50%"></div>
        </div>
        <div class="mt-3">
            <a href="<?= APP_URL ?>/penelitian.php"
                class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline inline-flex items-center">
                Lihat Detail
                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Total Bidang Ilmu Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Bidang Ilmu</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1"><?= $totalbidangIlmu ?></h3>
            </div>
            <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
            <div class="bg-amber-600 h-1.5 rounded-full" style="width: 40%"></div>
        </div>
        <div class="mt-3">
            <a href="<?= APP_URL ?>/bidang_ilmu.php"
                class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline inline-flex items-center">
                Lihat Detail
                <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Main Content Sections -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Recent Activities -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="flex justify-between items-center py-4 px-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-medium text-gray-800 dark:text-white">Kegiatan Terbaru</h2>
            <a href="<?= APP_URL ?>/kegiatan.php"
                class="px-3 py-1.5 text-xs font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700">Lihat
                Semua</a>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr
                            class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th class="px-4 py-3">Nama Kegiatan</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Jenis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if (empty($recentActivities)): ?>
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Belum
                                    ada kegiatan</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentActivities as $activity): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-4 text-sm text-gray-800 dark:text-gray-200">
                                        <?= $activity['nama'] ?>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        <?= date('d M Y', strtotime($activity['tanggal_mulai'])) ?>
                                        <?php if ($activity['tanggal_selesai']): ?>
                                            - <?= date('d M Y', strtotime($activity['tanggal_selesai'])) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <?= $activity['jenis_kegiatan_nama'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Activity Distribution -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="py-4 px-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="font-medium text-gray-800 dark:text-white">Distribusi Kegiatan</h2>
        </div>
        <div class="p-6 flex items-center justify-center h-fit">
            <div class="w-full h-full">
                <canvas id="activityChart" data-labels='<?= json_encode($chartLabels) ?>'
                    data-values='<?= json_encode($chartData) ?>'></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Capture the content -->
<?php
$content = ob_get_clean();

// Define custom page variables
$pageVars = [
    'title' => 'Dashboard - Sistem Manajemen Kegiatan Dosen',
    'description' => 'Dashboard untuk mengelola kegiatan dosen',
    'scripts' => '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("activityChart");
        if (ctx) {
            new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: ' . json_encode($chartLabels) . ',
                    datasets: [{
                        data: ' . json_encode($chartData) . ',
                        backgroundColor: [
                            "#3b82f6", // blue-500
                            "#10b981", // green-500 
                            "#f59e0b", // amber-500
                            "#ef4444", // red-500
                            "#8b5cf6", // violet-500
                            "#06b6d4"  // cyan-500
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        }
    });
    </script>
    '
];

// Render the layout with the content
echo renderContent($content, 'main', $pageVars);
?>