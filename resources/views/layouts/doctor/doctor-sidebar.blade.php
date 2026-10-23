<aside class="clinical-sidebar">
    <a class="clinical-brand" href="{{ route('doctor.dashboard') }}"><span class="brand-mark"><i class="fas fa-plus"></i></span><span>MySihat <small>CLINICAL</small></span></a>
    <div class="clinical-user"><span class="member-avatar large">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->expertise ?: 'Clinical staff' }}</small></div></div>
    <nav class="clinical-nav" aria-label="Clinical navigation">
        <span class="nav-label">WORKSPACE</span>
        @foreach (config('doctor-navigation.items') as $item)
            <a class="{{ request()->routeIs($item['route']) ? 'active' : '' }}" href="{{ route($item['route']) }}"><i class="{{ $item['icon'] }}"></i><span>{{ $item['name'] }}</span></a>
        @endforeach
        <span class="nav-label">ACCOUNT</span>
        <a href="{{ route('doctor.profile') }}"><i class="fas fa-fw fa-user-cog"></i><span>Profile</span></a>
        <a href="{{ route('doctor.change.password') }}"><i class="fas fa-fw fa-key"></i><span>Security</span></a>
    </nav>
    <form class="clinical-logout" action="{{ route('logout') }}" method="POST">@csrf<button type="submit"><i class="fas fa-sign-out-alt"></i> Sign out</button></form>
</aside>
