<?php
require_once './config/app.php';
require_once './helpers/functions.php';
require_once './helpers/layout.php';
require_once './models/Database.php';
require_once './models/Dosen.php';
require_once './models/Prodi.php';
require_once './includes/auth.php';

// Require login
requireLogin();

// Initialize models
$dosenModel = new Dosen();
$prodiModel = new Prodi();

// Get all prodi for dropdown
$prodiList = $prodiModel->getAll();

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create or Update operation
    if (isset($_POST['action'])) {
        $data = [
            'nidn' => $_POST['nidn'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'email' => $_POST['email'] ?? '',
            'alamat' => $_POST['alamat'] ?? '',
            'telp' => $_POST['telp'] ?? '',
            'gelar_depan' => $_POST['gelar_depan'] ?? null,
            'gelar_belakang' => $_POST['gelar_belakang'] ?? null,
            'jenis_kelamin' => $_POST['jenis_kelamin'] ?? null,
            'tempat_lahir' => $_POST['tempat_lahir'] ?? null,
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? null,
            'tahun_masuk' => $_POST['tahun_masuk'] ? (int) $_POST['tahun_masuk'] : null,
            'prodi_id' => $_POST['prodi_id'] ? (int) $_POST['prodi_id'] : null
        ];

        $action = $_POST['action'];
        $result = false;
        $message = '';

        switch ($action) {
            case 'create':
                $result = $dosenModel->create($data);
                $message = $result !== false ? 'Data dosen berhasil ditambahkan' : 'Gagal menambahkan data dosen';
                break;
            case 'edit':
                $id = (int) $_POST['id'];
                $result = $dosenModel->update($id, $data);
                $message = $result ? 'Data dosen berhasil diperbarui' : 'Gagal memperbarui data dosen';
                break;
            case 'delete':
                $id = (int) $_POST['id'];
                $result = $dosenModel->delete($id);
                $message = $result ? 'Data dosen berhasil dihapus' : 'Gagal menghapus data dosen';
                break;
        }

        setFlashMessage($message, $result ? 'success' : 'danger');
    }

    // Redirect back to the same page to prevent form resubmission
    header('Location: dosen.php');
    exit;
}

// Get all dosen data with their prodi
$dosen = $dosenModel->getAllWithProdi();

// Start output buffering to capture content
ob_start();
?>

<div x-data="{ 
    showModal: false, 
    modalMode: 'create',
    formData: {}
}">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Data Dosen</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manajemen data dosen di lingkungan perguruan tinggi</p>
        </div>
        <button type="button" @click="showModal = true; modalMode = 'create'; formData = {}"
            class="mt-3 sm:mt-0 inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Dosen
        </button>
    </div>

    <!-- Flash Messages -->
    <?php if ($flashMessage = getFlashMessage()): ?>
        <div
            class="mb-6 p-4 rounded-lg flex items-center justify-between 
        <?= $flashMessage['type'] === 'success' ? 'bg-green-100 text-green-700 dark:bg-green-800/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-800/30 dark:text-red-400' ?>">
            <div><?= $flashMessage['message'] ?></div>
            <button type="button" onclick="this.parentElement.remove()" class="text-sm hover:underline">Tutup</button>
        </div>
        <?php clearFlashMessage(); endif; ?>

    <!-- Main Content -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="p-6 overflow-x-auto">
            <table id="dosenTable" class="min-w-full table-auto">
                <thead class="bg-gray-50 dark:bg-gray-700/30">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            NIDN</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Program Studi</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Email</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Telepon</th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (count($dosen) > 0): ?>
                        <?php foreach ($dosen as $d): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                    <?= htmlspecialchars($d['nidn'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3 shadow-sm">
                                            <span
                                                class="text-lg font-bold text-white"><?= substr(htmlspecialchars($d['nama'] ?? '?'), 0, 1) ?></span>
                                        </div>
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-800 dark:text-white">
                                                <?= htmlspecialchars(($d['gelar_depan'] ? $d['gelar_depan'] . '. ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : '')) ?>
                                            </div>
                                            <div class="text-gray-500 dark:text-gray-400">
                                                <?= $d['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($d['jenis_kelamin'] === 'P' ? 'Perempuan' : '-') ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($d['prodi_nama'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($d['email'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($d['telp'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button type="button"
                                            @click="showModal = true; modalMode = 'edit'; formData = <?= htmlspecialchars(json_encode($d)) ?>"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/30 transition-all duration-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                            @click="showModal = true; modalMode = 'delete'; formData = <?= htmlspecialchars(json_encode($d)) ?>"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30 transition-all duration-200"
                                            title="Hapus">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>Tidak ada data dosen yang tersedia.</p>
                                    <button type="button" @click="showModal = true; modalMode = 'create'; formData = {}"
                                        class="mt-4 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 transition-all duration-200">
                                        Tambah Dosen Baru
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Unified Modal -->
    <div x-cloak x-show="showModal" @keydown.escape.window="showModal = false"
        class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
            <!-- Background overlay -->
            <div @click="showModal = false" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"></div>

            <!-- Modal panel -->
            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="POST">
                    <input type="hidden" name="action" :value="modalMode">
                    <input type="hidden" name="id" :value="formData.id" x-if="formData.id">

                    <!-- Content for delete mode -->
                    <template x-if="modalMode === 'delete'">
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                        Konfirmasi Hapus
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400"
                                            x-text="`Apakah Anda yakin ingin menghapus data dosen '${formData.nama}'? Tindakan ini tidak dapat dibatalkan.`">
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Content for create/edit mode -->
                    <template x-if="modalMode !== 'delete'">
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white"
                                    x-text="modalMode === 'edit' ? 'Edit Data Dosen' : 'Tambah Data Dosen'">
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                                    x-text="modalMode === 'edit' ? 'Perbarui informasi dosen melalui form berikut.' : 'Isi form berikut untuk menambahkan data dosen baru.'">
                                </p>
                            </div>

                            <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1">
                                <!-- Basic Info Section -->
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-3">Informasi Dasar</h4>

                                    <!-- NIDN Field -->
                                    <div class="mb-3">
                                        <label for="nidn"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIDN</label>
                                        <input type="text" id="nidn" name="nidn" :value="formData.nidn"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                            required>
                                    </div>

                                    <!-- Name Fields -->
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                                        <div>
                                            <label for="gelar_depan"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gelar
                                                Depan</label>
                                            <input type="text" id="gelar_depan" name="gelar_depan"
                                                :value="formData.gelar_depan"
                                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                                placeholder="Dr">
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="nama"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                                Lengkap <span class="text-red-500">*</span></label>
                                            <input type="text" id="nama" name="nama" :value="formData.nama"
                                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                                required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="gelar_belakang"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gelar
                                            Belakang</label>
                                        <input type="text" id="gelar_belakang" name="gelar_belakang"
                                            :value="formData.gelar_belakang"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                            placeholder="S.Kom., M.Cs.">
                                    </div>

                                    <!-- Program Studi -->
                                    <div>
                                        <label for="prodi_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program
                                            Studi</label>
                                        <select id="prodi_id" name="prodi_id"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                            <option value="">Pilih Program Studi</option>
                                            <?php foreach ($prodiList as $prodi): ?>
                                                <option value="<?= $prodi['id'] ?>"
                                                    x-bind:selected="formData.prodi_id == <?= $prodi['id'] ?>">
                                                    <?= htmlspecialchars($prodi['nama']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Personal Info Section -->
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <h4 class="text-sm font-medium text-gray-500 mb-3">Informasi Personal</h4>

                                    <!-- Jenis Kelamin Field -->
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis
                                            Kelamin</label>
                                        <div class="mt-2 flex items-center space-x-6">
                                            <div class="flex items-center">
                                                <input id="jk-l" name="jenis_kelamin" type="radio" value="L"
                                                    x-bind:checked="formData.jenis_kelamin == 'L'"
                                                    class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <label for="jk-l"
                                                    class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                    Laki-laki
                                                </label>
                                            </div>
                                            <div class="flex items-center">
                                                <input id="jk-p" name="jenis_kelamin" type="radio" value="P"
                                                    x-bind:checked="formData.jenis_kelamin == 'P'"
                                                    class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                <label for="jk-p"
                                                    class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                                    Perempuan
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tempat & Tanggal Lahir -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <label for="tempat_lahir"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat
                                                Lahir</label>
                                            <input type="text" id="tempat_lahir" name="tempat_lahir"
                                                :value="formData.tempat_lahir"
                                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                        </div>
                                        <div>
                                            <label for="tanggal_lahir"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                                Lahir</label>
                                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                                :value="formData.tanggal_lahir"
                                                class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                        </div>
                                    </div>

                                    <!-- Tahun Masuk -->
                                    <div>
                                        <label for="tahun_masuk"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun
                                            Masuk</label>
                                        <input type="number" id="tahun_masuk" name="tahun_masuk"
                                            :value="formData.tahun_masuk" min="1900" max="<?= date('Y') ?>"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                    </div>
                                </div>

                                <!-- Contact Info Section -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-3">Informasi Kontak</h4>

                                    <!-- Email Field -->
                                    <div class="mb-3">
                                        <label for="email"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email
                                            <span class="text-red-500">*</span></label>
                                        <input type="email" id="email" name="email" :value="formData.email"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                            required>
                                    </div>

                                    <!-- Telepon Field -->
                                    <div class="mb-3">
                                        <label for="telp"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor
                                            Telepon</label>
                                        <input type="text" id="telp" name="telp" :value="formData.telp"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                    </div>

                                    <!-- Alamat Field -->
                                    <div>
                                        <label for="alamat"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                                        <textarea id="alamat" name="alamat" rows="2" x-text="formData.alamat"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Form Actions -->
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            :class="modalMode === 'delete' ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700'"
                            class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm focus:outline-none sm:ml-3 sm:w-auto">
                            <span
                                x-text="modalMode === 'delete' ? 'Hapus' : (modalMode === 'edit' ? 'Simpan Perubahan' : 'Simpan')"></span>
                        </button>
                        <button type="button" @click="showModal = false"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Additional scripts
$additionalScripts = <<<HTML
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables
        $('#dosenTable').DataTable({
            language: {url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'},
            pageLength: 10,
            responsive: true
        });
    });
</script>
HTML;

// Define custom page variables
$pageVars = [
    'title' => 'Data Dosen - Sistem Manajemen Kegiatan Dosen',
    'description' => 'Manajemen data dosen perguruan tinggi',
    'pageTitle' => 'Data Dosen',
    'additionalScripts' => $additionalScripts
];

// Render the layout with the content
echo renderContent($content, 'main', $pageVars);
?>