<?php
require_once 'config/app.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Process registration form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'dosen'; // Default role for new registrations

    // Simple validation
    $errors = [];

    if (empty($name)) {
        $errors[] = 'Nama lengkap harus diisi.';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }

    if (empty($username) || strlen($username) < 4) {
        $errors[] = 'Username harus memiliki minimal 4 karakter.';
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Password harus memiliki minimal 6 karakter.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Konfirmasi password tidak sesuai.';
    }

    // Check if username or email already exists
    $userModel = new User();

    if ($userModel->usernameExists($username)) {
        $errors[] = 'Username sudah digunakan. Silakan pilih username lain.';
    }

    if ($userModel->emailExists($email)) {
        $errors[] = 'Email sudah terdaftar. Silakan gunakan email lain.';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Create user data
        $userData = [
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password, // Will be hashed in the model
            'role' => $role,
            'is_active' => 1
        ];

        // Create user
        $userId = $userModel->create($userData);

        // Create user profile
        $profileData = [
            'user_id' => $userId,
            'phone' => $_POST['phone'] ?? null,
            'address' => $_POST['address'] ?? null,
            'bio' => null,
            'last_login' => null
        ];

        // Set success message
        setFlashMessage('Registrasi berhasil! Silakan login dengan akun Anda.', 'success');
        header('Location: login.php');
        exit;
    } else {
        // Set error messages
        $errorMessage = implode('<br>', $errors);
        setFlashMessage($errorMessage, 'danger');
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - <?= APP_NAME ?></title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans p-12">

    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-white shadow-md rounded-lg max-w-5xl w-full grid md:grid-cols-2 overflow-hidden">

            <!-- Left Info Panel -->
            <div class="bg-blue-600 text-white p-8 hidden md:flex flex-col justify-between">
                <div>
                    <a href="<?= APP_URL ?>/landing.php" class="flex items-center mb-6 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" />
                        </svg>
                        <h1 class="text-2xl font-bold">SIAKAD Dosen</h1>
                    </a>
                    <h2 class="text-xl font-semibold mb-2">Bergabunglah dengan Kami!</h2>
                    <p class="mb-6 text-sm">Daftarkan diri Anda untuk mengakses sistem manajemen kegiatan dosen.</p>

                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center"><span class="mr-2">✔️</span> Akses ke semua fitur sistem</li>
                        <li class="flex items-center"><span class="mr-2">✔️</span> Kelola data akademik dengan mudah
                        </li>
                        <li class="flex items-center"><span class="mr-2">✔️</span> Dapatkan wawasan dari data Anda</li>
                    </ul>
                </div>

                <div class="mt-8 text-sm">
                    Sudah punya akun?
                    <a href="<?= APP_URL ?>/login.php" class="underline">Login</a>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="p-8">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Registrasi</h2>

                <?php $flashMessage = getFlashMessage(); ?>
                <?php if ($flashMessage): ?>
                    <div
                        class="mb-4 p-3 rounded bg-<?= $flashMessage['type'] === 'success' ? 'green' : 'red' ?>-100 text-<?= $flashMessage['type'] === 'success' ? 'green' : 'red' ?>-800">
                        <?= $flashMessage['message'] ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>"
                            class="w-full px-4 py-2 mt-1 border rounded focus:outline-none focus:ring focus:ring-blue-300"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>"
                            class="w-full px-4 py-2 mt-1 border rounded focus:outline-none focus:ring focus:ring-blue-300"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>"
                            class="w-full px-4 py-2 mt-1 border rounded focus:outline-none focus:ring focus:ring-blue-300"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password"
                            class="w-full px-4 py-2 mt-1 border rounded focus:outline-none focus:ring focus:ring-blue-300"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input type="password" name="confirm_password"
                            class="w-full px-4 py-2 mt-1 border rounded focus:outline-none focus:ring focus:ring-blue-300"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Telepon (Opsional)</label>
                        <input type="text" name="phone" value="<?= $_POST['phone'] ?? '' ?>"
                            class="w-full px-4 py-2 mt-1 border rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat (Opsional)</label>
                        <textarea name="address" rows="2"
                            class="w-full px-4 py-2 mt-1 border rounded"><?= $_POST['address'] ?? '' ?></textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                            Daftar
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-sm text-center text-gray-600">
                    Sudah punya akun? <a href="<?= APP_URL ?>/login.php" class="text-blue-600 underline">Login di
                        sini</a>
                </div>

                <div class="mt-3 text-sm text-center">
                    <a href="<?= APP_URL ?>/landing.php" class="text-gray-600 hover:text-blue-600 underline">← Kembali
                        ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>