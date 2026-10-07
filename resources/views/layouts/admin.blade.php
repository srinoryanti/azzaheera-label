<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} — Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo_website.png') }}">

    {{-- Spark Admin: Bootstrap 5 + Icons via CDN + design system Spark --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.min.css') }}">
    {{-- Token brand bersama (satu sumber kebenaran warna/font) --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/brand-tokens.css') }}">
    {{-- Design system Spark (satu-satunya sumber tampilan admin) --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/spark-admin.css') }}">
    @stack('styles')
</head>
<body class="spark-body">
<div class="sidebar-overlay" id="sidebar-overlay"></div>

{{-- ============ SIDEBAR ============ --}}
<div class="sidebar-wrapper" id="sidebar">
    <a href="{{ route('admin.index') }}" class="sidebar-brand">
        <img src="{{ asset('assets/images/logowebsite.jpeg') }}" alt="Azzahera" class="sidebar-brand-logo" onerror="this.src='https://ui-avatars.com/api/?name=A&background=141414&color=D4AF37'">
        <span>Azzahera Admin</span>
    </a>

    <div class="flex-grow-1 overflow-y-auto">
        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Menu</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.orders') }}" class="sidebar-menu-link {{ request()->routeIs('admin.orders*') || request()->routeIs('admin.order.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i><span>Pesanan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Katalog</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.products') }}" class="sidebar-menu-link {{ request()->routeIs('admin.product*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i><span>Produk</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.product.add') }}" class="{{ request()->routeIs('admin.product.add') ? 'active' : '' }}">Tambah Produk</a></li>
                        <li><a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products') ? 'active' : '' }}">Semua Produk</a></li>
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.brands') }}" class="sidebar-menu-link {{ request()->routeIs('admin.brand*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i><span>Merek</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.brand.add') }}">Tambah Merek</a></li>
                        <li><a href="{{ route('admin.brands') }}">Semua Merek</a></li>
                    </ul>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.categories') }}" class="sidebar-menu-link {{ request()->routeIs('admin.categor*') ? 'active' : '' }}">
                        <i class="bi bi-layers"></i><span>Kategori</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.category.add') }}">Tambah Kategori</a></li>
                        <li><a href="{{ route('admin.categories') }}">Semua Kategori</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="sidebar-menu-section">
            <div class="sidebar-menu-title">Konten & Lainnya</div>
            <ul class="sidebar-menu-list">
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.slides') }}" class="sidebar-menu-link {{ request()->routeIs('admin.slide*') ? 'active' : '' }}">
                        <i class="bi bi-images"></i><span>Slide</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.reviews') }}" class="sidebar-menu-link {{ request()->routeIs('admin.review*') ? 'active' : '' }}">
                        <i class="bi bi-star"></i><span>Ulasan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('admin.contacts') }}" class="sidebar-menu-link {{ request()->routeIs('admin.contact*') ? 'active' : '' }}">
                        <i class="bi bi-envelope"></i><span>Pesan Kontak</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="{{ route('account.admin.password.edit') }}" class="sidebar-menu-link {{ request()->routeIs('account.admin.password.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i><span>Akun</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-profile">
        <img src="{{ asset('assets/images/logowebsite.jpeg') }}" alt="Admin" class="sidebar-profile-img" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=141414&color=D4AF37'">
        <div class="sidebar-profile-info">
            <div class="sidebar-profile-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
            <div class="sidebar-profile-email">{{ Auth::user()->email ?? 'admin@azzahera.id' }}</div>
        </div>
    </div>
</div>

{{-- ============ MAIN ============ --}}
<div class="main-wrapper">
    <header class="navbar-custom">
        <div class="navbar-left">
            <button class="btn-desktop-toggle d-none d-lg-flex" id="desktop-sidebar-toggle" aria-label="Toggle Sidebar">
                <i class="bi bi-chevron-bar-left"></i>
            </button>
            <button class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="Toggle Navigation">
                <i class="bi bi-list"></i>
            </button>
            <div class="dropdown ms-1">
                <button class="btn-quick-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-plus-lg"></i><span>Create</span>
                </button>
                <ul class="dropdown-menu">
                    <li class="dropdown-header">Aksi Cepat</li>
                    <li><a class="dropdown-item" href="{{ route('admin.product.add') }}"><i class="bi bi-box-seam me-2"></i>Produk Baru</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.category.add') }}"><i class="bi bi-layers me-2"></i>Kategori Baru</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.brand.add') }}"><i class="bi bi-tags me-2"></i>Merek Baru</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.orders') }}"><i class="bi bi-receipt me-2"></i>Lihat Pesanan</a></li>
                </ul>
            </div>
        </div>

        <div class="navbar-search-wrapper">
            <input type="text" class="navbar-search-input" placeholder="Cari produk di Azzahera..." id="search-input" autocomplete="off">
            <button class="navbar-search-btn" aria-label="Search"><i class="bi bi-search"></i></button>
            <div class="box-content-search d-none" id="search-box">
                <ul id="box-content-search"></ul>
            </div>
        </div>

        <div class="navbar-actions">
            <button class="navbar-action-btn" id="btn-fullscreen" aria-label="Fullscreen"><i class="bi bi-arrows-fullscreen"></i></button>
            <div class="dropdown">
                <button class="navbar-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    <i class="bi bi-bell"></i>@if(($notifOrderCount ?? 0) > 0)<span class="navbar-action-badge"></span>@endif
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-notification p-0">
                    <div class="notification-header">
                        <h6 class="notification-title">Notifikasi</h6>
                        <a href="{{ route('admin.orders') }}" class="btn-clear-all text-decoration-none">Lihat pesanan</a>
                    </div>
                    <div class="notification-list">
                        <a href="{{ route('admin.orders') }}" class="notification-item">
                            <div class="notification-icon bg-success text-white"><i class="bi bi-wallet2"></i></div>
                            <div class="notification-content">
                                @if(($notifOrderCount ?? 0) > 0)
                                    <p class="notification-text">Ada {{ $notifOrderCount }} pesanan yang perlu diproses</p>
                                @else
                                    <p class="notification-text">Tidak ada pesanan yang perlu diproses</p>
                                @endif
                                <span class="notification-time">Buka halaman pesanan</span>
                            </div>
                            @if(($notifOrderCount ?? 0) > 0)<span class="notification-unread-dot"></span>@endif
                        </a>
                        <a href="{{ route('admin.contacts') }}" class="notification-item">
                            <div class="notification-icon bg-primary text-white"><i class="bi bi-envelope"></i></div>
                            <div class="notification-content">
                                @if(($notifContactCount ?? 0) > 0)
                                    <p class="notification-text">{{ $notifContactCount }} pesan kontak masuk</p>
                                @else
                                    <p class="notification-text">Belum ada pesan kontak</p>
                                @endif
                                <span class="notification-time">Buka halaman kontak</span>
                            </div>
                        </a>
                        <a href="{{ route('admin.reviews') }}" class="notification-item">
                            <div class="notification-icon bg-warning text-dark"><i class="bi bi-star-fill"></i></div>
                            <div class="notification-content">
                                @if(($notifReviewCount ?? 0) > 0)
                                    <p class="notification-text">{{ $notifReviewCount }} ulasan produk pelanggan</p>
                                @else
                                    <p class="notification-text">Belum ada ulasan produk</p>
                                @endif
                                <span class="notification-time">Buka halaman ulasan</span>
                            </div>
                        </a>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="notification-footer">Lihat Semua Pesanan</a>
                </div>
            </div>
            <div class="dropdown ms-1">
                <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('assets/images/logowebsite.jpeg') }}" alt="Profile" class="navbar-profile-img" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=141414&color=D4AF37'">
                    <span class="navbar-profile-name d-none d-md-inline">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">Halo, {{ Auth::user()->name ?? 'Admin' }}!</li>
                    <li><a class="dropdown-item" href="{{ route('account.admin.password.edit') }}"><i class="bi bi-person me-2"></i>Akun Saya</a></li>
                    <li><a class="dropdown-item" href="{{ route('home.index') }}" target="_blank"><i class="bi bi-shop me-2"></i>Lihat Toko</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="spark-content">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="footer-custom">
        <div class="footer-left">
            <span class="footer-copy">&copy; Copyright Azzahera Label All Rights Reserved</span>
        </div>
        <div class="footer-right">
            <span class="footer-copy">Designed by Sri Nor Yanti</span>
        </div>
    </footer>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
<script>
(function(){
    var body=document.body;
    var desktopBtn=document.getElementById('desktop-sidebar-toggle');
    var mobileBtn=document.getElementById('sidebar-toggle');
    var overlay=document.getElementById('sidebar-overlay');
    if(desktopBtn){desktopBtn.addEventListener('click',function(){body.classList.toggle('sidebar-collapsed');});}
    function closeMobile(){body.classList.remove('sidebar-mobile-open');}
    if(mobileBtn){mobileBtn.addEventListener('click',function(){body.classList.toggle('sidebar-mobile-open');});}
    if(overlay){overlay.addEventListener('click',closeMobile);}
    var fs=document.getElementById('btn-fullscreen');
    if(fs){fs.addEventListener('click',function(){if(!document.fullscreenElement){document.documentElement.requestFullscreen().catch(function(){});}else{document.exitFullscreen();}});}
})();
</script>
<script>
$(function() {
    var t=null,last='';
    var box=$('#search-box');
    $("#search-input").on("keyup", function() {
        clearTimeout(t);
        var q=$(this).val().trim();
        if(q.length<2){$("#box-content-search").html('');box.addClass('d-none');return;}
        if(q===last) return;
        t=setTimeout(function(){
            last=q;
            $.ajax({
                type:"GET",url:"{{ route('admin.search') }}",data:{query:q},dataType:'json',
                success:function(data){
                    $("#box-content-search").html('');
                    if(!data.length){box.removeClass('d-none');$("#box-content-search").html('<li class="p-3 text-muted small">Tidak ditemukan.</li>');return;}
                    $.each(data,function(i,item){
                        var url="{{ route('admin.product.edit',['id'=>'product_id']) }}".replace('product_id',item.id);
                        var img="{{ asset('storage/products') }}/"+item.image;
                        $("#box-content-search").append('<li><a href="'+url+'" class="d-flex gap-2 align-items-center p-2 text-decoration-none text-dark"><img src="'+img+'" width="44" height="44" style="object-fit:cover;border-radius:8px" loading="lazy" onerror="this.style.display=\'none\'"><span class="small fw-semibold">'+item.name+'</span></a></li>');
                    });
                    box.removeClass('d-none');
                }
            });
        },350);
    });
    $(document).on('click',function(e){if(!$(e.target).closest('.navbar-search-wrapper').length){box.addClass('d-none');}});
});
</script>
<script>
/* Custom dropdown — pengganti popup native select (gaya Spark) */
function enhanceSelects(root){
    (root||document).querySelectorAll('select.form-select').forEach(function(el){
        if(el.dataset.ssdReady || el.disabled) return;
        el.dataset.ssdReady='1';
        var wrap=document.createElement('div');
        wrap.className='ssd';
        el.parentNode.insertBefore(wrap,el);
        wrap.appendChild(el);
        el.classList.add('ssd-native');
        var trigger=document.createElement('button');
        trigger.type='button';
        trigger.className='ssd-trigger';
        var menu=document.createElement('div');
        menu.className='ssd-menu';
        wrap.appendChild(trigger);
        wrap.appendChild(menu);
        function close(){wrap.classList.remove('open');}
        function paint(){
            menu.innerHTML='';
            Array.prototype.forEach.call(el.options,function(o){
                var b=document.createElement('button');
                b.type='button';
                b.className='ssd-option'+(o.selected?' is-active':'');
                b.textContent=(o.textContent||'').trim();
                b.addEventListener('click',function(ev){
                    ev.stopPropagation();
                    el.value=o.value;
                    el.dispatchEvent(new Event('change',{bubbles:true}));
                    paint(); close();
                });
                menu.appendChild(b);
            });
            var cur=el.options[el.selectedIndex];
            var txt=cur?(cur.textContent||'').trim():'Pilih...';
            trigger.innerHTML='<span></span><i class="bi bi-chevron-down"></i>';
            var sp=trigger.querySelector('span');
            sp.textContent=txt;
            if(!cur||!el.value){sp.className='ssd-placeholder';}
        }
        trigger.addEventListener('click',function(e){
            e.stopPropagation();
            var wasOpen=wrap.classList.contains('open');
            document.querySelectorAll('.ssd.open').forEach(function(w){w.classList.remove('open');});
            if(!wasOpen) wrap.classList.add('open');
        });
        el.addEventListener('change',paint);
        paint();
    });
}
document.addEventListener('click',function(){document.querySelectorAll('.ssd.open').forEach(function(w){w.classList.remove('open');});});
if(document.readyState!=='loading'){enhanceSelects();}else{document.addEventListener('DOMContentLoaded',function(){enhanceSelects();});}
</script>
@stack('scripts')
</body>
</html>
