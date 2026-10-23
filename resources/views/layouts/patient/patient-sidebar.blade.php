<aside class="clinical-sidebar patient-sidebar" id="appSidebar">
    <a class="clinical-brand" href="{{ route('patient.dashboard') }}"><span class="brand-mark"><i class="fas fa-plus"></i></span><span>MySihat<small>PERSONAL HEALTH</small></span></a>
    <div class="clinical-user"><span class="member-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span><span><strong>{{ Auth::user()->name }}</strong><small>Patient portal</small></span></div>
    <nav class="clinical-nav"><span class="nav-label">YOUR CARE</span>@foreach (config('patient-navigation.items') as $item)<a href="{{ route($item['route']) }}" class="{{ request()->routeIs($item['route']) ? 'active' : '' }}"><i class="{{ $item['icon'] }}"></i><span>{{ $item['name'] }}</span></a>@endforeach</nav>
    <form class="clinical-logout" method="POST" action="{{ route('logout') }}">@csrf<button type="submit"><i class="fas fa-sign-out-alt mr-2"></i> Sign out</button></form>
</aside>
