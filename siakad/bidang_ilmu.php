<?php
require_once './config/app.php';
require_once './helpers/functions.php';
require_once './helpers/layout.php';
require_once './models/Database.php';
require_once './models/BidangIlmu.php';
require_once './includes/auth.php';

// Require login
requireLogin();

// Initialize models
$bidangIlmuModel = new BidangIlmu();

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        // Sanitize form data - add deskripsi field
        $data = [
            'nama' => trim($_POST['nama'] ?? ''),
            'deskripsi' => trim($_POST['deskripsi'] ?? ''),
        ];

        $action = $_POST['action'];
        $result = false;
        $message = '';

        switch ($action) {
            case 'create':
                $result = $bidangIlmuModel->create($data);
                $message = $result !== false ? 'Bidang ilmu berhasil ditambahkan' : 'Gagal menambahkan bidang ilmu';
                break;
            case 'edit':
                $id = (int) $_POST['id'];
                $result = $bidangIlmuModel->update($id, $data);
                $message = $result ? 'Bidang ilmu berhasil diperbarui' : 'Gagal memperbarui bidang ilmu';
                break;
            case 'delete':
                $id = (int) $_POST['id'];
                $result = $bidangIlmuModel->delete($id);
                $message = $result ? 'Bidang ilmu berhasil dihapus' : 'Gagal menghapus bidang ilmu';
                break;
        }

        setFlashMessage($message, $result ? 'success' : 'danger');
    }

    // Redirect back to prevent form resubmission
    header('Location: bidang_ilmu.php');
    exit;
}

// Get all bidang ilmu data
$bidangIlmu = $bidangIlmuModel->getAll();

// Start output buffering to capture content
ob_start();
?>

<div x-data="{ showModal: false, modalMode: 'create', formData: {} }">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Bidang Ilmu</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manajemen data bidang ilmu</p>
        </div>
        <button type="button" @click="showModal = true; modalMode = 'create'; formData = {}"
            class="mt-3 sm:mt-0 inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Bidang Ilmu
        </button>
    </div>

    <!-- Main Content -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="p-6 overflow-x-auto">
            <table id="bidangIlmuTable" class="min-w-full table-auto">
                <thead class="bg-gray-50 dark:bg-gray-700/30">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama Bidang Ilmu
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (!empty($bidangIlmu)): ?>
                        <?php foreach ($bidangIlmu as $bi): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($bi['nama'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    <?= !empty($bi['deskripsi']) ? htmlspecialchars($bi['deskripsi']) : '<span class="italic text-gray-400 dark:text-gray-500">Tidak ada deskripsi</span>' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button type="button"
                                            @click="showModal = true; modalMode = 'edit'; formData = <?= htmlspecialchars(json_encode($bi)) ?>"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/30 transition-all duration-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                            @click="showModal = true; modalMode = 'delete'; formData = <?= htmlspecialchars(json_encode($bi)) ?>"
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
                            <td colspan="3" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>Tidak ada data bidang ilmu yang tersedia.</p>
                                    <button type="button" @click="showModal = true; modalMode = 'create'; formData = {}"
                                        class="mt-4 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 transition-all duration-200">
                                        Tambah Bidang Ilmu Baru
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
                                            x-text="`Apakah Anda yakin ingin menghapus bidang ilmu '${formData.nama}'? Tindakan ini tidak dapat dibatalkan.`">
                                        </p>
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                            Perhatian: Menghapus bidang ilmu dapat memengaruhi data kegiatan dan
                                            penelitian yang terkait.
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
                                    x-text="modalMode === 'edit' ? 'Edit Bidang Ilmu' : 'Tambah Bidang Ilmu'">
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                                    x-text="modalMode === 'edit' ? 'Perbarui informasi bidang ilmu.' : 'Isi form berikut untuk menambahkan bidang ilmu baru.'">
                                </p>
                            </div>

                            <div class="space-y-4">
                                <!-- Nama Bidang Ilmu -->
                                <div>
                                    <label for="nama"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nama Bidang Ilmu
                                    </label>
                                    <input type="text" id="nama" name="nama" :value="formData.nama"
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                        required>
                                </div>

                                <!-- Deskripsi Field - Add this new section -->
                                <div>
                                    <label for="deskripsi"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Deskripsi
                                    </label>
                                    <textarea id="deskripsi" name="deskripsi" rows="3"
                                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                        x-text="formData.deskripsi"></textarea>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Berikan deskripsi ringkas tentang bidang ilmu ini (opsional)
                                    </p>
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

// Define custom page variables
$pageVars = [
    'title' => 'Bidang Ilmu - Sistem Manajemen Kegiatan Dosen',
    'description' => 'Manajemen data bidang ilmu',
    'pageTitle' => 'Bidang Ilmu',
];

// Render the layout with the content
echo renderContent($content, 'main', $pageVars);
?>