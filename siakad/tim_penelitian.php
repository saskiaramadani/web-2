<?php
require_once './config/app.php';
require_once './helpers/functions.php';
require_once './helpers/layout.php';
require_once './models/Database.php';
require_once './models/TimPenelitian.php';
require_once './models/Penelitian.php';
require_once './models/Dosen.php';
require_once './includes/auth.php';

// Authentication check
requireLogin();

// Initialize models
$timPenelitianModel = new TimPenelitian();
$dosenModel = new Dosen();

// Initialize variables
$activePenelitianId = null;
$activePenelitian = null;
$searchTerm = '';
$selectedMembers = [];
$memberRoles = [];
$filteredDosen = [];

// Get active penelitian
if (isset($_GET['penelitian_id'])) {
    $activePenelitianId = (int) $_GET['penelitian_id'];
    $activePenelitian = $timPenelitianModel->getPenelitianById($activePenelitianId);

    // Load team members
    if ($activePenelitian && isset($activePenelitian['tim_members'])) {
        foreach ($activePenelitian['tim_members'] as $member) {
            $selectedMembers[(int)$member['dosen_id']] = true;
            $memberRoles[(int)$member['dosen_id']] = $member['peran'] ?? 'Anggota';
        }
    }
}

// Handle search term
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get data
$penelitianList = $timPenelitianModel->getAll();
$allDosen = $dosenModel->getAll();
// debug($penelitianList);

// Filter dosen based on search term
$filteredDosen = $allDosen;
if (!empty($searchTerm)) {
    $filteredDosen = array_filter($allDosen, function ($dosen) use ($searchTerm) {
        $searchLower = strtolower($searchTerm);
        return (
            strpos(strtolower($dosen['nama'] ?? ''), $searchLower) !== false ||
            strpos(strtolower($dosen['nidn'] ?? ''), $searchLower) !== false ||
            strpos(strtolower($dosen['gelar_depan'] ?? ''), $searchLower) !== false ||
            strpos(strtolower($dosen['gelar_belakang'] ?? ''), $searchLower) !== false
        );
    });
}

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $actionType = $_POST['action'];
        $result = false;
        $message = '';

        switch ($actionType) {
            case 'update_members':
                // Process submitted members
                $penelitianId = (int) $_POST['penelitian_id'];
                $anggotaData = [];

                if (!empty($_POST['dosen']) && is_array($_POST['dosen'])) {
                    foreach ($_POST['dosen'] as $dosenId) {
                        $dosenId = (int) $dosenId;
                        $peran = $_POST['peran'][$dosenId] ?? 'Anggota';
                        $anggotaData[$dosenId] = $peran;
                    }
                }

                $result = $timPenelitianModel->updateTimAnggota($penelitianId, $anggotaData);
                $message = $result ? 'Tim penelitian berhasil diperbarui' : 'Gagal memperbarui tim penelitian';
                break;

            case 'select_all':
                // Handle "select all" action directly
                foreach ($filteredDosen as $dosen) {
                    $selectedMembers[$dosen['id']] = true;
                    if (!isset($memberRoles[$dosen['id']])) {
                        $memberRoles[$dosen['id']] = 'Anggota';
                    }
                }
                // Return to same page without redirect
                break;

            case 'deselect_all':
                // Handle "deselect all" action directly
                $selectedMembers = [];
                $memberRoles = [];
                // Return to same page without redirect
                break;
        }

        if ($message) {
            setFlashMessage($message, $result ? 'success' : 'danger');

            // Redirect only for successful operations that change data
            if ($actionType === 'update_members') {
                header('Location: tim_penelitian.php');
                exit;
            }
        }
    }
}

// Helper functions
function getFullName($dosen)
{
    if (!$dosen)
        return '';

    $name = '';
    if (!empty($dosen['gelar_depan'])) {
        $name .= $dosen['gelar_depan'] . ' ';
    }
    $name .= $dosen['nama'] ?? '';
    if (!empty($dosen['gelar_belakang'])) {
        $name .= ', ' . $dosen['gelar_belakang'];
    }
    return $name;
}

function getRoleColorClass($role)
{
    $roleColors = [
        'Ketua' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
        'Wakil Ketua' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
        'Sekretaris' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
        'Bendahara' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        'Advisor' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
    ];

    return $roleColors[$role] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
}


// Start output buffering
ob_start();
?>

<div class="space-y-6">
    <!-- Page Header -->
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg aria-hidden="true" class="w-6 h-6 text-blue-600 dark:text-blue-400"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Tim Penelitian
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola tim penelitian dan anggota tim</p>
        </div>
        <a href="penelitian.php"
            class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm hover:shadow">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Penelitian
        </a>
    </header>

    <!-- Main Content -->
    <section
        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <?php if (empty($penelitianList)): ?>
            <!-- Empty state -->
            <div class="p-8 text-center">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 dark:bg-blue-900/30 dark:text-blue-400 mb-4">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Penelitian</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    Belum ada data penelitian yang tersedia. Silakan tambahkan penelitian baru.
                </p>
                <a href="penelitian.php"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Penelitian Baru
                </a>
            </div>
        <?php else: ?>
            <!-- Research list table -->
            <div class="overflow-x-auto">
                <table id="penelitianTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Judul Penelitian</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tahun Ajaran</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Bidang Ilmu</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Anggota Tim</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($penelitianList as $penelitian): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        <?= htmlspecialchars($penelitian['judul']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <?php if (!empty($penelitian['mulai']) && !empty($penelitian['akhir'])): ?>
                                            <span class="inline-flex items-center">
                                                <svg class="w-3 h-3 mr-1 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <?= date('d M Y', strtotime($penelitian['mulai'])) ?> -
                                                <?= date('d M Y', strtotime($penelitian['akhir'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="italic">Tanggal belum ditetapkan</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <?= htmlspecialchars($penelitian['tahun_ajaran'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        <?= htmlspecialchars($penelitian['bidang_ilmu_nama'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                                        <?= (isset($penelitian['jumlah_anggota']) && $penelitian['jumlah_anggota'] > 0)
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'
                                            : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400' ?>">
                                        <?= isset($penelitian['jumlah_anggota']) ? $penelitian['jumlah_anggota'] : 0 ?> anggota
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium whitespace-nowrap">
                                    <a href="tim_penelitian.php?penelitian_id=<?= $penelitian['id'] ?>"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                                        <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Kelola Tim
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($activePenelitian): ?>
        <!-- Team Management Form -->
        <section
            class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mt-8">
            <form id="teamForm" method="POST" action="tim_penelitian.php">
                <input type="hidden" name="action" value="update_members">
                <input type="hidden" name="penelitian_id" value="<?= $activePenelitianId ?>">

                <!-- Form Header -->
                <header class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <!-- Header content remains the same -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                Kelola Tim Penelitian
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                <svg class="w-4 h-4 mr-1.5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span><?= htmlspecialchars($activePenelitian['judul'] ?? '') ?></span>
                            </p>
                        </div>
                        <a href="tim_penelitian.php"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <span class="sr-only">Tutup</span>
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    </div>
                </header>

                <div class="px-6 py-4">
                    <!-- Member selection controls -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                        <div>
                            <h4 class="text-base font-medium text-gray-900 dark:text-white flex items-center">
                                <svg class="w-5 h-5 mr-1.5 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z">
                                    </path>
                                </svg>
                                Anggota Tim Penelitian
                            </h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                                <span
                                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full dark:bg-blue-900/30 dark:text-blue-400">
                                    <?= count($selectedMembers) ?>
                                </span>
                                <span>anggota dipilih dari total <?= count($allDosen) ?> dosen</span>
                            </p>
                        </div>
                    </div>

                    <!-- Member selection area -->
                    <div class="max-h-[450px] overflow-y-auto px-0.5">
                        <?php if (empty($filteredDosen)): ?>
                            <!-- Empty search results state -->
                            <div class="py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada hasil</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tidak ada dosen yang sesuai dengan
                                    pencarian</p>
                                <div class="mt-4">
                                    <a href="tim_penelitian.php?penelitian_id=<?= $activePenelitianId ?>"
                                        class="inline-flex items-center rounded-md bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Hapus pencarian
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Member cards -->
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 list-none">
                                <?php foreach ($filteredDosen as $dosen):
                                    $dosenId = (int) $dosen['id'];
                                    $isSelected = isset($selectedMembers[$dosenId]);
                                    $currentRole = $isSelected ? ($memberRoles[$dosenId] ?? 'Anggota') : 'Anggota';
                                    
                                    ?>
                                    <li>
                                        <!-- Card container -->
                                        <article
                                            class="border rounded-md overflow-hidden transition-all duration-200 <?= $isSelected ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-900/5 dark:border-blue-700 shadow-sm' : 'border-gray-200 dark:border-gray-700' ?>">
                                            <!-- Header section -->
                                            <div class="p-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex-shrink-0">
                                                        <input type="checkbox" id="member-<?= $dosenId ?>" name="dosen[]"
                                                            value="<?= $dosenId ?>" <?= $isSelected ? 'checked' : '' ?>
                                                            onchange="toggleRoleVisibility(this)"
                                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </div>

                                                    <!-- Member info -->
                                                    <div class="min-w-0 flex-1">
                                                        <label for="member-<?= $dosenId ?>"
                                                            class="text-left w-full px-1 py-0.5 cursor-pointer space-y-1">
                                                            <p class="font-medium text-sm text-gray-900 dark:text-white truncate">
                                                                <?= htmlspecialchars(getFullName($dosen)) ?>
                                                            </p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                <?= htmlspecialchars($dosen['nidn'] ?? '-') ?>
                                                            </p>
                                                        </label>
                                                    </div>

                                                    <!-- Role badge -->
                                                    <?php if ($isSelected): ?>
                                                        <div class="ml-1 role-badge">
                                                            <span
                                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= getRoleColorClass($currentRole) ?>">
                                                                <?= htmlspecialchars($currentRole) ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Role selection panel -->
                                            <div id="role-panel-<?= $dosenId ?>"
                                                class="role-panel border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 p-3"
                                                style="<?= $isSelected ? '' : 'display: none;' ?>">
                                                <div class="flex items-center">
                                                    <label for="role-<?= $dosenId ?>"
                                                        class="text-xs font-medium text-gray-700 dark:text-gray-300 mr-2">
                                                        Peran:
                                                    </label>
                                                    <div class="relative flex-1">
                                                        <select id="role-<?= $dosenId ?>" name="peran[<?= $dosenId ?>]"
                                                            onchange="updateRoleBadge(this, <?= $dosenId ?>)"
                                                            class="w-full text-sm border border-gray-300 dark:border-gray-600 rounded-md py-1.5 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white appearance-none">
                                                            <?php
                                                            $roles = ['Anggota', 'Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara', 'Advisor'];
                                                            foreach ($roles as $role):
                                                                ?>
                                                                <option value="<?= $role ?>" <?= ($currentRole === $role) ? 'selected' : '' ?>>
                                                                    <?= $role ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <div
                                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500 dark:text-gray-400">
                                                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form footer -->
                <footer
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end border-t border-gray-200 dark:border-gray-600 gap-3">
                    <a href="tim_penelitian.php"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </footer>
            </form>
        </section>
    <?php endif; ?>
</div>

<script>    // Function to toggle role panel visibility
    function toggleRoleVisibility(checkbox) {
        const dosenId = checkbox.value;
        const rolePanel = document.getElementById(`role-panel-${dosenId}`);
        const article = checkbox.closest('article');
        const roleBadgeContainer = article.querySelector('.role-badge');

        if (checkbox.checked) {
            // Show role panel when checked
            rolePanel.style.display = 'block';
            article.classList.add('border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-900/5', 'dark:border-blue-700', 'shadow-sm');
            article.classList.remove('border-gray-200', 'dark:border-gray-700');

            // Create role badge if it doesn't exist
            if (!roleBadgeContainer) {
                const select = document.getElementById(`role-${dosenId}`);
                const selectedRole = select.options[select.selectedIndex].text;
                const badgeDiv = document.createElement('div');
                badgeDiv.className = 'ml-1 role-badge';

                const roleClass = getRoleColorClass(selectedRole);
                badgeDiv.innerHTML = `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${roleClass}">${selectedRole}</span>`;

                const memberInfo = article.querySelector('.flex.items-center.gap-3');
                memberInfo.appendChild(badgeDiv);
            }
        } else {
            // Hide role panel when unchecked
            rolePanel.style.display = 'none';
            article.classList.remove('border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-900/5', 'dark:border-blue-700', 'shadow-sm');
            article.classList.add('border-gray-200', 'dark:border-gray-700');

            // Remove role badge if it exists
            if (roleBadgeContainer) {
                roleBadgeContainer.remove();
            }
        }
    }

    // Function to update role badge when role changes
    function updateRoleBadge(select, dosenId) {
        const selectedRole = select.options[select.selectedIndex].text;
        const article = select.closest('article');
        const roleBadge = article.querySelector('.role-badge span');

        if (roleBadge) {
            // Update existing badge class and text
            roleBadge.className = `inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${getRoleColorClass(selectedRole)}`;
            roleBadge.textContent = selectedRole;
        }
    }

    // Function to get role color class (must match PHP version)
    function getRoleColorClass(role) {
        const roleColors = {
            'Ketua': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'Wakil Ketua': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'Sekretaris': 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'Bendahara': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'Advisor': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
        };

        return roleColors[role] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }

    // Add this at the end of your existing script
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all checkboxes and role panels on page load
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="dosen[]"]');
        
        checkboxes.forEach(function(checkbox) {
            // If the checkbox is checked when the page loads, make sure the role panel is visible
            if (checkbox.checked) {
                const dosenId = checkbox.value;
                const rolePanel = document.getElementById(`role-panel-${dosenId}`);
                const article = checkbox.closest('article');
                
                if (rolePanel) {
                    rolePanel.style.display = 'block';
                }
                
                article.classList.add('border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-900/5', 'dark:border-blue-700', 'shadow-sm');
                article.classList.remove('border-gray-200', 'dark:border-gray-700');
            }
        });
    });
</script>

<?php
// Capture the content
$content = ob_get_clean();

// Define page variables
$pageVars = [
    'title' => 'Tim Penelitian - Sistem Manajemen Kegiatan Dosen',
    'description' => 'Manajemen tim penelitian dosen',
    'pageTitle' => 'Tim Penelitian',
];

// Render the layout with the content
echo renderContent($content, 'main', $pageVars);
?>