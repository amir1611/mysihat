@php
    // Sidebar items for patient
    $sidebarItems = [
        [
            'route' => 'patient.dashboard',
            'name' => 'Dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],
        [
            'route' => 'chatbot.index',
            'name' => 'Chatbot',
            'icon' => 'fas fa-comments',
        ],
    ];
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('patient.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('build/assets/logo-mysihat-white.svg') }}" alt="MySihat Logo"
                style="width: 180px; height: auto;">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    @foreach ($sidebarItems as $item)
        <li class="nav-item {{ Route::currentRouteName() == $item['route'] ? 'active' : '' }}">
            <a class="nav-link" href="{{ route($item['route']) }}">
                <i class="{{ $item['icon'] }}"></i>
                <span>{{ $item['name'] }}</span>
            </a>
        </li>
    @endforeach

</ul>
