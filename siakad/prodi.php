<?php
require_once './config/app.php';
require_once './helpers/functions.php';
require_once './helpers/layout.php';
require_once './models/Database.php';
require_once './models/Prodi.php';
require_once './includes/auth.php';

// Require login
requireLogin();

// Initialize model
$prodiModel = new Prodi();

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create or Update operation
    if (isset($_POST['action'])) {
        $data = [
            'kode' => $_POST['kode'] ?? '',
            'nama' => $_POST['nama'] ?? '',
            'alamat' => $_POST['alamat'] ?? '',
            'telpon' => $_POST['telpon'] ?? '',
            'ketua' => $_POST['ketua'] ?? ''
        ];

        $action = $_POST['action'];
        $result = false;
        $message = '';

        switch ($action) {
            case 'create':
                $result = $prodiModel->create($data);
                $message = $result !== false ? 'Data program studi berhasil ditambahkan' : 'Gagal menambahkan data program studi';
                break;
            case 'edit':
                $id = (int) $_POST['id'];
                $result = $prodiModel->update($id, $data);
                $message = $result ? 'Data program studi berhasil diperbarui' : 'Gagal memperbarui data program studi';
                break;
            case 'delete':
                $id = (int) $_POST['id'];
                $result = $prodiModel->delete($id);
                $message = $result ? 'Data program studi berhasil dihapus' : 'Gagal menghapus data program studi';
                break;
        }

        setFlashMessage($message, $result ? 'success' : 'danger');
    }

    // Redirect back to the same page to prevent form resubmission
    header('Location: prodi.php');
    exit;
}

// Get all prodi data
$prodi = $prodiModel->getAll();

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
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Data Program Studi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manajemen data program studi di lingkungan perguruan
                tinggi</p>
        </div>
        <button type="button" @click="showModal = true; modalMode = 'create'; formData = {}"
            class="mt-3 sm:mt-0 inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Program Studi
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
            <table id="prodiTable" class="min-w-full table-auto">
                <thead class="bg-gray-50 dark:bg-gray-700/30">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Kode</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nama</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Ketua</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Alamat</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Telepon</th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if (count($prodi) > 0): ?>
                        <?php foreach ($prodi as $p): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <?= htmlspecialchars($p['kode'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center mr-3 shadow-sm">
                                            <span
                                                class="text-lg font-bold text-white"><?= substr(htmlspecialchars($p['nama'] ?? '?'), 0, 1) ?></span>
                                        </div>
                                        <div class="text-sm font-medium text-gray-800 dark:text-white">
                                            <?= htmlspecialchars($p['nama'] ?? '-') ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($p['ketua'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($p['alamat'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    <?= htmlspecialchars($p['telpon'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button type="button"
                                            @click="showModal = true; modalMode = 'edit'; formData = <?= htmlspecialchars(json_encode($p)) ?>"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:text-yellow-400 dark:hover:bg-yellow-900/30 transition-all duration-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button type="button"
                                            @click="showModal = true; modalMode = 'delete'; formData = <?= htmlspecialchars(json_encode($p)) ?>"
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
                                    <p>Tidak ada data program studi yang tersedia.</p>
                                    <button type="button" @click="showModal = true; modalMode = 'create'; formData = {}"
                                        class="mt-4 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 transition-all duration-200">
                                        Tambah Program Studi Baru
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
                                            x-text="`Apakah Anda yakin ingin menghapus program studi '${formData.nama}'? Tindakan ini mungkin akan menghapus data terkait.`">
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
                                    x-text="modalMode === 'edit' ? 'Edit Program Studi' : 'Tambah Program Studi'">
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                                    x-text="modalMode === 'edit' ? 'Perbarui informasi program studi melalui form berikut.' : 'Isi form berikut untuk menambahkan program studi baru.'">
                                </p>
                            </div>

                            <div class="space-y-4 max-h-[60vh] overflow-y-auto px-1">
                                <!-- Prodi Info Section -->
                                <div>
                                    <!-- Kode Field -->
                                    <div class="mb-3">
                                        <label for="kode"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kode
                                            Program Studi</label>
                                        <input type="text" id="kode" name="kode" :value="formData.kode"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                            required>
                                    </div>

                                    <!-- Nama Field -->
                                    <div class="mb-3">
                                        <label for="nama"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama
                                            Program Studi <span class="text-red-500">*</span></label>
                                        <input type="text" id="nama" name="nama" :value="formData.nama"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm"
                                            required>
                                    </div>

                                    <!-- Ketua Field -->
                                    <div class="mb-3">
                                        <label for="ketua"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ketua
                                            Program Studi</label>
                                        <input type="text" id="ketua" name="ketua" :value="formData.ketua"
                                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                                    </div>

                                    <!-- Telepon Field -->
                                    <div class="mb-3">
                                        <label for="telpon"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor
                                            Telepon</label>
                                        <input type="text" id="telpon" name="telpon" :value="formData.telpon"
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
        $('#prodiTable').DataTable({
            language: {url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json'},
            pageLength: 10,
            responsive: true
        });
    });
</script>
HTML;

// Define custom page variables
$pageVars = [
    'title' => 'Data Program Studi - Sistem Manajemen Kegiatan Dosen',
    'description' => 'Manajemen data program studi perguruan tinggi',
    'pageTitle' => 'Data Program Studi',
    'additionalScripts' => $additionalScripts
];

// Render the layout with the content
echo renderContent($content, 'main', $pageVars);
?>