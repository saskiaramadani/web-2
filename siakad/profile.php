<?php
// Include configuration
require_once 'config/app.php';
require_once 'helpers/functions.php';
require_once 'helpers/layout.php';
require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Dosen.php';
require_once 'models/Kegiatan.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

// Get user data
$userModel = new User();
$user = $userModel->getById($_SESSION['user_id']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $bio = $_POST['bio'];


    // Validate input
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Nama lengkap harus diisi.';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }

    // Check if email already exists (but not for current user)
    if ($email !== $user['email'] && $userModel->emailExists($email)) {
        $errors[] = 'Email sudah digunakan oleh pengguna lain.';
    }

    // If no errors, update user data
    if (empty($errors)) {
        // Update user data
        $userData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'bio' => $bio
        ];

        $userModel->update($_SESSION['user_id'], $userData);


        // Update session data
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        // Set success message
        setFlashMessage('Profil berhasil diperbarui.', 'success');

        // Redirect to refresh the page
        header('Location: profile.php');
        exit;
    } else {
        // Set error messages
        $errorMessage = implode('<br>', $errors);
        setFlashMessage($errorMessage, 'danger');
    }
}

// Start output buffering to capture content
ob_start();
?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Profil Pengguna</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi profil Anda</p>
    </div>
    <div class="mt-3 sm:mt-0">
        <a href="<?= APP_URL ?>/change-password.php"
            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-blue-600 text-white hover:bg-blue-700">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            Ubah Password
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - User Info -->
    <div>
        <!-- Profile Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-6">
            <div class="p-6 text-center">
                <div class="w-24 h-24 bg-blue-600 rounded-full mx-auto flex items-center justify-center mb-4">
                    <span class="text-3xl font-bold text-white"><?= substr($user['name'], 0, 1) ?></span>
                </div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-1"><?= $user['name'] ?></h2>
                <p class="text-blue-600 dark:text-blue-400 mb-3"><?= ucfirst($user['role']) ?></p>

                <div class="flex justify-center space-x-2 mb-4">
                    <span
                        class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                        <?= $user['username'] ?>
                    </span>
                    <span
                        class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                        Aktif
                    </span>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300"><?= $user['email'] ?></span>
                    </div>
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span
                            class="text-sm text-gray-600 dark:text-gray-300"><?= $user['phone'] ?? 'Belum diisi' ?></span>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 mr-2 mt-0.5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span
                            class="text-sm text-gray-600 dark:text-gray-300"><?= $user['address'] ?? 'Belum diisi' ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Informasi Login</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Login Terakhir</span>
                        <span
                            class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= $user['last_login'] ? date('d M Y H:i', strtotime($user['last_login'])) : 'Belum ada' ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Terdaftar Sejak</span>
                        <span
                            class="text-sm font-medium text-gray-800 dark:text-gray-200"><?= date('d M Y', strtotime($user['created_at'])) ?></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Edit Form -->
    <div class="lg:col-span-2">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-medium text-gray-800 dark:text-white flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profil
                </h3>
            </div>

            <div class="p-8">
                <form method="POST" action="">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <!-- Name Input with Icon -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nama Lengkap
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>"
                                    class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200"
                                    required>
                            </div>
                        </div>

                        <!-- Email Input with Icon -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input type="email" id="email" name="email"
                                    value="<?= htmlspecialchars($user['email']) ?>"
                                    class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200"
                                    required>
                            </div>
                        </div>

                        <!-- Username Input with Icon (Readonly) -->
                        <div class="space-y-2">
                            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Username
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" id="username" value="<?= htmlspecialchars($user['username']) ?>"
                                    class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 text-sm cursor-not-allowed"
                                    readonly>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Username tidak dapat diubah.</p>
                        </div>

                        <!-- Phone Input with Icon -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nomor Telepon
                            </label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <input type="text" id="phone" name="phone"
                                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                    class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Address Textarea with Icon -->
                    <div class="mb-8 space-y-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute top-3 left-0 pl-4 flex items-start pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <textarea id="address" name="address" rows="2"
                                class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- Bio Textarea with Icon -->
                    <div class="mb-10 space-y-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Biografi
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute top-3 left-0 pl-4 flex items-start pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <textarea id="bio" name="bio" rows="4"
                                class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                        </div>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Ceritakan sedikit tentang diri Anda, bidang keahlian, atau minat penelitian.
                        </p>
                    </div>

                    <!-- Submit Button with Enhanced Design -->
                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan Perubahan
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
    'title' => 'Profil Pengguna - Sistem Manajemen Kegiatan Dosen',
    'description' => 'Kelola informasi profil pengguna',
    'pageTitle' => 'Profil Pengguna'
];

// Render the layout with the content
echo renderContent($content, 'main', $pageVars);
?>