<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="index.html"><img src="{{ asset('assets') }}/images/logo/logo.png" alt="Logo"
                            srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-item">
                    <a href="index.html" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('room*') ? 'active' : '' }}">
                    <a href="{{ route('room.index') }}" class='sidebar-link'>
                        <i class="bi bi-door-open"></i>
                        <span>Room</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('guest*') ? 'active' : '' }}">
                    <a href="{{ route('guest.index') }}" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>Guest</span>
                    </a>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
