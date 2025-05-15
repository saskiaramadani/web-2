<?php
// Include configuration
require_once 'config/app.php';
require_once 'helpers/functions.php';
require_once 'helpers/layout.php';
require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'includes/auth.php';

// Require login
requireLogin();

// Get user data
$userModel = new User();
$user = $userModel->getById($_SESSION['user_id']);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    $errors = [];

    // Get user with password
    $userWithPassword = $userModel->getByUsername($user['username']);

    // Verify current password
    if (!$userModel->verifyPassword($current_password, $userWithPassword['password'])) {
        $errors[] = 'Password saat ini tidak sesuai.';
    }

    if (empty($new_password) || strlen($new_password) < 6) {
        $errors[] = 'Password baru harus memiliki minimal 6 karakter.';
    }

    if ($new_password !== $confirm_password) {
        $errors[] = 'Konfirmasi password baru tidak sesuai.';
    }

    // If no errors, update password
    if (empty($errors)) {
        // Update user password using dedicated method
        $userModel->updatePassword($_SESSION['user_id'], $new_password);

        // Set success message
        setFlashMessage('Password berhasil diubah.', 'success');

        // Redirect to profile page
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
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Ubah Password</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui keamanan akun Anda</p>
    </div>
    <div class="mt-3 sm:mt-0">
        <a href="profile.php"
            class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 text-gray-700 bg-white dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Profil
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Change Password Form -->
    <div class="lg:col-span-2">
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-medium text-gray-800 dark:text-white flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                    Form Ubah Password
                </h3>
            </div>

            <div class="p-8">
                <form method="POST" action="">
                    <!-- Current Password Input with Icon -->
                    <div class="mb-6 space-y-2">
                        <label for="current_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password Saat Ini
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="current_password" name="current_password"
                                class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200"
                                required>
                        </div>
                    </div>

                    <!-- New Password Input with Icon -->
                    <div class="mb-6 space-y-2">
                        <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password Baru
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="new_password" name="new_password"
                                class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200"
                                required>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Minimal 6 karakter dengan kombinasi huruf dan angka.
                        </p>
                    </div>

                    <!-- Confirm Password Input with Icon -->
                    <div class="mb-8 space-y-2">
                        <label for="confirm_password"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Konfirmasi Password Baru
                        </label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input type="password" id="confirm_password" name="confirm_password"
                                class="pl-12 py-3 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm transition-all duration-200"
                                required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column - Security Tips -->
    <div>
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-medium text-gray-800 dark:text-white flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tips Keamanan Password
                </h3>
            </div>

            <div class="p-6">
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Gunakan minimal 8 karakter dengan
                            kombinasi huruf besar, huruf kecil, angka, dan simbol.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Hindari informasi pribadi seperti nama,
                            tanggal lahir, atau alamat dalam password Anda.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Jangan gunakan password yang sama untuk
                            beberapa akun yang berbeda.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Ganti password Anda secara berkala,
                            setidaknya setiap 3-6 bulan sekali.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-green-500 mr-2 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-sm text-gray-600 dark:text-gray-300">Pertimbangkan untuk menggunakan pengelola
                            password untuk menyimpan password yang kompleks dengan aman.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Capture the content
$content = ob_get_clean();

// Define custom page variables
$pageVars = [
    'title' => 'Ubah Password - Sistem Manajemen Kegiatan Dosen',
    'description' => 'Perbarui keamanan akun dengan mengubah password',
    'pageTitle' => 'Ubah Password'
];

// Render the layout with the content
echo renderContent($content, 'main', $pageVars);
?>