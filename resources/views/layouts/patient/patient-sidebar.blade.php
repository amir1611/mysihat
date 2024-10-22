<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion d-none d-lg-block" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('patient.chatbot') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('build/assets/logo-mysihat-white.svg') }}" alt="MySihat Logo" class="img-fluid"
                style="max-width: 100%; height: auto;">
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    @foreach (config('patient-navigation.items') as $item)
        <li class="nav-item">
            <a class="nav-link" href="{{ route($item['route']) }}" style="font-size: 1.0rem;">
                <i class="{{ $item['icon'] }}" style="font-size: 1.2rem;"></i>
                <span>{{ $item['name'] }}</span>
            </a>
        </li>
    @endforeach

</ul>
