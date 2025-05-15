<?php
require_once 'config/app.php';

if (session_status() === PHP_SESSION_NONE)
    session_start();
if (isset($_SESSION['user_id']))
    header('Location: dashboard.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($username) || empty($password)) {
        setFlashMessage('danger', 'Username dan password harus diisi.');
    } else {
        $userModel = new User();
        $user = $userModel->authenticate($username, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            $redirect = $_SESSION['redirect_url'] ?? 'dashboard.php';
            unset($_SESSION['redirect_url']);
            header('Location: ' . $redirect);
            exit;
        } else {
            setFlashMessage('Username atau password salah.', 'danger');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - <?= APP_NAME ?></title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/forms@0.5.3/dist/forms.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="flex w-full max-w-5xl bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Left side -->
        <div
            class="hidden md:flex flex-col justify-between bg-gradient-to-br from-indigo-600 to-blue-500 p-8 text-white w-1/2">
            <div>
                <h2 class="text-3xl font-bold">Selamat Datang Kembali!</h2>
                <p class="mt-2">Masuk untuk mengelola kegiatan & penelitian dosen dengan mudah.</p>
            </div>
            <ul class="space-y-3 mt-8">
                <li class="flex items-center gap-3"><i class="bi bi-check-circle-fill"></i> Manajemen dosen terpusat
                </li>
                <li class="flex items-center gap-3"><i class="bi bi-check-circle-fill"></i> Dokumentasi kegiatan &
                    penelitian</li>
                <li class="flex items-center gap-3"><i class="bi bi-check-circle-fill"></i> Laporan otomatis dan rapi
                </li>
            </ul>
            <div class="mt-auto">
                <p class="text-sm">Belum punya akun?</p>
                <a href="<?= APP_URL ?>/register.php"
                    class="inline-block mt-2 px-4 py-2 border border-white rounded hover:bg-white hover:text-indigo-600 transition">Daftar
                    Sekarang</a>
            </div>
        </div>

        <!-- Right side -->
        <div class="w-full md:w-1/2 p-8" x-data="{ show: false }">
            <h2 class="text-2xl font-bold mb-6">Login ke SIAKAD Dosen</h2>

            <?php $flashMessage = getFlashMessage(); ?>
            <?php if ($flashMessage): ?>
                <div
                    class="mb-4 p-4 rounded bg-<?= $flashMessage['type'] === 'danger' ? 'red' : 'green' ?>-100 text-<?= $flashMessage['type'] === 'danger' ? 'red' : 'green' ?>-800">
                    <?= $flashMessage['message'] ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Username</label>
                    <input name="username" type="text" value="<?= $_POST['username'] ?? '' ?>" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-500">
                </div>

                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full border border-gray-300 rounded px-3 py-2 pr-10 focus:outline-none focus:ring focus:border-indigo-500">
                        <button type="button" @click="show = !show"
                            class="absolute top-2.5 right-3 text-gray-500 hover:text-gray-700">
                            <i :class="show ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600">
                        Ingat saya
                    </label>
                    <a href="forgot-password.php" class="text-indigo-600 hover:underline">Lupa password?</a>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded transition">
                    <i class="bi bi-box-arrow-in-right mr-1"></i> Login
                </button>
            </form>

            <div class="mt-6 text-sm text-center">
                <p>Belum punya akun? <a href="<?= APP_URL ?>/register.php"
                        class="text-indigo-600 hover:underline">Daftar Sekarang</a></p>
                <a href="<?= APP_URL ?>/landing.php"
                    class="inline-block mt-2 text-gray-500 hover:text-indigo-600 hover:underline"><i
                        class="bi bi-arrow-left mr-1"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</body>

</html>