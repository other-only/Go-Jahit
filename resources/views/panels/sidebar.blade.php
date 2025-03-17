<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <img src="{{ asset('assets/icons/toko.jpg') }}" width="50" class="app-brand-logo demo" />
            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboard</div>
                {{--  <span class="badge rounded-pill bg-danger ms-auto">5</span>  --}}
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/seting*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate" data-i18n="Seting Produk">Seting Toko</div>
                {{--  <span class="badge rounded-pill bg-danger ms-auto">5</span>  --}}
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/seting/toko') ? 'active' : '' }}">
                    <a href="{{ route('admin.toko.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Toko">Toko</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('admin/seting/produk') ? 'active' : '' }}">
                    <a href="{{ route('admin.produk.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Produk">Produk</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('admin/seting/detail') ? 'active' : '' }}">
                    <a href="{{ route('admin.detail.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Detail">Detail</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->is('admin/order*') ? 'active' : '' }}">
            <a href="{{ route('admin.order.index') }}" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div class="text-truncate" data-i18n="Pemesanan">Pemesanan</div>
                {{--  <span class="badge rounded-pill bg-danger ms-auto">5</span>  --}}
            </a>
        </li>


    </ul>
</aside>
<!-- / Menu -->
