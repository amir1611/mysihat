<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-lg-block" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('build/assets/logo-mysihat-white.svg') }}" alt="MySihat Logo"
                style="width: 180px; height: auto;">
        </div>
    </a>


    <hr class="sidebar-divider my-0">


    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-cog"></i>
            <span>Settings</span>
        </a>
    </li>
    <!-- Add more sidebar items here -->
</ul>
