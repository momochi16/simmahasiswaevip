<?php
// audit_log/index.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();
if (!isAdmin()) {
    header("Location: ../dashboard.php");
    exit();
}

$title = 'Audit Log - SIM Mahasiswa UMB';
$active_menu = 'audit';
$base_url = '../';

$query = "SELECT a.*, p.nama_lengkap 
          FROM audit_log a 
          LEFT JOIN pengguna p ON a.pengguna_id = p.id 
          ORDER BY a.waktu_aktivitas DESC 
          LIMIT 500";
$logs = $pdo->query($query)->fetchAll();

require_once '../includes/header.php';
?>

<div class="mb-4">
    <h4 class="fw-bold text-hijau-tua mb-0">Audit Log</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="../dashboard.php" class="text-decoration-none text-success">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Audit Log</li>
        </ol>
    </nav>
</div>

<div class="card card-custom border-0 shadow-sm bg-white">
    <div class="card-body p-4">
        <div class="alert alert-warning small mb-4" role="alert">
            <i class="bi bi-shield-fill-exclamation me-2"></i>
            Halaman ini mencatat seluruh aktivitas sistem untuk keperluan audit keamanan. Data bersifat <strong>Read-Only</strong>.
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle small">
                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Aktivitas</th>
                        <th>Tabel</th>
                        <th>ID Data</th>
                        <th>Keterangan</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-nowrap"><?= date('d/m/Y H:i:s', strtotime($log['waktu_aktivitas'])) ?></td>
                            <td class="fw-bold text-success"><?= h($log['nama_lengkap'] ?: 'System') ?></td>
                            <td>
                                <?php 
                                    $badgeClass = 'bg-secondary';
                                    if ($log['aktivitas'] === 'INSERT') $badgeClass = 'bg-success';
                                    if ($log['aktivitas'] === 'UPDATE') $badgeClass = 'bg-primary';
                                    if ($log['aktivitas'] === 'DELETE') $badgeClass = 'bg-danger';
                                    if ($log['aktivitas'] === 'LOGIN') $badgeClass = 'bg-info text-dark';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= h($log['aktivitas']) ?></span>
                            </td>
                            <td><code class="text-dark"><?= h($log['nama_tabel'] ?: '-') ?></code></td>
                            <td><?= h($log['data_id'] ?: '-') ?></td>
                            <td><?= h($log['keterangan']) ?></td>
                            <td><small class="text-muted"><?= h($log['ip_address']) ?></small></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
