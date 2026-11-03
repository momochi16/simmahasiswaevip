<?php
// laporan/export_excel.php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

checkLogin();

$query = "SELECT m.npm, m.nama_mahasiswa, m.jenis_kelamin, m.angkatan, p.nama_prodi, f.nama_fakultas 
          FROM mahasiswa m 
          JOIN program_studi p ON m.prodi_id = p.id 
          JOIN fakultas f ON p.fakultas_id = f.id 
          ORDER BY f.nama_fakultas ASC, p.nama_prodi ASC, m.npm ASC";
$stmt = $pdo->query($query);
$data = $stmt->fetchAll();

$filename = "Rekap_Mahasiswa_UMB_" . date('Ymd_His') . ".csv";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen("php://output", "w");

// Column Headers
fputcsv($output, ['No', 'NPM', 'Nama Mahasiswa', 'Jenis Kelamin', 'Angkatan', 'Program Studi', 'Fakultas']);

foreach ($data as $index => $row) {
    fputcsv($output, [
        $index + 1,
        $row['npm'],
        $row['nama_mahasiswa'],
        $row['jenis_kelamin'],
        $row['angkatan'],
        $row['nama_prodi'],
        $row['nama_fakultas']
    ]);
}

fclose($output);
exit();
?>
