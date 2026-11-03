<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIM Mahasiswa - Universitas Muhammadiyah Bengkulu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        .bg-hijau-tua {
            background-color: #0d3b2e;
        }
        .text-hijau-tua {
            color: #0d3b2e;
        }
        .btn-hijau {
            background-color: #198754;
            color: white;
            border: none;
        }
        .btn-hijau:hover {
            background-color: #157347;
            color: white;
        }
        .navbar {
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .hero-section {
            padding: 100px 0;
            background-color: #f8f9fa;
        }
        .feature-card {
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 30px;
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
        .feature-icon {
            width: 60px;
            height: 60px;
            background-color: #d1e7dd;
            color: #0f5132;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        footer {
            background-color: #0d3b2e;
            color: white;
            padding: 50px 0 20px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="assets/logo.png" alt="Logo" width="40" height="40" class="me-2">
                <div>
                    <span class="fw-bold d-block lh-1">SIM Mahasiswa</span>
                    <small class="text-muted">Universitas Muhammadiyah Bengkulu</small>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link px-3" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#tentang">Tentang Sistem</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#fitur">Fitur</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-hijau px-4 rounded-pill" href="auth/login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3 text-hijau-tua">Sistem Informasi Manajemen Mahasiswa</h1>
                    <p class="lead mb-4 text-muted">Platform terpadu untuk pengelolaan data akademik mahasiswa, fakultas, dan program studi di lingkungan Universitas Muhammadiyah Bengkulu secara efisien dan transparan.</p>
                    <div class="d-flex gap-3">
                        <a href="auth/login.php" class="btn btn-hijau btn-lg px-4 rounded-pill">Masuk ke Sistem</a>
                        <a href="#tentang" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">Pelajari Lebih Lanjut</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <img src="assets/gedung.jpg" alt="Hero Illustration" class="img-fluid rounded-4 shadow-sm">
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Sistem -->
    <section id="tentang" class="py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <img src="assets/logo.png" alt="About System" class="img-fluid rounded-4 shadow">
                </div>
                <div class="col-lg-7 ps-lg-5 mt-4 mt-lg-0">
                    <h6 class="text-success fw-bold text-uppercase mb-2">Tentang Sistem</h6>
                    <h2 class="fw-bold mb-4 text-hijau-tua">Solusi Modern Manajemen Akademik UMB</h2>
                    <p class="text-muted mb-4">SIM Mahasiswa UMB adalah sistem informasi yang dirancang khusus untuk memenuhi kebutuhan administrasi akademik di Universitas Muhammadiyah Bengkulu. Sistem ini mengintegrasikan berbagai data penting mulai dari identitas mahasiswa hingga laporan rekapitulasi tingkat universitas.</p>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon bg-light text-success me-3 shadow-sm">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Aman & Terpercaya</h5>
                                    <p class="small text-muted mb-0">Keamanan data terjamin dengan enkripsi modern.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon bg-light text-success me-3 shadow-sm">
                                    <i class="bi bi-lightning-charge"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Cepat & Akurat</h5>
                                    <p class="small text-muted mb-0">Akses data real-time dengan performa tinggi.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Sistem -->
    <section id="fitur" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h6 class="text-success fw-bold text-uppercase mb-2">Fitur Utama</h6>
                <h2 class="fw-bold text-hijau-tua">Layanan Manajemen Terintegrasi</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card bg-white">
                        <div class="feature-icon"><i class="bi bi-people"></i></div>
                        <h5 class="fw-bold">Manajemen Mahasiswa</h5>
                        <p class="text-muted small">Pengelolaan data induk mahasiswa lengkap dengan NPM, angkatan, dan status akademik.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card bg-white">
                        <div class="feature-icon"><i class="bi bi-building"></i></div>
                        <h5 class="fw-bold">Manajemen Universitas</h5>
                        <p class="text-muted small">Pengaturan profil universitas, fakultas, dan program studi secara terstruktur.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card bg-white">
                        <div class="feature-icon"><i class="bi bi-person-badge"></i></div>
                        <h5 class="fw-bold">Manajemen Pengguna</h5>
                        <p class="text-muted small">Kontrol hak akses sistem berbasis role untuk admin dan operator.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card bg-white">
                        <div class="feature-icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
                        <h5 class="fw-bold">Laporan Akademik</h5>
                        <p class="text-muted small">Penyajian data statistik dan rekapitulasi mahasiswa yang dapat dicetak.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card bg-white">
                        <div class="feature-icon"><i class="bi bi-journal-text"></i></div>
                        <h5 class="fw-bold">Audit Log</h5>
                        <p class="text-muted small">Pencatatan setiap aktivitas pengguna untuk menjaga integritas data.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card bg-white">
                        <div class="feature-icon"><i class="bi bi-phone"></i></div>
                        <h5 class="fw-bold">Responsif</h5>
                        <p class="text-muted small">Akses sistem dengan nyaman melalui berbagai perangkat mobile maupun desktop.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <h5 class="fw-bold mb-3">SIM Mahasiswa UMB</h5>
                    <p class="small text-light-emphasis" style="max-width: 400px;">Sistem Informasi Manajemen Mahasiswa Universitas Muhammadiyah Bengkulu. Memberikan kemudahan dalam pengelolaan data akademik secara modern dan profesional.</p>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-bold mb-3">Tautan Cepat</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#beranda" class="text-decoration-none text-light-emphasis">Beranda</a></li>
                        <li class="mb-2"><a href="#tentang" class="text-decoration-none text-light-emphasis">Tentang Sistem</a></li>
                        <li class="mb-2"><a href="#fitur" class="text-decoration-none text-light-emphasis">Fitur</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-bold mb-3">Kontak</h6>
                    <ul class="list-unstyled small text-light-emphasis">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Jl. Bali, Kota Bengkulu</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> info@umb.ac.id</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> (0736) 123456</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-light opacity-25">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="small mb-0">&copy; 2026 Universitas Muhammadiyah Bengkulu. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
