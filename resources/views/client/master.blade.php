<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>{{ config('app.name') }} - @yield('title', 'Pesan Jahit Online')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/icons/logo.png') }}" />
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --signal-violet: #696cff;
            --signal-violet-deep: #5a5fe0;
            --cool-gray: #6c757d;
            --warm-gold: #ffc107;
            --atelier-mist: #f5f7fa;
            --paper: #ffffff;
            --ink: #212529;
            --ink-muted: #6c757d;
            --frost: rgba(0, 0, 0, 0.05);
            --leaf: #71dd37;
            --ember: #ff3e1d;
            --honey: #ffab00;
            --sky: #03c3ec;
        }

        body {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
            background-color: var(--atelier-mist);
            color: var(--ink);
            line-height: 1.6;
        }

        /* Typography hierarchy */
        h1, h2, h3, h4, .serif-heading {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 600;
            text-wrap: balance;
        }

        h1, .display-heading {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        h2, .section-heading {
            font-size: clamp(1.25rem, 3vw, 1.75rem);
            line-height: 1.3;
        }

        h5, h6, .card-title {
            font-family: 'Poppins', system-ui, sans-serif;
            font-weight: 600;
        }

        /* Navbar Styles */
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: var(--paper);
        }

        .navbar-brand {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--signal-violet);
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .nav-link {
            font-weight: 500;
            color: var(--ink);
            position: relative;
            margin: 0 5px;
            font-size: 0.875rem;
        }

        .nav-link:hover {
            color: var(--signal-violet);
        }

        .nav-link.active {
            color: var(--signal-violet);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: var(--signal-violet);
            border-radius: 10px;
        }

        /* Button Styles */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 12px 24px;
            transition: background 0.2s cubic-bezier(0.4, 0, 0.2, 1), transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--signal-violet);
            border-color: var(--signal-violet);
        }

        .btn-primary:hover {
            background-color: var(--signal-violet-deep);
            border-color: var(--signal-violet-deep);
        }

        .btn-outline-primary {
            color: var(--signal-violet);
            border-color: var(--signal-violet);
        }

        .btn-outline-primary:hover {
            background-color: var(--signal-violet);
            border-color: var(--signal-violet);
            color: var(--paper);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.8125rem;
        }

        /* Card Styles — Flat by Default */
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-header {
            background-color: var(--paper);
            border-bottom: 1px solid var(--frost);
            border-radius: 10px 10px 0 0 !important;
        }

        /* Interactive Cards — Lift on Hover */
        .store-card,
        .product-card,
        .fabric-card {
            cursor: pointer;
            border-radius: 12px;
            overflow: hidden;
        }

        .store-card:hover,
        .product-card:hover,
        .fabric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .store-card.selected {
            border: 3px solid var(--signal-violet);
        }

        .product-card input:checked+label,
        .fabric-card input:checked+label {
            color: var(--signal-violet);
        }

        .form-check-input:checked {
            background-color: var(--signal-violet);
            border-color: var(--signal-violet);
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
        .form-control, .form-select {
            padding: 8px 16px;
            border-radius: 6px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            font-size: 0.9375rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--signal-violet);
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .form-control::placeholder {
            color: var(--ink-muted);
        }

        /* Footer Styles */
        .footer {
            background-color: var(--paper);
            padding: 2.5rem 0;
            margin-top: 3rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .footer h5 {
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }

        .footer h6 {
            font-weight: 600;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer a {
            transition: color 0.2s;
        }

        .footer a:hover {
            color: var(--signal-violet) !important;
        }

        /* Page Title */
        .page-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--signal-violet);
            border-radius: 2px;
        }

        /* Avatar */
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background-color: var(--atelier-mist);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--signal-violet);
            font-weight: 600;
            margin-left: 10px;
        }

        /* Dropdown */
        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: none;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-size: 0.875rem;
        }

        .dropdown-item:hover {
            background-color: rgba(105, 108, 255, 0.08);
            color: var(--signal-violet);
        }

        .dropdown-item i {
            margin-right: 10px;
            color: var(--signal-violet);
        }

        /* Status Badges */
        .badge {
            font-weight: 500;
            font-size: 0.8125rem;
            padding: 4px 10px;
            border-radius: 999px;
        }

        /* Loading Spinner */
        .spinner-border {
            color: var(--signal-violet);
        }

        /* Progress Bar */
        .progress-bar {
            background-color: var(--signal-violet);
        }

        /* Text utilities */
        .text-balance {
            text-wrap: balance;
        }

        .text-muted {
            color: var(--ink-muted) !important;
        }

        /* Empty state */
        .empty-state-icon {
            font-size: 3rem;
            color: var(--cool-gray);
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
                transform: none !important;
            }

            .card:hover,
            .store-card:hover,
            .product-card:hover,
            .fabric-card:hover {
                transform: none !important;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Header/Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.belanja') }}">
                {{ config('app.name', 'Go-Jahit') }}
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
                            <i class="bi bi-truck"></i> Cek Status
                        </a>
                    </li>
                </ul>

                <!-- Right Side Nav -->
                <ul class="navbar-nav">
                    @guest
                        <!-- Login and Register -->
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
                                @if (auth()->user()->hasRole('admin'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard Admin
                                        </a>
                                    </li>
                                @elseif (auth()->user()->hasRole('penjahit'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('penjahit.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard Penjahit
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="{{ route('client.history.order') }}">
                                        <i class="bi bi-card-list"></i> Riwayat Pesanan
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        <i class="bi bi-box-arrow-right"></i> Keluar
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
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
            <h2 class="page-title">@yield('page-title')</h2>
        @endif

        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-primary mb-3">{{ config('app.name', 'Go-Jahit') }}</h5>
                    <p class="text-muted small">
                        Platform jahit online yang menghubungkan Anda dengan penjahit profesional
                        di seluruh Indonesia. Temukan model, pilih kain, dan pesan — semua dari rumah.
                    </p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="text-dark">Layanan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-1"><a href="{{ route('client.belanja') }}" class="text-secondary text-decoration-none small">Cari Toko</a></li>
                        <li class="mb-1"><a href="{{ route('client.track.order') }}" class="text-secondary text-decoration-none small">Lacak Pesanan</a></li>
                        <li class="mb-1"><a href="{{ route('register') }}" class="text-secondary text-decoration-none small">Daftar Akun</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h6 class="text-dark">Bantuan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-1"><a href="#" class="text-secondary text-decoration-none small">Cara Pesan</a></li>
                        <li class="mb-1"><a href="#" class="text-secondary text-decoration-none small">Pembayaran</a></li>
                        <li class="mb-1"><a href="#" class="text-secondary text-decoration-none small">Pengiriman</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="text-dark">Kontak</h6>
                    <ul class="list-unstyled small text-secondary">
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> hello@go-jahit.com</li>
                        <li class="mb-2"><i class="bi bi-instagram me-2"></i> @gojahit</li>
                    </ul>
                </div>
            </div>
            <hr class="my-3">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted small mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Go-Jahit') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small mb-0">
                        <a href="#" class="text-secondary text-decoration-none">Syarat & Ketentuan</a>
                        <span class="mx-1">·</span>
                        <a href="#" class="text-secondary text-decoration-none">Kebijakan Privasi</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap & jQuery JS -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>

</html>
