<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>{{ config('app.name') }} - @yield('title', 'Sistem Belanja Online')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/icons/logo.png') }}" />
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #696cff;
            --secondary-color: #6c757d;
            --accent-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-color);
        }

        /* Navbar Styles */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark-color);
            position: relative;
            margin: 0 5px;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active {
            color: var(--primary-color);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 10px;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #146c43;
            border-color: #146c43;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Card Styles */
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 10px 10px 0 0 !important;
        }

        .card-header.bg-success {
            background-color: var(--primary-color) !important;
        }

        /* Store, Product, Fabric Cards */
        .store-card,
        .product-card,
        .fabric-card {
            cursor: pointer;
            transition: all 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }

        .store-card:hover,
        .product-card:hover,
        .fabric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .store-card.selected {
            border: 3px solid var(--primary-color);
        }

        .product-card input:checked+label,
        .fabric-card input:checked+label {
            color: var(--primary-color);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check {
            padding-left: 0;
            text-align: center;
        }

        .form-check-input {
            float: none;
            margin-right: 5px;
        }

        .form-check-label {
            cursor: pointer;
        }

        /* Form Controls */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }

        /* Footer Styles */
        .footer {
            background-color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Page Title */
        .page-title {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            border-left: 5px solid var(--primary-color);
            padding-left: 15px;
        }

        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background-color: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: bold;
            margin-left: 10px;
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
        }

        .dropdown-item:hover {
            background-color: rgba(25, 135, 84, 0.1);
            color: var(--primary-color);
        }

        .dropdown-item i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        /* Cart Badge */
        .badge-cart {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.6rem;
            background-color: var(--accent-color);
            color: var(--dark-color);
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Header/Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.belanja') }}">
                {{ config('app.name', 'FashionMart') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.belanja') ? 'active' : '' }}"
                            href="{{ route('client.belanja') }}">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.track.order') ? 'active' : '' }}"
                            href="{{ route('client.track.order') }}">
                            <i class="bi bi-truck"></i> Lihat Status Order
                        </a>
                    </li>
                </ul>

                <!-- Right Side Nav -->
                <ul class="navbar-nav">
                    <!-- Shopping Cart Button -->

                    @guest
                        <!-- Login and Register Buttons -->
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary me-2">
                                <i class="bi bi-box-arrow-in-right"></i> Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-person-plus"></i> Daftar
                            </a>
                        </li>
                    @else
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2">{{ Auth::user()->name }}</span>
                                <div class="avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                @if (auth()->user()->hasRole(['admin', 'penjahit']))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.history.order') }}">
                                        <i class="bi bi-card-list"></i> History Order
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @hasSection('page-title')
            <h2 class="page-title mb-4">@yield('page-title')</h2>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-primary">{{ config('app.name', 'FashionMart') }}</h5>
                    <p class="text-muted">
                        Temukan berbagai pilihan produk fashion berkualitas dengan harga terbaik. Kami menyediakan
                        beragam jenis kain dan model pakaian untuk kebutuhan Anda.
                    </p>
                    <div class="social-icons">
                        <a href="#" class="text-secondary me-2"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-secondary me-2"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-whatsapp fs-5"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="text-dark">Belanja</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-secondary text-decoration-none">Produk Terbaru</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Produk Terlaris</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Diskon</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Semua Toko</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="text-dark">Bantuan</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-secondary text-decoration-none">Cara Pembelian</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Pengiriman</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">Pembayaran</a></li>
                        <li><a href="#" class="text-secondary text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="text-dark">Hubungi Kami</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2 text-secondary"><i class="bi bi-geo-alt me-2"></i> Jl. Fashion No. 123,
                            Jakarta Pusat</li>
                        <li class="mb-2 text-secondary"><i class="bi bi-telephone me-2"></i> +62 123 4567 890</li>
                        <li class="mb-2 text-secondary"><i class="bi bi-envelope me-2"></i> info@fashionmart.com</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }}
                        {{ config('app.name', 'FashionMart') }}. Semua Hak Dilindungi.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">
                        <a href="#" class="text-secondary text-decoration-none">Syarat & Ketentuan</a> |
                        <a href="#" class="text-secondary text-decoration-none">Kebijakan Privasi</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & jQuery JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <!-- Custom JS -->
    <script>
        // Auto-hide alert messages after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>

</html>
