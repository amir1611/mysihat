<header class="clinical-topbar">
    <button class="mobile-menu-toggle" type="button" aria-label="Open menu"><i class="fas fa-bars"></i></button>
    <div><span class="eyebrow">CLINICAL WORKSPACE</span><h2>{{ now()->format('l, d F') }}</h2></div>
    <div class="topbar-actions"><a href="{{ route('doctor.appointments') }}" class="topbar-icon" aria-label="Appointments"><i class="fas fa-calendar-check"></i></a><a href="{{ route('doctor.prescriptions') }}" class="topbar-icon" aria-label="Prescriptions"><i class="fas fa-prescription-bottle"></i></a></div>
</header>
