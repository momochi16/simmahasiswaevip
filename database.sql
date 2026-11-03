-- Database: sim_mahasiswa
CREATE DATABASE IF NOT EXISTS sim_mahasiswa;
USE sim_mahasiswa;

-- Table: roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_role VARCHAR(50) NOT NULL UNIQUE,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: pengguna
CREATE TABLE IF NOT EXISTS pengguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT,
    status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
);

-- Table: universitas
CREATE TABLE IF NOT EXISTS universitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_universitas VARCHAR(20) NOT NULL UNIQUE,
    nama_universitas VARCHAR(100) NOT NULL,
    alamat TEXT,
    email VARCHAR(100),
    nomor_telepon VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: fakultas
CREATE TABLE IF NOT EXISTS fakultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    universitas_id INT NOT NULL,
    kode_fakultas VARCHAR(20) NOT NULL,
    nama_fakultas VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (universitas_id) REFERENCES universitas(id) ON DELETE CASCADE,
    UNIQUE(universitas_id, kode_fakultas)
);

-- Table: program_studi
CREATE TABLE IF NOT EXISTS program_studi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fakultas_id INT NOT NULL,
    kode_prodi VARCHAR(20) NOT NULL,
    nama_prodi VARCHAR(100) NOT NULL,
    jenjang VARCHAR(20) NOT NULL,
    status_prodi ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fakultas_id) REFERENCES fakultas(id) ON DELETE CASCADE,
    UNIQUE(fakultas_id, kode_prodi)
);

-- Table: mahasiswa
CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    npm VARCHAR(20) NOT NULL UNIQUE,
    nama_mahasiswa VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    alamat TEXT,
    email VARCHAR(100),
    nomor_hp VARCHAR(20),
    prodi_id INT NOT NULL,
    angkatan INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (prodi_id) REFERENCES program_studi(id) ON DELETE CASCADE
);

-- Table: audit_log
CREATE TABLE IF NOT EXISTS audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pengguna_id INT,
    aktivitas VARCHAR(255) NOT NULL,
    nama_tabel VARCHAR(50),
    data_id INT,
    keterangan TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    waktu_aktivitas TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pengguna_id) REFERENCES pengguna(id) ON DELETE SET NULL
);

-- Initial Data: roles
INSERT INTO roles (nama_role, keterangan) VALUES 
('Admin', 'Memiliki akses penuh ke seluruh fitur sistem.'),
('Operator', 'Memiliki akses ke fitur pengelolaan data akademik sesuai izin.');

-- Initial Data: pengguna (password: admin123)
INSERT INTO pengguna (nama_lengkap, username, email, password_hash, role_id, status) VALUES 
('Admin SIM Mahasiswa UMB', 'admin', 'admin@umb.ac.id', '$2y$10$uBrrlpfcB6Uy5NuP6U4SceAnMNTEaHKrL7MOiymsEgEKQIO3Hijwa', 1, 'Aktif');

-- Initial Data: universitas
INSERT INTO universitas (kode_universitas, nama_universitas, alamat, email, nomor_telepon) VALUES 
('UMB', 'Universitas Muhammadiyah Bengkulu', 'Jl. Bali, Bengkulu', 'info@umb.ac.id', '0736-123456');

-- Initial Data: fakultas
INSERT INTO fakultas (universitas_id, kode_fakultas, nama_fakultas) VALUES 
(1, 'FKIP', 'Fakultas Keguruan dan Ilmu Pendidikan'),
(1, 'FT', 'Fakultas Teknik');

-- Initial Data: program_studi
INSERT INTO program_studi (fakultas_id, kode_prodi, nama_prodi, jenjang, status_prodi) VALUES 
(1, 'PEND-IND', 'Pendidikan Bahasa Indonesia', 'S1', 'Aktif'),
(2, 'TEK-INF', 'Teknik Informatika', 'S1', 'Aktif');

-- Initial Data: mahasiswa
INSERT INTO mahasiswa (npm, nama_mahasiswa, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat, email, nomor_hp, prodi_id, angkatan) VALUES 
('210001', 'Ahmad Dani', 'Laki-laki', 'Bengkulu', '2002-05-15', 'Jl. Merdeka No. 1', 'ahmad.dani@email.com', '081234567890', 2, 2021),
('210002', 'Siti Aminah', 'Perempuan', 'Bengkulu', '2003-08-20', 'Jl. Melati No. 5', 'siti.aminah@email.com', '081234567891', 1, 2021),
('220001', 'Budi Santoso', 'Laki-laki', 'Curup', '2004-01-10', 'Jl. Mawar No. 10', 'budi.santoso@email.com', '081234567892', 2, 2022);
