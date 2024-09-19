<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Hamburger Menu for Mobile View -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Dropdown for Mobile Menu -->
    <div class="dropdown d-md-none">
        <div class="dropdown-menu dropdown-menu-left shadow animated--grow-in" aria-labelledby="sidebarToggleTop">
            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-cog"></i> Settings
            </a>
        </div>
    </div>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-black small">{{ Auth::user()->name }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('build/assets/profile-circle.svg') }}" alt="Profile">
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
