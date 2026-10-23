@extends('layouts.patient.patient-layout')

@section('content')
<div class="health-dashboard">
    <section class="dashboard-hero">
        <div>
            <span class="eyebrow">MY HEALTH SPACE</span>
            <h1>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 18 ? 'afternoon' : 'evening') }}, {{ auth()->user()->name }}.</h1>
            <p>Your care plan, appointments, and wellbeing tools—together in one calm place.</p>
        </div>
        <a class="primary-action" href="{{ route('appointmentCreate') }}"><i class="fas fa-plus"></i> Book appointment</a>
    </section>

    <section class="metric-grid">
        <article class="metric-card accent-blue"><i class="fas fa-calendar-check"></i><span>Upcoming care</span><strong>{{ $appointments->count() }}</strong><small>appointments in your schedule</small></article>
        <article class="metric-card accent-green"><i class="fas fa-file-medical"></i><span>Health summaries</span><strong>{{ $recordCount }}</strong><small>saved consultation notes</small></article>
        <article class="metric-card accent-violet"><i class="fas fa-chart-line"></i><span>Health readings</span><strong>{{ $bloodSugarCount }}</strong><small>blood sugar check-ins</small></article>
    </section>

    <section class="dashboard-grid">
        <article class="panel panel-wide">
            <div class="panel-heading"><div><span class="eyebrow">YOUR SCHEDULE</span><h2>Upcoming appointments</h2></div><a href="{{ route('appointmentList') }}">View all <i class="fas fa-arrow-right"></i></a></div>
            @forelse($appointments as $appointment)
                <div class="appointment-row">
                    <div class="date-chip"><strong>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('d') }}</strong><span>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('M') }}</span></div>
                    <div><strong>{{ optional($appointment->doctor)->name ?? 'Care team' }}</strong><p>{{ optional($appointment->doctor)->expertise ?? 'Consultation' }}</p></div>
                    <time>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</time>
                </div>
            @empty
                <div class="empty-state"><i class="far fa-calendar"></i><h3>Your schedule is clear</h3><p>Book a consultation when you need one.</p></div>
            @endforelse
        </article>
        <article class="panel care-nudge">
            <span class="eyebrow">WELLBEING CHECK-IN</span>
            <h2>How are you feeling today?</h2>
            <p>Start a private conversation with MySihat Bot for helpful next steps.</p>
            <a class="secondary-action" href="{{ route('patient.chatbot') }}">Talk to MySihat Bot <i class="fas fa-comment-dots"></i></a>
        </article>
    </section>

    <section class="quick-links">
        <a href="{{ route('patient.bmi') }}"><i class="fas fa-weight"></i><span>BMI calculator</span><small>Understand your range</small></a>
        <a href="{{ route('patient.blood-sugar') }}"><i class="fas fa-droplet"></i><span>Blood sugar</span><small>Track your readings</small></a>
        <a href="{{ route('patient.records') }}"><i class="fas fa-notes-medical"></i><span>My records</span><small>View care summaries</small></a>
        <a href="{{ route('patient.care-team') }}"><i class="fas fa-user-doctor"></i><span>Care team</span><small>Find a specialist</small></a>
    </section>
</div>
@endsection
