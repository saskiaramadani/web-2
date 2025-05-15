<?php
require_once '../config/app.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Check if user has admin role
if ($_SESSION['user_role'] !== 'admin') {
    setFlashMessage('danger', 'Anda tidak memiliki akses ke halaman ini.');
    header('Location: ' . APP_URL);
    exit;
}

$userModel = new User();
$users = $userModel->getAll();
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Manajemen User</h1>
            <p class="text-muted">Kelola data pengguna sistem</p>
        </div>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah User
        </a>
    </div>
    
    <?php $flashMessage = getFlashMessage(); ?>
    <?php if ($flashMessage): ?>
    <div class="alert alert-<?= $flashMessage['type'] ?> alert-dismissible fade show" role="alert">
        <?= $flashMessage['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= $u['username'] ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-gradient rounded-circle me-2 d-flex align-items-center justify-content-center">
                                        <span class="text-white"><?= substr($u['name'], 0, 1) ?></span>
                                    </div>
                                    <?= $u['name'] ?>
                                </div>
                            </td>
                            <td><?= $u['email'] ?></td>
                            <td>
                                <?php
                                $roleBadge = '';
                                switch ($u['role']) {
                                    case 'admin':
                                        $roleBadge = 'bg-danger';
                                        break;
                                    case 'dosen':
                                        $roleBadge = 'bg-primary';
                                        break;
                                    case 'staff':
                                        $roleBadge = 'bg-success';
                                        break;
                                    default:
                                        $roleBadge = 'bg-secondary';
                                }
                                ?>
                                <span class="badge <?= $roleBadge ?>"><?= ucfirst($u['role']) ?></span>
                            </td>
                            <td>
                                <?php if ($u['is_active']): ?>
                                <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="view.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <?php if ($u['id'] != $_SESSION['user_id']): ?>
                                    <a href="delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Hapus" onclick="return confirmDelete('Apakah Anda yakin ingin menghapus user ini?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>
