<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('client.belanja') }}" class="app-brand-link">
            <img src="{{ asset('assets/icons/logo.png') }}" width="50" class="app-brand-logo demo" />
            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->is('penjahit/dashboard') ? 'active' : '' }}">
            <a href="{{ route('penjahit.dashboard') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Toko -->
        <li class="menu-item {{ request()->is('penjahit/toko*') ? 'active open' : '' }}">
            <a href="{{ route('penjahit.toko.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate" data-i18n="Toko">Toko Saya</div>
            </a>
        </li>

        @if (auth()->user()->toko)
            <li class="menu-item">
                <a href="{{ route('client.order', auth()->user()->toko->id) }}" class="menu-link" target="_blank">
                    <i class="menu-icon tf-icons bx bx-link-external"></i>
                    <div class="text-truncate">Lihat Toko di Website</div>
                </a>
            </li>
        @endif

        <!-- Produk -->
        <li class="menu-item {{ request()->is('penjahit/produk*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div class="text-truncate" data-i18n="Produk">Produk</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('penjahit/produk') ? 'active' : '' }}">
                    <a href="{{ route('penjahit.produk.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Daftar Produk">Daftar Produk</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('penjahit/produk/add') || request()->is('penjahit/produk/create') ? 'active' : '' }}">
                    <a href="{{ route('penjahit.produk.create') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Tambah Produk">Tambah Produk</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Detail -->
        <li class="menu-item {{ request()->is('penjahit/detail*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div class="text-truncate" data-i18n="Detail">Detail</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('penjahit/detail') ? 'active' : '' }}">
                    <a href="{{ route('penjahit.detail.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Daftar Detail">Daftar Detail</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('penjahit/detail/add') || request()->is('penjahit/detail/create') ? 'active' : '' }}">
                    <a href="{{ route('penjahit.detail.create') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Tambah Detail">Tambah Detail</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Pesanan -->
        <li class="menu-item {{ request()->is('penjahit/pesanan*') ? 'active' : '' }}">
            <a href="{{ route('penjahit.pesanan.index') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div class="text-truncate" data-i18n="Pesanan">Pesanan</div>
            </a>
        </li>
    </ul>
</aside>
<!-- / Menu -->
