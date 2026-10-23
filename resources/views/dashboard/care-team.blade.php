@extends('layouts.patient.patient-layout')

@section('content')
<div class="health-dashboard"><section class="dashboard-hero compact-hero"><div><span class="eyebrow">CARE DIRECTORY</span><h1>Find the right support</h1><p>Explore MySihat’s care team and book an appointment when you are ready.</p></div><a class="primary-action" href="{{ route('appointmentCreate') }}">Book appointment <i class="fas fa-calendar-plus"></i></a></section>
<section class="team-grid">@foreach($doctors as $doctor)<article class="team-card"><div class="member-avatar large">{{ strtoupper(substr($doctor->name, 3, 1)) }}</div><span class="eyebrow">{{ $doctor->expertise }}</span><h2>{{ $doctor->name }}</h2><p>Available through MySihat online consultations.</p><a href="{{ route('appointmentCreate') }}">Choose this specialist <i class="fas fa-arrow-right"></i></a></article>@endforeach</section></div>
@endsection
