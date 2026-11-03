# SIM Mahasiswa - Universitas Muhammadiyah Bengkulu (UMB)

Sistem Informasi Manajemen Mahasiswa (SIM Mahasiswa) berbasis web yang dikembangkan khusus untuk Universitas Muhammadiyah Bengkulu. Sistem ini dirancang untuk mengelola data akademik secara modern, profesional, dan efisien.

## 🚀 Fitur Utama

- **Landing Page & Login**: Tampilan menarik dan responsif dengan keamanan session.
- **Dashboard Admin**: Visualisasi ringkas data akademik sesuai referensi desain dosen.
- **Manajemen Akademik**: CRUD lengkap untuk Universitas, Fakultas, Program Studi, dan Mahasiswa.
- **Manajemen Pengguna & Role**: Pengaturan hak akses (Admin & Operator) dengan password hashing BCRYPT.
- **Laporan & Rekap**: Rekapitulasi data mahasiswa dengan fitur Cetak dan Export Excel (CSV).
- **Audit Log**: Pencatatan riwayat aktivitas pengguna untuk keamanan data.
- **Responsif**: Dapat diakses dengan nyaman melalui desktop, tablet, maupun smartphone.

## 🛠️ Teknologi

- **Backend**: PHP Native (PDO)
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5, Bootstrap Icons, Google Fonts (Inter)
- **Keamanan**: Prepared Statements, Password Hashing, Session Protection, CSRF Prevention.

---

## 💻 Panduan Instalasi Lokal (XAMPP)

1.  **Persiapan Folder**:
    - Pastikan folder project `simmahasiswa` berada di dalam direktori `C:\xampp\htdocs\`.
2.  **Persiapan Database**:
    - Buka browser dan akses `http://localhost/phpmyadmin/`.
    - Buat database baru dengan nama `sim_mahasiswa`.
    - Pilih database tersebut, lalu klik menu **Import**.
    - Pilih file `database.sql` dari folder project dan klik **Go**.
3.  **Konfigurasi Koneksi**:
    - Buka file `config/database.php`.
    - Sesuaikan `$host`, `$db`, `$user`, dan `$pass` jika berbeda dengan pengaturan default XAMPP Anda.
4.  **Menjalankan Website**:
    - Akses `http://localhost/simmahasiswa/` di browser Anda.

### 🔑 Akun Administrator Awal
- **Username**: `admin`
- **Password**: `admin123`

---

## 🌐 Panduan Hosting (InfinityFree)

1.  **Buat Akun**: Daftar di [InfinityFree](https://www.infinityfree.com/) dan buat akun hosting baru.
2.  **Upload File**:
    - Masuk ke **File Manager** atau gunakan FTP (seperti FileZilla).
    - Upload seluruh isi folder project ke dalam folder `htdocs` di server hosting.
3.  **Setup Database**:
    - Di Control Panel InfinityFree, masuk ke **MySQL Databases**.
    - Buat database baru (catat nama database, host, username, dan password yang diberikan).
    - Masuk ke **phpMyAdmin** di hosting dan import file `database.sql`.
4.  **Update Konfigurasi**:
    - Edit file `config/database.php` di server hosting.
    - Ubah `$host`, `$db`, `$user`, dan `$pass` sesuai dengan informasi database dari InfinityFree.
5.  **Selesai**: Website Anda kini dapat diakses melalui domain yang diberikan oleh InfinityFree.

---

## 📂 Struktur Folder Project

```text
simmahasiswa/
├── assets/             # File statis (CSS, JS, Gambar)
├── auth/               # Autentikasi (Login, Logout)
├── config/             # Konfigurasi Database
├── includes/           # Komponen layout (Header, Sidebar, Footer)
├── universitas/        # Modul Universitas
├── fakultas/           # Modul Fakultas
├── program_studi/      # Modul Program Studi
├── mahasiswa/          # Modul Mahasiswa
├── pengguna/           # Modul Pengguna
├── role/               # Modul Role
├── laporan/            # Modul Laporan & Rekap
├── audit_log/          # Modul Audit Log
├── index.php           # Landing Page
├── dashboard.php       # Dashboard Admin
├── database.sql        # Database Schema & Data Awal
└── README.md           # Dokumentasi Project
```

&copy; 2026 Universitas Muhammadiyah Bengkulu.
