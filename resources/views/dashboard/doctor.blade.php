@extends('layouts.doctor.doctor-layout')

@section('content')
<div class="health-dashboard">
    <section class="dashboard-hero doctor-hero"><div><span class="eyebrow">CLINICAL WORKSPACE</span><h1>Focus on the patients who need you most.</h1><p>Here is a clear view of your day and upcoming consultations.</p></div><a class="primary-action" href="{{ route('time-slots.index') }}"><i class="fas fa-clock"></i> Manage availability</a></section>
    <section class="metric-grid">
        <article class="metric-card accent-blue"><i class="fas fa-calendar-day"></i><span>Today</span><strong>{{ $todayCount }}</strong><small>scheduled consultations</small></article>
        <article class="metric-card accent-green"><i class="fas fa-users"></i><span>Patients</span><strong>{{ $patientCount }}</strong><small>people in your care</small></article>
        <article class="metric-card accent-violet"><i class="fas fa-clock"></i><span>Next steps</span><strong>{{ $appointments->count() }}</strong><small>upcoming appointments</small></article>
    </section>
    <section class="dashboard-grid"><article class="panel panel-wide"><div class="panel-heading"><div><span class="eyebrow">UP NEXT</span><h2>Your appointment queue</h2></div><a href="{{ route('appointmentList') }}">Open schedule <i class="fas fa-arrow-right"></i></a></div>
    @forelse($appointments as $appointment)<div class="appointment-row"><div class="date-chip"><strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d') }}</strong><span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('M') }}</span></div><div><strong>{{ optional($appointment->patient)->name ?? 'Patient' }}</strong><p>{{ $appointment->reason ?: 'General consultation' }}</p></div><time>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</time></div>
    @empty <div class="empty-state"><i class="far fa-calendar"></i><h3>No upcoming appointments</h3><p>Set your availability to let patients find a suitable time.</p></div>@endforelse
    </article><article class="panel care-nudge"><span class="eyebrow">WORKFLOW</span><h2>Keep your calendar current.</h2><p>Open time slots give patients a simple way to get the care they need.</p><a class="secondary-action" href="{{ route('time-slots.index') }}">Set time slots <i class="fas fa-arrow-right"></i></a></article></section>
</div>
@endsection
