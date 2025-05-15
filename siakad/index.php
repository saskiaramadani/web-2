<?php
require_once 'config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= APP_NAME ?> - Sistem Modern untuk Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-white via-indigo-50 to-white text-gray-800 font-sans antialiased">

    <!-- Navbar -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/90 backdrop-blur shadow" x-data="{ open: false }">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="<?= APP_URL ?>" class="text-xl font-bold text-indigo-600 flex items-center">
                <i class="bi bi-mortarboard-fill text-2xl mr-2"></i><?= APP_NAME ?>
            </a>
            <div class="hidden md:flex space-x-6 font-medium">
                <a href="#features" class="hover:text-indigo-500">Fitur</a>
                <a href="#benefits" class="hover:text-indigo-500">Manfaat</a>
                
            </div>
            <div class="hidden md:flex space-x-3">
                <a href="<?= APP_URL ?>/login.php"
                    class="text-indigo-600 border border-indigo-600 px-4 py-2 rounded-full hover:bg-indigo-50 transition">
                    <i class="bi bi-box-arrow-in-right mr-2"></i>Login
                </a>
                <a href="<?= APP_URL ?>/register.php"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-full hover:bg-indigo-700 transition">
                    <i class="bi bi-person-plus mr-2"></i>Daftar
                </a>
            </div>
            <button class="md:hidden text-2xl" @click="open = !open">
                <i :class="open ? 'bi bi-x' : 'bi bi-list'"></i>
            </button>
        </div>
        <div x-show="open" x-cloak class="md:hidden bg-white shadow">
            <div class="px-4 py-4 space-y-3">
                <a href="#features" class="block">Fitur</a>
                <a href="#benefits" class="block">Manfaat</a>
                
                <hr>
                <a href="<?= APP_URL ?>/login.php" class="block text-indigo-600"><i
                        class="bi bi-box-arrow-in-right mr-2"></i>Login</a>
                <a href="<?= APP_URL ?>/register.php"
                    class="block bg-indigo-600 text-white py-2 text-center rounded">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-36 pb-20 text-center bg-gradient-to-br from-indigo-50 via-white to-indigo-100">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-indigo-700 mb-4 leading-tight">
                <span class="text-indigo-600"><?= APP_NAME ?></span><br>Sistem Cerdas untuk Dosen Modern
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">Automasi proses akademik dan kelola kinerja dosen
                secara efisien dalam satu platform.</p>
            <div class="flex justify-center gap-4">
                <a href="<?= APP_URL ?>/register.php"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-full hover:bg-indigo-700 transition">
                    <i class="bi bi-rocket-takeoff mr-2"></i>Daftar Gratis
                </a>
                <a href="#features"
                    class="border border-indigo-600 text-indigo-600 px-6 py-3 rounded-full hover:bg-indigo-50 transition">
                    <i class="bi bi-lightbulb mr-2"></i>Pelajari Fitur
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-indigo-700 mb-4">Fitur Unggulan</h2>
            <p class="text-gray-600 mb-12">Temukan fitur-fitur modern untuk kemudahan pengelolaan data dosen.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $features = [
                    ['icon' => 'bi-person-badge', 'title' => 'Profil Dosen', 'desc' => 'Manajemen data & CV dosen.'],
                    ['icon' => 'bi-journal-text', 'title' => 'Penelitian', 'desc' => 'Kelola laporan & publikasi.'],
                    ['icon' => 'bi-calendar2-week', 'title' => 'Agenda Akademik', 'desc' => 'Kelola seminar & kegiatan.'],
                    ['icon' => 'bi-bar-chart-line', 'title' => 'Analisis Kinerja', 'desc' => 'Laporan visual otomatis.'],
                ];
                foreach ($features as $f) {
                    echo "
        <div class='bg-indigo-50 hover:bg-indigo-100 transition rounded-xl p-6 shadow-sm'>
          <div class='text-indigo-600 text-3xl mb-4'><i class='bi {$f['icon']}'></i></div>
          <h3 class='text-xl font-semibold mb-2'>{$f['title']}</h3>
          <p class='text-gray-600 text-sm'>{$f['desc']}</p>
        </div>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section id="benefits" class="py-20 bg-gradient-to-br from-indigo-50 via-white to-indigo-100">
        <div class="container mx-auto px-12 grid lg:grid-cols-2 items-center gap-12">
            <img src="
https://assets.siakadcloud.com/uploads/sttnurulfikri/bgaplikasi/1405.jpg" alt="Keuntungan"
                class="w-full rounded-xl mx-auto lg:mx-0">
            <div>
                <h2 class="text-3xl font-bold text-indigo-700 mb-6">Mengapa Memilih <?= APP_NAME ?>?</h2>
                <ul class="space-y-6">
                    <li class="flex items-start">
                        <span class="text-indigo-600 text-xl mr-4"><i class="bi bi-speedometer2"></i></span>
                        <div>
                            <h4 class="font-semibold text-lg">Cepat & Efisien</h4>
                            <p class="text-gray-600 text-sm">Kurangi waktu administratif dengan automasi.</p>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <span class="text-indigo-600 text-xl mr-4"><i class="bi bi-cloud-check"></i></span>
                        <div>
                            <h4 class="font-semibold text-lg">Akses dari Mana Saja</h4>
                            <p class="text-gray-600 text-sm">Dukung kerja hybrid dosen secara online.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-8 text-center text-sm text-gray-500">
        &copy; <?= date('Y') ?> <?= APP_NAME ?>. Semua hak dilindungi.
    </footer>

</body>

</html>