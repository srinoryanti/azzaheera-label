<ul class="account-nav">

    <li>
        <a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">
            <i class="fa fa-home me-2"></i>
            Dasbor
        </a>
    </li>

    <li>
        <a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s">
            <i class="fa fa-shopping-bag me-2"></i>
            Pesanan Saya
        </a>
    </li>

    <li>
        <a href="{{ route('account.password.edit') }}" class="menu-link menu-link_us-s">
            <i class="fa fa-lock me-2"></i>
            Ubah Password
        </a>
    </li>

    <li>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf

            <a href="{{ route('logout') }}"
               class="menu-link menu-link_us-s"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">

                <i class="fa fa-sign-out me-2"></i>
                Keluar

            </a>
        </form>
    </li>

</ul>